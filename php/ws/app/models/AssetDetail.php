<?php
require_once __DIR__ . '/../models/Database.php';

class AssetDetail {
    public static function getAll($pdo, $historyNo = null) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_asset_detail.sql');
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
        // 履歴番号のWHERE句をパラメータ化
        $sql_no_comments = preg_replace(
            "/where 明細\.履歴番号 = [0-9]+/",
            'where 明細.履歴番号 = :history_no',
            $sql_no_comments
        );
        // ; で分割しつつ、空白や改行だけのクエリは除外
        $queries = array_filter(array_map('trim', preg_split('/;[\r\n]+/', $sql_no_comments)));
        $result = [];
        foreach ($queries as $query) {
            if (!$query) continue;
            try {
                if (stripos($query, 'select') === 0) {
                    $stmt = $pdo->prepare($query);
                    if (strpos($query, ':history_no') !== false) {
                        $stmt->bindValue(':history_no', $historyNo !== null ? $historyNo : 48, \PDO::PARAM_INT);
                    }
                    $stmt->execute();
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
