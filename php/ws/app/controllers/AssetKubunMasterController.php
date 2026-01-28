<?php
require_once __DIR__ . '/../models/AssetKubunMaster.php';

class AssetKubunMasterController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $sort = $_GET['sort'] ?? '資産区分コード';
        $order = $_GET['order'] ?? 'ASC';
        $model = new AssetKubunMaster();
        $records = $model->getAll($sort, $order);
        include __DIR__ . '/../views/asset_kubun_master.php';
    }

    public function edit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $code = $_GET['code'] ?? '';
        if ($code === '') {
            header('Location: ?action=asset_kubun_master');
            exit;
        }
        $model = new AssetKubunMaster();
        $record = $model->getByCode($code);
        if (!$record) {
            header('Location: ?action=asset_kubun_master');
            exit;
        }
        include __DIR__ . '/../views/edit_asset_kubun_master.php';
    }
    public function update() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $code = $_POST['資産区分コード'] ?? '';
        $name = $_POST['資産区分名'] ?? '';
        $short = $_POST['資産区分略名'] ?? '';
        $val1 = $_POST['設定値1'] ?? '';
        $val2 = $_POST['設定値2'] ?? '';
        $val3 = $_POST['設定値3'] ?? '';
        $model = new AssetKubunMaster();
        $model->updateByCode($code, $name, $short, $val1, $val2, $val3);
        header('Location: ?action=asset_kubun_master');
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
        include __DIR__ . '/../views/add_asset_kubun_master.php';
    }

    public function insert() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $code = $_POST['資産区分コード'] ?? '';
        $name = $_POST['資産区分名'] ?? '';
        $short = $_POST['資産区分略名'] ?? '';
        $val1 = $_POST['設定値1'] ?? '';
        $val2 = $_POST['設定値2'] ?? '';
        $val3 = $_POST['設定値3'] ?? '';
        $model = new AssetKubunMaster();
        $model->insert($code, $name, $short, $val1, $val2, $val3);
        header('Location: ?action=asset_kubun_master');
        exit;
    }
}
