<?php
require_once __DIR__ . '/Database.php';

class Message {
    public static function getRandomMessage($userId) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/get_random_message.sql');
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
