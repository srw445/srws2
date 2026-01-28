<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/RssFeed.php';
require_once __DIR__ . '/../models/KubunCode.php';

class RssFeedController {
    public function index() {
        // 未ログインならログイン画面へリダイレクト
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: ?action=login');
            exit;
        }
        $pdo = Database::getInstance();
        // セレクトボックスの選択値を取得
        $selectedKubun = isset($_GET['kubun00900']) ? $_GET['kubun00900'] : '';
        if ($selectedKubun) {
            $feedUrl = RssFeed::getFeedUrlByKubun($pdo, $selectedKubun);
        } else {
            $feedUrl = RssFeed::getFeedUrl($pdo);
        }
        // 区分コード00900のコード名リスト取得
        $kubunModel = new KubunCode();
        $kubunList = array_filter($kubunModel->getKubunCodes(), function($row) {
            return $row['区分コード'] === '00900';
        });

        function fetchRss($url) {
            if (!$url) return '';
            $xml = @simplexml_load_file($url);
            if (!$xml) return '';
            $items = [];
            foreach ($xml->channel->item as $item) {
                $items[] = [
                    'title' => (string)$item->title,
                    'link' => (string)$item->link,
                    'description' => (string)$item->description,
                    'pubDate' => (string)$item->pubDate
                ];
            }
            return $items;
        }

        // デバッグ用: フィードURLが空の場合はログ出力
        if (!$feedUrl) {
            error_log('RSSフィードURLが取得できませんでした。');
        }
        $rssItems = fetchRss($feedUrl);
        // RSS取得失敗時はログ出力
        if ($feedUrl && !$rssItems) {
            error_log('RSSフィードの取得に失敗しました: ' . $feedUrl);
        }
        include __DIR__ . '/../views/rss_feed.php';
    }
}
