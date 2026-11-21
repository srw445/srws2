<?php
require_once __DIR__ . '/../models/Database.php';

class AssetSummary {
    public static function getAll($pdo) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_asset_summary.sql');
        $sql = $sqlPath ? file_get_contents($sqlPath) : '';
        if (!$sql) return [];
        // コメント行を除去
        $lines = explode("\n", $sql);
        $filtered = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || strpos($trimmed, '--') === 0) continue;
            $filtered[] = $line;
        }
        $sql_no_comments = implode("\n", $filtered);
        // ; で分割しつつ、空白や改行だけのクエリは除外
        $queries = array_filter(array_map('trim', preg_split('/;[\r\n]+/', $sql_no_comments)));
        $result = [];
        foreach ($queries as $query) {
            if (!$query) continue;
            try {
                if (stripos($query, 'select') === 0) {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll();
                } else {
                    $pdo->exec($query);
                }
            } catch (\PDOException $e) {
                error_log('SQL実行エラー: ' . $e->getMessage() . "\n該当クエリ: " . $query);
                throw $e;
            }
        }
        return $result;
    }

    public static function getCategorySummary($pdo, $historyNo) {
        $totalStmt = $pdo->prepare(
            'select 金額_合計 from 資産集計 where 履歴番号 = ?'
        );
        $totalStmt->execute([$historyNo]);
        $totalAmount = (float)$totalStmt->fetchColumn();

        $categoryTables = [
            '現金' => '資産集計_現金',
            '個別株' => '資産集計_株式',
            '投信' => '資産集計_投信',
            '仮想通貨' => '資産集計_仮想通貨',
            '年金' => '資産集計_年金',
            'コモディティ' => '資産集計_コモディティ',
        ];
        $queries = [];
        $params = [];
        foreach ($categoryTables as $category => $table) {
            $queries[] = "select " . $pdo->quote($category) . " as 資産区分
                ,履歴番号,金額_合計,評価損益_合計
                from {$table}
                where 履歴番号 in (?, ?)";
            $params[] = $historyNo;
            $params[] = $historyNo - 1;
        }

        $stmt = $pdo->prepare(implode("\nunion all\n", $queries));
        $stmt->execute($params);
        $categoryValues = [];
        foreach ($categoryTables as $category => $_table) {
            $categoryValues[$category] = [
                'current_amount' => 0.0,
                'previous_amount' => 0.0,
                'evaluation_profit' => 0.0,
            ];
        }
        foreach ($stmt->fetchAll() as $row) {
            $key = (int)$row['履歴番号'] === (int)$historyNo
                ? 'current_amount'
                : 'previous_amount';
            $categoryValues[$row['資産区分']][$key] = (float)$row['金額_合計'];
            if ($key === 'current_amount') {
                $categoryValues[$row['資産区分']]['evaluation_profit'] =
                    (float)$row['評価損益_合計'];
            }
        }

        $rows = [];
        foreach ($categoryValues as $category => $values) {
            $currentAmount = $values['current_amount'];
            $change = $currentAmount - $values['previous_amount'];
            $profit = $values['evaluation_profit'];
            $profitBase = $currentAmount - $profit;
            $rows[] = [
                '資産区分' => $category,
                '金額' => $currentAmount,
                '構成比' => $totalAmount != 0
                    ? number_format($currentAmount / $totalAmount * 100, 1) . '%'
                    : '',
                '前月比' => $change,
                '前月比割合' => $currentAmount != 0
                ? number_format($change / $currentAmount * 100, 1) . '%'
                : '',
                '評価損益' => $profit,
                '評価損益割合' => $profitBase != 0
                ? number_format($profit / $profitBase * 100, 1) . '%'
                : '',
            ];
        }
        usort($rows, static function ($left, $right) {
            return $right['金額'] <=> $left['金額'];
        });
        $totalAmountForCategories = array_sum(array_column($rows, '金額'));
        $totalChange = array_sum(array_column($rows, '前月比'));
        $totalProfit = array_sum(array_column($rows, '評価損益'));
        $totalProfitBase = $totalAmountForCategories - $totalProfit;
        $rows[] = [
            '資産区分' => '合計',
            '金額' => $totalAmountForCategories,
            '構成比' => $totalAmount != 0
                ? number_format($totalAmountForCategories / $totalAmount * 100, 1) . '%'
                : '',
            '前月比' => $totalChange,
            '前月比割合' => $totalAmountForCategories != 0
                ? number_format($totalChange / $totalAmountForCategories * 100, 1) . '%'
                : '',
            '評価損益' => $totalProfit,
            '評価損益割合' => $totalProfitBase != 0
                ? number_format($totalProfit / $totalProfitBase * 100, 1) . '%'
                : '',
        ];
        return $rows;
    }
}
