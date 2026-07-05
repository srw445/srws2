<?php
require_once 'Database.php';

class FreeeTransaction {
    private function loadSql($filename) {
        $path = __DIR__ . '/../../sql/' . $filename;
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: $filename");
        }
        return trim(file_get_contents($path));
    }

    public function getNextImportNo() {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_next_freee_import_no.sql');
            $stmt = $pdo->query($sql);
            $row = $stmt ? $stmt->fetch() : false;
            return $row ? (int)$row['next_no'] : 1;
        } catch (PDOException $e) {
            return 1;
        } catch (Exception $e) {
            return 1;
        }
    }

    public function getLatestTransactions($userId) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_latest_freee_transactions.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function insertRows($userId, $importNo, array $rows) {
        $pdo = null;
        try {
            $pdo = Database::getInstance();
            $pdo->beginTransaction();
            $sql = $this->loadSql('insert_freee_transaction.sql');
            $stmt = $pdo->prepare($sql);
            foreach ($rows as $row) {
                $stmt->execute([
                    $userId,
                    $importNo,
                    $row['収支区分'] ?? null,
                    $row['管理番号'] ?? null,
                    $row['発生日'] ?? null,
                    $row['支払期日'] ?? null,
                    $row['取引先'] ?? null,
                    $row['勘定科目'] ?? null,
                    $row['税区分'] ?? null,
                    $row['金額'] ?? null,
                    $row['税計算区分'] ?? null,
                    $row['税額'] ?? null,
                    $row['備考'] ?? null,
                    $row['品目'] ?? null,
                    $row['部門'] ?? null,
                    $row['メモタグ'] ?? null,
                    $row['支払日'] ?? null,
                    $row['支払口座'] ?? null,
                    $row['支払金額'] ?? null,
                ]);
            }
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $e->getMessage();
        } catch (Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return $e->getMessage();
        }
    }
}
?>