<?php
class RssFeed {
    public static function getFeedUrls($pdo) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_rss_feed.sql');
        $sql = $sqlPath ? file_get_contents($sqlPath) : '';
        if (!$sql) return [];
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();
        // フィードと名称のペアで返す
        return $rows;
    }

    public static function getFeedUrlsByKubun($pdo, $kubun) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_rss_feed_by_kubun.sql');
        $sql = $sqlPath ? file_get_contents($sqlPath) : '';
        if (!$sql) return [];
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kubun]);
        $rows = $stmt->fetchAll();
        // フィードと名称のペアで返す
        return $rows;
    }
}
