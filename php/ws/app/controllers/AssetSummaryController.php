<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/AssetSummary.php';
require_once __DIR__ . '/../models/AssetDetail.php';
require_once __DIR__ . '/../models/AssetRatio.php';
require_once __DIR__ . '/../models/AssetRatioCountry.php';
require_once __DIR__ . '/../models/AssetRatioCash.php';
require_once __DIR__ . '/../models/AssetRatioScale.php';

class AssetSummaryController {
    public function index() {
        $pdo = Database::getInstance();
        $assets = AssetSummary::getAll($pdo);
        // 最大履歴番号を取得
        $historyNo = isset($_GET['history_no']) ? $_GET['history_no'] : null;
        if ($historyNo === null && !empty($assets)) {
            // $assetsは履歴番号降順なので先頭が最大
            $historyNo = $assets[0]['履歴番号'];
        }
        $assetDetails = AssetDetail::getAll($pdo, $historyNo);
        $assetRatios = AssetRatio::getAll($pdo, $historyNo);
        $assetRatioCountries = AssetRatioCountry::getAll($pdo, $historyNo);
        $assetRatioCashs = AssetRatioCash::getAll($pdo, $historyNo);
        require_once __DIR__ . '/../models/AssetRatioAccount.php';
        $assetRatioScales = AssetRatioScale::getAll($pdo, $historyNo);
        $assetRatioAccounts = AssetRatioAccount::getAll($pdo, $historyNo);
        include __DIR__ . '/../views/asset_summary.php';
    }
    public function delete_asset_manager() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['history_no'])) {
            $historyNo = $_POST['history_no'];
            $pdo = Database::getInstance();
            // 資産管理 削除
            $sql1 = file_get_contents(__DIR__ . '/../../../ws/sql/delete_asset_manager.sql');
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([$historyNo]);
            // 資産管理明細 削除
            $sql2 = file_get_contents(__DIR__ . '/../../../ws/sql/delete_asset_detail.sql');
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$historyNo]);
        }
        header('Location: ?action=asset_summary');
        exit;
    }
    public function import_asset_file() {
        // ファイルアップロード処理
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            header('Location: ?action=asset_summary');
            exit;
        }
        $uploadDir = __DIR__ . '/../../../ws/temp/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($_FILES['import_file']['name']);
        // 拡張子チェック
        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'tsv') {
            $_SESSION['asset_import_error'] = 'ファイルを取り込めませんでした（拡張子が.tsvではありません）';
            header('Location: ?action=asset_summary');
            exit;
        }
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['import_file']['tmp_name'], $targetPath)) {
            // TSVファイルを資産管理明細テーブルに取り込む
            $pdo = Database::getInstance();
            $userId = $_SESSION['user_id'] ?? '';
            if (($handle = fopen($targetPath, 'r')) !== false) {
                // 1行目をタブ区切りで取得
                $header = fgetcsv($handle, 0, "\t", '"', "\\");
                // 区切り文字がタブでない場合はエラー
                if (count($header) === 1) {
                    $_SESSION['asset_import_error'] = 'ファイルを取り込めませんでした（タブ区切りではありません）';
                    fclose($handle);
                    header('Location: ?action=asset_summary');
                    exit;
                }
                $colIndex = array_flip($header);
                // 必須カラムチェック
                $required = ['資産区分コード', '資産コード', '金額', '評価損益'];
                foreach ($required as $col) {
                    if (!isset($colIndex[$col])) {
                        $_SESSION['asset_import_error'] = 'ファイルを取り込めませんでした（必須カラムがありません）';
                        fclose($handle);
                        header('Location: ?action=asset_summary');
                        exit;
                    }
                }
                // 履歴番号はSQLファイルから取得
                $sql_next = file_get_contents(__DIR__ . '/../../../ws/sql/get_next_history_no.sql');
                $stmt = $pdo->query($sql_next);
                $row = $stmt->fetch();
                $historyNo = $row ? $row['next_no'] : 1;
                // 明細INSERT SQLもファイルから取得
                $sql = file_get_contents(__DIR__ . '/../../../ws/sql/insert_asset_detail.sql');
                $insert = $pdo->prepare($sql);
                $firstDate = null;
                while (($data = fgetcsv($handle, 0, "\t", '"', "\\")) !== false) {
                    if ($firstDate === null && isset($colIndex['年月日'])) {
                        $firstDate = $data[$colIndex['年月日']];
                    }
                    $kubun = $colIndex['資産区分コード'] ?? null;
                    $code = $colIndex['資産コード'] ?? null;
                    $amount = $colIndex['金額'] ?? null;
                    $profit = $colIndex['評価損益'] ?? null;
                    $insert->execute([
                        $historyNo,
                        $kubun !== null ? $data[$kubun] : null,
                        $code !== null ? $data[$code] : null,
                        $amount !== null ? str_replace([','], '', $data[$amount]) : null,
                        $profit !== null ? str_replace([','], '', $data[$profit]) : null,
                        $userId
                    ]);
                }
                fclose($handle);
                // 資産管理テーブルにも1件INSERT（SQLファイルから取得）
                if ($firstDate !== null) {
                    $sql2 = file_get_contents(__DIR__ . '/../../../ws/sql/insert_asset_manager.sql');
                    $pdo->prepare($sql2)->execute([$historyNo, $userId, $firstDate]);
                }
            }
            header('Location: ?action=asset_summary');
            exit;
        } else {
            echo 'ファイルのアップロードに失敗しました。';
            echo '<a href="?action=asset_summary">戻る</a>';
        }
    }
}
