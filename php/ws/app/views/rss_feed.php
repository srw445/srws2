<?php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>RSSフィード表示</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <h2>RSSフィード内容</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
    </div>
    <form method="get" class="mb-3">
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
    <?php if ($rssItems): ?>
        <table class="table table-hover table-sm">
            <thead><tr><th>タイトル</th><th>公開日</th><th>リンク</th></tr></thead>
            <tbody>
            <?php foreach ($rssItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['pubDate']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">リンク</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">フィードの取得に失敗しました。</div>
    <?php endif; ?>
</body>
</html>
