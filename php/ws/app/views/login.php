<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>ログイン画面</title>
</head>
<body class="bg-body-tertiary">
    <h2>ログイン</h2>
    <form action="?action=authenticate" method="post">
        <table>
            <tr>
                <td>ユーザーID:</td>
                <td><input type="text" name="user_id" id="login-user-id" class="form-control" required></td>
            </tr>
            <tr>
                <td>パスワード:</td>
                <td><input type="password" name="password" id="login-password" class="form-control" required></td>
            </tr>
        </table>
        <button type="submit" class="btn btn-primary mt-3" id="login-submit">ログイン</button>
    </form>
</body>
</html>