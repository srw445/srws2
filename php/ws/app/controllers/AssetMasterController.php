<?php
require_once __DIR__ . '/../models/AssetMaster.php';

class AssetMasterController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $sort = $_GET['sort'] ?? '資産コード';
        $order = $_GET['order'] ?? 'ASC';
        $model = new AssetMaster();
        $records = $model->getAll($sort, $order);
        include __DIR__ . '/../views/asset_master.php';
    }

    public function edit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $id = $_GET['id'] ?? '';
        if ($id === '') {
            header('Location: ?action=asset_master');
            exit;
        }
        $model = new AssetMaster();
        $record = $model->getById($id);
        if (!$record) {
            header('Location: ?action=asset_master');
            exit;
        }
        include __DIR__ . '/../views/edit_asset_master.php';
    }

    public function update() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $id = $_POST['連番'] ?? '';
        $kubun = $_POST['資産区分コード'] ?? '';
        $code = $_POST['資産コード'] ?? '';
        $name = $_POST['資産名'] ?? '';
        $short = $_POST['資産略名'] ?? '';
        $dom = $_POST['国内外区分'] ?? '';
        $cur = $_POST['通貨区分'] ?? '';
        $acc = $_POST['口座区分'] ?? '';
        $len = $_POST['長短区分'] ?? '';
        $trade = $_POST['売買区分'] ?? '';
        $model = new AssetMaster();
        $model->updateById($id, $kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade);
        header('Location: ?action=asset_master');
        exit;
    }

    public function add() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        include __DIR__ . '/../views/add_asset_master.php';
    }

    public function insert() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $kubun = $_POST['資産区分コード'] ?? '';
        $code = $_POST['資産コード'] ?? '';
        $name = $_POST['資産名'] ?? '';
        $short = $_POST['資産略名'] ?? '';
        $dom = $_POST['国内外区分'] ?? '';
        $cur = $_POST['通貨区分'] ?? '';
        $acc = $_POST['口座区分'] ?? '';
        $len = $_POST['長短区分'] ?? '';
        $trade = $_POST['売買区分'] ?? '';
        $model = new AssetMaster();
        $model->insert($kubun, $code, $name, $short, $dom, $cur, $acc, $len, $trade);
        header('Location: ?action=asset_master');
        exit;
    }
}
