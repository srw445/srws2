<?php
class LogoutController {
    public function index() {
        // ログアウト処理（セッション破棄）
        session_start();
        session_destroy();
        // ログイン画面にリダイレクト
        header('Location: ?action=login');
        exit;
    }
}
?>