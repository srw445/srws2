<?php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>RSSリーダー</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-secondary">
    <h2>RSSリーダー</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
    </div>
    <form method="get" class="mb-3">
        <input type="hidden" name="action" value="rss_feed">
        <label for="kubun00900" class="form-label">フィード選択:</label>
        <select id="kubun00900" name="kubun00900" class="form-select" style="max-width:300px;display:inline-block;">
            <option value="">--選択してください--</option>
            <?php if (isset($kubunList)) foreach ($kubunList as $row): ?>
                    <option value="<?php echo htmlspecialchars($row['コード']); ?>"<?php if (isset($_GET['kubun00900']) && $_GET['kubun00900'] === $row['コード']) echo ' selected'; ?>>
                    <?php echo htmlspecialchars($row['コード名']); ?>
                </option>
            <?php endforeach; ?>
        </select>
            <button type="submit" class="btn btn-primary ms-2">表示</button>
    </form>
    <?php if (empty($feedUrls) || !is_array($feedUrls)): ?>
        <!-- RSSフィードURL未設定時は何も表示しない -->
    <?php elseif (!empty($rssItems) && is_array($rssItems)): ?>
        <div style="max-height: 700px; overflow-y: auto; margin-top: 30px;">
            <table class="table table-hover table-sm" style="width: 100%;">
                <thead style="position: sticky; top: 0; background: white; z-index: 2;">
                    <tr>
                        <?php
                        $currentSort = $_GET['sort'] ?? 'pubDate';
                        $currentOrder = $_GET['order'] ?? 'DESC';
                        $headers = [
                            'feed_name' => '名称',
                            'content' => '内容',
                            'pubDate' => '公開日',
                        ];
                        foreach ($headers as $key => $label) {
                            $order = ($currentSort === $key && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                            $params = $_GET;
                            $params['sort'] = $key;
                            $params['order'] = $order;
                            $query = http_build_query($params);
                            echo "<th><a href=\"?{$query}\">{$label}</a></th>";
                        }
                        ?>
                        <th>リンク</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rssItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['feed_name'] ?? ''); ?></td>
                        <td><?php echo html_entity_decode($item['content'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?></td>
                        <td>
                        <?php
                        $pubDate = $item['pubDate'] ?? '';
                        if ($pubDate) {
                            $dt = new DateTime($pubDate);
                            $w = ['日','月','火','水','木','金','土'];
                            $wday = $w[(int)$dt->format('w')];
                            echo $dt->format("Y年m月d日($wday) H:i:s");
                        }
                        ?>
                        </td>
                        <td><a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">リンク</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">フィードの取得に失敗しました。</div>
    <?php endif; ?>
</body>
</html>
