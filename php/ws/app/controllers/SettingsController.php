<?php
class SettingsController {
    public function index() {
        // 設定ビューを表示
        include '../app/views/settings.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            // 更新処理（ここではダミー）
            echo "<p>設定を更新しました: $email</p>";
            echo '<a href="?action=settings">戻る</a>';
        }
    }
}
?>