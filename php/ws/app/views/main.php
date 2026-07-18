<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>メインメニュー</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <?php
    require_once __DIR__.'/../models/Message.php';
    $userId = $_SESSION['user_id'] ?? '';
    $msg = Message::getRandomMessage($userId);
    if ($msg) {
        echo '<div class="alert alert-info mt-2 mb-3" style="font-size:1.1em;">'.nl2br(htmlspecialchars($msg)).'</div>';
    }
    ?>
    <h2>メインメニュー</h2>
    <div class="d-flex flex-column gap-2" style="max-width: 300px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=reading_records'">読書記録</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=video_records'">映像記録</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=kubun_codes'">区分コードマスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=rss_feed'">RSSリーダー</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_kubun_master'">資産区分マスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_master'">資産マスタ</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_summary'">資産管理</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=freee_transaction'">freee取引確認</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=freee_transaction_summary'">freee取引確認(集計)</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=resident_tax'">住民税</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=calendar'">カレンダー</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=exam_study'">試験学習</button>
    </div>
    <!-- ログアウトボタンを一番下に配置 -->
    <div class="mt-5 d-flex justify-content-start">
        <button type="button" class="btn btn-primary mt-3" onclick="location.href='?action=logout'">ログアウト</button>
    </div>
    </div>
</body>
</html>