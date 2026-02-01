<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>区分コード編集</title>
</head>
<body class="bg-body-secondary">
    <h2>区分コード編集</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=kubun_codes'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="editForm">更新</button>
    </div>
    <form id="editForm" action="?action=update_kubun_code" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['連番'] ?? ''); ?>">
        <table>
            <tr><td>区分コード:</td><td><input type="text" name="区分コード" value="<?php echo htmlspecialchars($record['区分コード'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>区分名:</td><td><input type="text" name="区分名" value="<?php echo htmlspecialchars($record['区分名'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>コード:</td><td><input type="text" name="コード" value="<?php echo htmlspecialchars($record['コード'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>コード名:</td><td><input type="text" name="コード名" value="<?php echo htmlspecialchars($record['コード名'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>コード名2:</td><td><input type="text" name="コード名2" value="<?php echo htmlspecialchars($record['コード名2'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>区分説明:</td><td><input type="text" name="区分説明" value="<?php echo htmlspecialchars($record['区分説明'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>設定値1:</td><td><input type="text" name="設定値1" value="<?php echo htmlspecialchars($record['設定値1'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>設定値2:</td><td><input type="text" name="設定値2" value="<?php echo htmlspecialchars($record['設定値2'] ?? ''); ?>" class="form-control"></td></tr>
            <tr><td>設定値3:</td><td><input type="text" name="設定値3" value="<?php echo htmlspecialchars($record['設定値3'] ?? ''); ?>" class="form-control"></td></tr>
        </table>
    </form>
</body>
</html>
