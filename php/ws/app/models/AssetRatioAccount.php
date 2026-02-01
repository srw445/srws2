<?php
require_once __DIR__ . '/../models/Database.php';

class AssetRatioAccount {
    public static function getAll($pdo, $historyNo = null) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_asset_ratio_account.sql');
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
        if ($historyNo !== null) {
            $sql_no_comments = preg_replace(
                "/and 管理\.履歴番号 = [0-9]+/",
                'and 管理.履歴番号 = :history_no',
                $sql_no_comments
            );
            $stmt = $pdo->prepare($sql_no_comments);
            $stmt->bindValue(':history_no', $historyNo, \PDO::PARAM_INT);
        } else {
            $sql_no_comments = preg_replace(
                "/and 管理\.履歴番号 = :history_no/",
                '',
                $sql_no_comments
            );
            $stmt = $pdo->prepare($sql_no_comments);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
