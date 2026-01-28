<?php
require_once __DIR__ . '/../models/KubunCode.php';

class KubunCodeController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $sort = $_GET['sort'] ?? '区分コード';
        $order = $_GET['order'] ?? 'ASC';
        $model = new KubunCode();
        $records = $model->getKubunCodes($sort, $order);
        include __DIR__ . '/../views/kubun_codes.php';
    }

    public function edit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ?action=kubun_codes');
            exit;
        }
        $model = new KubunCode();
        $record = $model->getKubunCodeById($id);
        if (!$record) {
            header('Location: ?action=kubun_codes');
            exit;
        }
        include __DIR__ . '/../views/edit_kubun_code.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=kubun_codes');
            exit;
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            header('Location: ?action=kubun_codes');
            exit;
        }
        $data = [
            '区分名' => $_POST['区分名'] ?? '',
            'コード名' => $_POST['コード名'] ?? '',
            'コード名2' => $_POST['コード名2'] ?? '',
            '区分説明' => $_POST['区分説明'] ?? '',
            '設定値1' => $_POST['設定値1'] ?? '',
            '設定値2' => $_POST['設定値2'] ?? '',
            '設定値3' => $_POST['設定値3'] ?? '',
        ];
        $model = new KubunCode();
        $result = $model->updateKubunCode($id, $data);
        if ($result === true) {
            header('Location: ?action=kubun_codes');
            exit;
        } else {
            echo "更新に失敗しました: $result";
            echo '<a href="?action=kubun_codes">戻る</a>';
        }
    }

    public function add() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        include __DIR__ . '/../views/add_kubun_code.php';
    }

    public function insert() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=kubun_codes');
            exit;
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $data = [
            '区分コード' => $_POST['区分コード'] ?? '',
            '区分名' => $_POST['区分名'] ?? '',
            'コード' => $_POST['コード'] ?? '',
            'コード名' => $_POST['コード名'] ?? '',
            'コード名2' => $_POST['コード名2'] ?? '',
            '区分説明' => $_POST['区分説明'] ?? '',
            '設定値1' => $_POST['設定値1'] ?? '',
            '設定値2' => $_POST['設定値2'] ?? '',
            '設定値3' => $_POST['設定値3'] ?? '',
        ];
        $model = new KubunCode();
        $result = $model->insertKubunCode($data);
        if ($result === true) {
            header('Location: ?action=kubun_codes');
            exit;
        } else {
            echo "追加に失敗しました: $result";
            echo '<a href="?action=kubun_codes">戻る</a>';
        }
    }
}
