<?php
class AssetMaster {
    public function getAll($sort = '資産コード', $order = 'ASC') {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/get_asset_master.sql');
        $allowedSorts = ['資産区分名','資産コード','資産名','資産略名','国内外区分名','通貨区分名','口座区分名','長短区分名','売買区分名'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = '資産コード';
        }
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC';
        }
        $sql = rtrim($sql, ";\n\r ") . " ORDER BY `$sort` $order";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/get_asset_master_by_id.sql');
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateById($id, $kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/update_asset_master_by_id.sql');
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade, $id]);
    }

    public function insert($kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade) {
        $pdo = Database::getInstance();
        $sql = file_get_contents(__DIR__ . '/../../sql/insert_asset_master.sql');
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade]);
    }
}
