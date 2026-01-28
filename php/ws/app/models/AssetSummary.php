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
}
