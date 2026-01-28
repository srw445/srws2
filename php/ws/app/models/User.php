<?php
require_once 'Database.php';

class User {
    private function loadSql($filename) {
        $path = __DIR__ . '/../../sql/' . $filename;
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: $filename");
        }
        return trim(file_get_contents($path));
    }

    public function authenticate($username, $password) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('authenticate.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function recordLoginAttempt($username, $password, $result) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('record_login_attempt.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $result]);
            return true;
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>