<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
    $dt = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
    $now = $dt->format('Y-m-d H:i');
    echo '<div style="background:#f8f9fa;padding:6px 16px 2px 16px;text-align:right;color:#333;font-size:0.95em;">'.
        htmlspecialchars($now).'　ログイン中：'.htmlspecialchars($_SESSION['user_id']).'</div>';
}
?>
