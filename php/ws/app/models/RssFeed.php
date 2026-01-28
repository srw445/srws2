<?php
class RssFeed {
    public static function getFeedUrl($pdo) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_rss_feed.sql');
        $sql = $sqlPath ? file_get_contents($sqlPath) : '';
        if (!$sql) return '';
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch();
        return $row ? $row['フィード'] : '';
    }

    public static function getFeedUrlByKubun($pdo, $kubun) {
        $sqlPath = realpath(__DIR__ . '/../../sql/get_rss_feed_by_kubun.sql');
        $sql = $sqlPath ? file_get_contents($sqlPath) : '';
        if (!$sql) return '';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kubun]);
        $row = $stmt->fetch();
        return $row ? $row['フィード'] : '';
    }
}
