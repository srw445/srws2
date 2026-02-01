<?php
// 区分コード追加画面
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>区分コード追加</title>
</head>
<body class="bg-body-secondary">
    <h2>区分コード追加</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=kubun_codes'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="addForm">追加</button>
    </div>
    <form id="addForm" method="post" action="?action=insert_kubun_code">
        <table>
            <tr><td>区分コード:</td><td><input type="text" name="区分コード" class="form-control" required></td></tr>
            <tr><td>区分名:</td><td><input type="text" name="区分名" class="form-control" required></td></tr>
            <tr><td>コード:</td><td><input type="text" name="コード" class="form-control" required></td></tr>
            <tr><td>コード名:</td><td><input type="text" name="コード名" class="form-control" required></td></tr>
            <tr><td>コード名2:</td><td><input type="text" name="コード名2" class="form-control"></td></tr>
            <tr><td>区分説明:</td><td><input type="text" name="区分説明" class="form-control"></td></tr>
            <tr><td>設定値1:</td><td><input type="text" name="設定値1" class="form-control"></td></tr>
            <tr><td>設定値2:</td><td><input type="text" name="設定値2" class="form-control"></td></tr>
            <tr><td>設定値3:</td><td><input type="text" name="設定値3" class="form-control"></td></tr>
        </table>
    </form>
</body>
</html>
