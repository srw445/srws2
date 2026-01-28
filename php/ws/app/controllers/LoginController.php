<?php
class LoginController {
    public function index() {
        // ビューを表示
        include '../app/views/login.php';
    }

    public function authenticate() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['user_id'];
            $password = $_POST['password'];

            // モデルを使って認証
            $userModel = new User();
            $result = $userModel->authenticate($username, $password);
            if ($result === true) {
                // ログイン成功を記録
                $userModel->recordLoginAttempt($username, $password, '1');
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $username;
                // ログイン成功したらメインメニューにリダイレクト
                header('Location: ?action=main');
                exit;
            } elseif (is_string($result)) {
                // エラーメッセージを表示してログイン画面に戻る
                // ログイン試行は失敗として記録
                $userModel->recordLoginAttempt($username, $password, '0');
                echo "<p>データベースエラー: $result</p>";
                include '../app/views/login.php';
            } else {
                // 認証失敗メッセージを表示してログイン画面に戻る
                $userModel->recordLoginAttempt($username, $password, '0');
                echo "<p>ユーザー名またはパスワードが間違っています。</p>";
                include '../app/views/login.php';
            }
        } else {
            // POSTでない場合はログイン画面を表示
            include '../app/views/login.php';
        }
    }
}
?>