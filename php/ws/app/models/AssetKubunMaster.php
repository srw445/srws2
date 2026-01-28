<?php
class AssetKubunMaster {
            public function insert($code, $name, $short, $val1, $val2, $val3) {
                $pdo = Database::getInstance();
                $sql = file_get_contents(__DIR__ . '/../../sql/insert_asset_kubun_master.sql');
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$code, $name, $short, $val1, $val2, $val3]);
            }
        public function updateByCode($code, $name, $short, $val1, $val2, $val3) {
            $pdo = Database::getInstance();
            $sql = file_get_contents(__DIR__ . '/../../sql/update_asset_kubun_master_by_code.sql');
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $short, $val1, $val2, $val3, $code]);
        }
    public function getAll($sort = '資産区分コード', $order = 'ASC') {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/get_asset_kubun_master.sql');
        $allowedSorts = ['資産区分コード','資産区分名','資産区分略名','設定値1','設定値2','設定値3'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = '資産区分コード';
        }
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC';
        }
        $sql = rtrim($sql, ";\n\r ") . " ORDER BY `$sort` $order";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getByCode($code) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/get_asset_kubun_master_by_code.sql');
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
}
