<?php
require_once 'Database.php';

class VideoRecord {
    private function loadSql($filename) {
        $path = __DIR__ . '/../../sql/' . $filename;
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: $filename");
        }
        return trim(file_get_contents($path));
    }

    public function getVideoRecords($userId, $sort = '年月日', $order = 'DESC') {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_video_records.sql');
            $sql = str_replace('video_records', '映像記録', $sql);
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

    public function getVideoRecordById($id, $userId) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('get_video_record_by_id.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function insertVideoRecord($data) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('insert_video_record.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['年月日'],
                $data['タイトル'],
                $data['監督'],
                $data['主演'],
                $data['映像区分'],
                $data['映画館'],
                $data['評価'],
                $data['備考'],
                '0',
                $data['ユーザID']
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function updateVideoRecord($id, $data, $userId) {
        try {
            $pdo = Database::getInstance();
            $sql = $this->loadSql('update_video_record.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['年月日'],
                $data['タイトル'],
                $data['監督'],
                $data['主演'],
                $data['映像区分'],
                $data['映画館'],
                $data['評価'],
                $data['備考'],
                $id,
                $userId
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
?>