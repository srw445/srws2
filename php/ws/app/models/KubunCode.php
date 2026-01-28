
<?php
require_once 'Database.php';

class KubunCode {
        // 区分コード・コードからコード名を取得
        public function getKubunCodeName($kubunCode, $code) {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../sql/get_kubun_code_name.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kubunCode, $code]);
            $row = $stmt->fetch();
            return $row ? $row['コード名'] : '';
        }
    public function getKubunCodes($sort = '区分コード', $order = 'ASC') {
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../sql/get_kubun_codes.sql');
            $allowedSorts = ['区分コード','区分名','コード','コード名','コード名2','区分説明','設定値1','設定値2','設定値3'];
            if (!in_array($sort, $allowedSorts)) {
                $sort = '区分コード';
            }
            if ($order !== 'ASC' && $order !== 'DESC') {
                $order = 'ASC';
            }
            $sql .= " ORDER BY `$sort` $order, `コード` ASC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getKubunCodeById($id) {
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../sql/get_kubun_code_by_id.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateKubunCode($id, $data) {
        try {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../sql/update_kubun_code.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['区分名'],
                $data['コード名'],
                $data['コード名2'],
                $data['区分説明'],
                $data['設定値1'],
                $data['設定値2'],
                $data['設定値3'],
                $id
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function insertKubunCode($data) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/insert_kubun_code.sql');
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['区分コード'],
            $data['区分名'],
            $data['コード'],
            $data['コード名'],
            $data['コード名2'],
            $data['区分説明'],
            $data['設定値1'],
            $data['設定値2'],
            $data['設定値3']
            // 削除FはSQLで0固定なので不要
        ]);
    }
}
