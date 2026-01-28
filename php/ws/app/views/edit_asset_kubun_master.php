<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>資産区分マスタ編集</title>
</head>
<body class="bg-body-tertiary">
    <h2>資産区分マスタ編集</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_kubun_master'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="editForm">更新</button>
    </div>
    <form id="editForm" method="post" action="?action=update_asset_kubun_master">
        <table>
            <tr><td>資産区分コード:</td><td><input type="text" name="資産区分コード" value="<?php echo htmlspecialchars($record['資産区分コード'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>資産区分名:</td><td><input type="text" name="資産区分名" value="<?php echo htmlspecialchars($record['資産区分名'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>資産区分略名:</td><td><input type="text" name="資産区分略名" value="<?php echo htmlspecialchars($record['資産区分略名'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>設定値1:</td><td><input type="text" name="設定値1" value="<?php echo htmlspecialchars($record['設定値1'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>設定値2:</td><td><input type="text" name="設定値2" value="<?php echo htmlspecialchars($record['設定値2'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>設定値3:</td><td><input type="text" name="設定値3" value="<?php echo htmlspecialchars($record['設定値3'] ?? ''); ?>" style="width: 400px;"></td></tr>
        </table>
    </form>
</body>
</html>
