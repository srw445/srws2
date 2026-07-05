<?php
require_once __DIR__ . '/../models/FreeeTransaction.php';
require_once __DIR__ . '/../models/Database.php';

class FreeeTransactionController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $freeeTransactions = [];
        $accountSummaries = [];
        $selectedMonth = $_GET['month'] ?? date('Y-m');
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../../ws/sql/get_latest_freee_transactions.sql');
            $stmt = $pdo->prepare($sql);
            $userId = $_SESSION['user_id'] ?? '';
            $stmt->execute([$userId, $userId, $selectedMonth]);
            $freeeTransactions = $stmt->fetchAll();

            $summarySql = file_get_contents(__DIR__ . '/../../../ws/sql/get_freee_transaction_account_summary.sql');
            $summaryStmt = $pdo->prepare($summarySql);
            $summaryStmt->execute([$selectedMonth, $userId, $userId, $selectedMonth]);
            $accountSummaries = $summaryStmt->fetchAll();
        } catch (PDOException $e) {
            $freeeTransactions = [];
            $accountSummaries = [];
        }
        include __DIR__ . '/../views/freee_transaction.php';
    }

    public function summary() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $monthlySummaries = [];
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../../ws/sql/get_freee_transaction_monthly_summary.sql');
            $stmt = $pdo->prepare($sql);
            $userId = $_SESSION['user_id'] ?? '';
            $stmt->execute([$userId, $userId]);
            $monthlySummaries = $stmt->fetchAll();
        } catch (PDOException $e) {
            $monthlySummaries = [];
        }

        include __DIR__ . '/../views/freee_transaction_summary.php';
    }

    public function import_freee_transaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=freee_transaction');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした。';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $filename = basename($_FILES['import_file']['name']);
        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'csv') {
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした（拡張子が.csvではありません）';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $rawContent = file_get_contents($_FILES['import_file']['tmp_name']);
        if ($rawContent === false) {
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした。';
            header('Location: ?action=freee_transaction');
            exit;
        }

        if (!preg_match('//u', $rawContent)) {
            if (function_exists('iconv')) {
                $convertedContent = @iconv('CP932', 'UTF-8//IGNORE', $rawContent);
                if ($convertedContent === false) {
                    $convertedContent = @iconv('EUC-JP', 'UTF-8//IGNORE', $rawContent);
                }
                if ($convertedContent !== false && $convertedContent !== null) {
                    $rawContent = $convertedContent;
                }
            }
        }

        $handle = fopen('php://temp', 'r+');
        if ($handle === false) {
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした。';
            header('Location: ?action=freee_transaction');
            exit;
        }
        fwrite($handle, $rawContent);
        rewind($handle);

        $header = fgetcsv($handle, 0, ',', '"', '\\');
        if ($header === false || count($header) < 1) {
            fclose($handle);
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした（ヘッダーを読み取れませんでした）';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $header = array_map(static function ($value) {
            return trim((string)$value);
        }, $header);
        $headerMap = array_flip($header);

        $findHeaderIndex = static function (array $aliases) use ($headerMap) {
            foreach ($aliases as $alias) {
                if (isset($headerMap[$alias])) {
                    return $headerMap[$alias];
                }
            }
            return null;
        };

        $requiredColumns = [
            '収支区分' => ['収支区分'],
            '発生日' => ['発生日'],
            '取引先' => ['取引先'],
            '勘定科目' => ['勘定科目'],
            '税区分' => ['税区分'],
            '金額' => ['金額'],
            '税計算区分' => ['税計算区分'],
            '税額' => ['税額'],
            '備考' => ['備考'],
            '支払日' => ['支払日'],
            '支払口座' => ['支払口座'],
            '支払金額' => ['支払金額'],
        ];

        $columnIndex = [];
        $missingColumns = [];
        foreach ($requiredColumns as $columnName => $aliases) {
            $index = $findHeaderIndex($aliases);
            if ($index === null) {
                $missingColumns[] = $columnName;
            } else {
                $columnIndex[$columnName] = $index;
            }
        }

        $optionalColumns = [
            '管理番号' => ['管理番号'],
            '支払期日' => ['支払期日'],
            '品目' => ['品目'],
            '部門' => ['部門'],
            'メモタグ' => ['メモタグ（複数指定可、カンマ区切り）', 'メモタグ（複数指定可,カンマ区切り）', 'メモタグ'],
        ];
        foreach ($optionalColumns as $columnName => $aliases) {
            $index = $findHeaderIndex($aliases);
            if ($index !== null) {
                $columnIndex[$columnName] = $index;
            }
        }

        if (!empty($missingColumns)) {
            fclose($handle);
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした（必須カラムがありません: ' . implode('、', $missingColumns) . '）';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $normalize = static function ($value, $numeric = false) {
            if ($value === null) {
                return null;
            }
            $value = trim((string)$value);
            if ($value === '') {
                return null;
            }
            if ($numeric) {
                $value = str_replace(',', '', $value);
            }
            return $value;
        };

        $rows = [];
        while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            if (count(array_filter($data, static function ($value) {
                return trim((string)$value) !== '';
            })) === 0) {
                continue;
            }

            $row = array_combine($header, array_pad($data, count($header), null));
            if ($row === false) {
                fclose($handle);
                $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした（列数が一致しません）';
                header('Location: ?action=freee_transaction');
                exit;
            }

            $rows[] = [
                '収支区分' => $normalize($row[$header[$columnIndex['収支区分']]] ?? null),
                '管理番号' => $normalize(isset($columnIndex['管理番号']) ? ($row[$header[$columnIndex['管理番号']]] ?? null) : null),
                '発生日' => $normalize($row[$header[$columnIndex['発生日']]] ?? null),
                '支払期日' => $normalize(isset($columnIndex['支払期日']) ? ($row[$header[$columnIndex['支払期日']]] ?? null) : null),
                '取引先' => $normalize($row[$header[$columnIndex['取引先']]] ?? null),
                '勘定科目' => $normalize($row[$header[$columnIndex['勘定科目']]] ?? null),
                '税区分' => $normalize($row[$header[$columnIndex['税区分']]] ?? null),
                '金額' => $normalize($row[$header[$columnIndex['金額']]] ?? null, true),
                '税計算区分' => $normalize($row[$header[$columnIndex['税計算区分']]] ?? null),
                '税額' => $normalize($row[$header[$columnIndex['税額']]] ?? null, true),
                '備考' => $normalize($row[$header[$columnIndex['備考']]] ?? null),
                '品目' => $normalize(isset($columnIndex['品目']) ? ($row[$header[$columnIndex['品目']]] ?? null) : null),
                '部門' => $normalize(isset($columnIndex['部門']) ? ($row[$header[$columnIndex['部門']]] ?? null) : null),
                'メモタグ' => $normalize(isset($columnIndex['メモタグ']) ? ($row[$header[$columnIndex['メモタグ']]] ?? null) : null),
                '支払日' => $normalize($row[$header[$columnIndex['支払日']]] ?? null),
                '支払口座' => $normalize($row[$header[$columnIndex['支払口座']]] ?? null),
                '支払金額' => $normalize($row[$header[$columnIndex['支払金額']]] ?? null, true),
            ];
        }
        fclose($handle);

        if (empty($rows)) {
            $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした（データ行がありません）';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $model = new FreeeTransaction();
        $importNo = $model->getNextImportNo();
        $result = $model->insertRows($_SESSION['user_id'] ?? '', $importNo, $rows);
        if ($result === true) {
            $_SESSION['freee_import_message'] = 'CSVを取り込みました。';
            header('Location: ?action=freee_transaction');
            exit;
        }

        $_SESSION['freee_import_error'] = 'ファイルを取り込めませんでした。';
        header('Location: ?action=freee_transaction');
        exit;
    }
}
?>