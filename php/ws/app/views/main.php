<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>メインメニュー</title>
</head>
<body class="bg-body-secondary">
    <h2>メインメニュー</h2>
    <div class="d-flex flex-column gap-2" style="max-width: 300px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=profile'">プロフィール</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=settings'">設定</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=reading_records'">読書記録</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=video_records'">映像記録</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=kubun_codes'">区分コードマスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_kubun_master'">資産区分マスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_master'">資産マスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=logout'">ログアウト</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=rss_feed'">RSSリーダー</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_summary'">資産管理</button>
    </div>
</body>
</html>