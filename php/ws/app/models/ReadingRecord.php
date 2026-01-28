<?php
require_once 'Database.php';

class ReadingRecord {
    private function loadSql($filename) {
        $path = __DIR__ . '/../../sql/' . $filename;
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: $filename");
        }
        return trim(file_get_contents($path));
    }

    public function getReadingRecords($userId, $sort = '読了日', $order = 'ASC') {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_reading_records.sql');
            $sql .= " ORDER BY `$sort` $order";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getReadingRecordById($id, $userId) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_reading_record_by_id.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateReadingRecord($id, $data) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('update_reading_record.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['タイトル'],
                $data['作者'],
                $data['出版社'],
                $data['形式'],
                $data['ジャンル'],
                $data['ページ数'],
                $data['定価'],
                $data['受賞'],
                $data['初版日'],
                $data['読了日'],
                $data['表紙ファイル名'],
                $data['備考'],
                $data['評価'],
                $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function insertReadingRecord($data) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('insert_reading_record.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['タイトル'],
                $data['作者'],
                $data['出版社'],
                $data['形式'],
                $data['ジャンル'],
                $data['ページ数'],
                $data['定価'],
                $data['受賞'],
                $data['初版日'],
                $data['読了日'],
                $data['表紙ファイル名'],
                $data['備考'],
                $data['評価'],
                $data['ユーザID']
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
?>