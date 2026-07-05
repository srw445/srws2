<?php
require_once __DIR__ . '/../models/Database.php';

class ResidentTaxController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $residentTaxRows = [];
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../../ws/sql/get_resident_tax.sql');
            $stmt = $pdo->prepare($sql);
            $userId = $_SESSION['user_id'] ?? '';
            $stmt->execute([$userId]);
            $residentTaxRows = $stmt->fetchAll();
        } catch (PDOException $e) {
            $residentTaxRows = [];
        }

        include __DIR__ . '/../views/resident_tax.php';
    }
}
