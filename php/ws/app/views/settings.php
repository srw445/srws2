<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>設定</title>
</head>
<body class="bg-body-secondary">
    <h2>設定</h2>
    <form action="?action=update_settings" method="post">
        <label for="email">メール:</label>
        <input type="email" id="email" name="email" value="admin@example.com"><br><br>
        <input type="submit" value="更新">
    </form>
    <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
</body>
</html>