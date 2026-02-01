<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/RssFeed.php';
require_once __DIR__ . '/../models/KubunCode.php';

class RssFeedController {
    public function index() {
        // ソートパラメータ取得
        $sort = $_GET['sort'] ?? 'pubDate';
        $order = $_GET['order'] ?? 'DESC';
        // 未ログインならログイン画面へリダイレクト
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?action=login');
            exit;
        }
        $pdo = Database::getInstance();
        // セレクトボックスの選択値を取得
        $selectedKubun = isset($_GET['kubun00900']) ? $_GET['kubun00900'] : '';
        if ($selectedKubun) {
            $feedUrls = RssFeed::getFeedUrlsByKubun($pdo, $selectedKubun);
        } else {
            $feedUrls = RssFeed::getFeedUrls($pdo);
        }
        // 区分コード00900のコード名リスト取得
        $kubunModel = new KubunCode();
        $kubunList = array_filter($kubunModel->getKubunCodes(), function($row) {
            return $row['区分コード'] === '00900';
        });

        function fetchRss($url, $displayField = 'title') {
            if (!$url) return [];
            $xml = @simplexml_load_file($url);
            if (!$xml) return [];
            $items = [];
            if (isset($xml->channel->item)) {
                foreach ($xml->channel->item as $item) {
                    $content = '';
                    if ($displayField === 'description') {
                        $content = (string)$item->description;
                    } else {
                        $content = (string)$item->title;
                    }
                    $items[] = [
                        'content' => $content,
                        'title' => (string)$item->title,
                        'link' => (string)$item->link,
                        'description' => (string)$item->description,
                        'pubDate' => (string)$item->pubDate
                    ];
                }
            } elseif (isset($xml->entry)) {
                foreach ($xml->entry as $item) {
                    $content = '';
                    if ($displayField === 'description') {
                        $content = (string)$item->summary;
                    } else {
                        $content = (string)$item->title;
                    }
                    $link = '';
                    if (isset($item->link)) {
                        $link = (string)$item->link['href'];
                    }
                    $items[] = [
                        'content' => $content,
                        'title' => (string)$item->title,
                        'link' => $link,
                        'description' => (string)$item->summary,
                        'pubDate' => (string)$item->updated
                    ];
                }
            }
            return $items;
        }

        $rssItems = [];
        if (!empty($feedUrls)) {
            foreach ($feedUrls as $feedRow) {
                $url = $feedRow['フィード'];
                $name = $feedRow['名称'] ?? '';
                $displayField = $feedRow['表示項目1'] ?? 'title';
                $items = fetchRss($url, $displayField);
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $item['feed_url'] = $url;
                        $item['feed_name'] = $name;
                        $rssItems[] = $item;
                    }
                }
            }
        } else {
            error_log('RSSフィードURLが取得できませんでした。');
        }
        // ソート処理
        $sortKeys = [
            'feed_name' => 'feed_name',
            'title' => 'title',
            'pubDate' => 'pubDate',
        ];
        $sortKey = $sortKeys[$sort] ?? 'pubDate';
        usort($rssItems, function($a, $b) use ($sortKey, $order) {
            $v1 = $a[$sortKey] ?? '';
            $v2 = $b[$sortKey] ?? '';
            if ($sortKey === 'pubDate') {
                $v1 = strtotime($v1);
                $v2 = strtotime($v2);
            }
            if ($v1 == $v2) return 0;
            if ($order === 'DESC') {
                return ($v1 < $v2) ? 1 : -1;
            } else {
                return ($v1 > $v2) ? 1 : -1;
            }
        });
        // ビューでfeedUrlsも使えるよう渡す
        $feedUrls = $feedUrls ?? [];
        include __DIR__ . '/../views/rss_feed.php';
    }
}
