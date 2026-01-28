<?php
require_once __DIR__ . '/../models/VideoRecord.php';

class VideoController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $sort = $_GET['sort'] ?? '年月日';
        $order = $_GET['order'] ?? 'DESC';
        $model = new VideoRecord();
        $records = $model->getVideoRecords($userId, $sort, $order);
        include __DIR__ . '/../views/video_records.php';
    }

    public function add() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        include __DIR__ . '/../views/add_video_record.php';
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
            header('Location: ?action=video_records');
            exit;
        }
        $model = new VideoRecord();
        $userId = $_SESSION['user_id'];
        $record = $model->getVideoRecordById($id, $userId);
        if (!$record) {
            header('Location: ?action=video_records');
            exit;
        }
        include __DIR__ . '/../views/edit_video_record.php';
    }

    public function insert() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=video_records');
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
            '年月日' => $_POST['年月日'] ?? '',
            'タイトル' => $_POST['タイトル'] ?? '',
            '監督' => $_POST['監督'] ?? '',
            '主演' => $_POST['主演'] ?? '',
            '映像区分' => $_POST['映像区分'] ?? '',
            '映画館' => $_POST['映画館'] ?? '',
            '評価' => $_POST['評価'] ?? '',
            '備考' => $_POST['備考'] ?? '',
            'ユーザID' => $_SESSION['user_id'] ?? '',
        ];
        $model = new VideoRecord();
        $result = $model->insertVideoRecord($data);
        if (is_numeric($result)) {
            header('Location: ?action=video_records');
            exit;
        } else {
            echo "追加に失敗しました: $result";
            echo '<a href="?action=video_records">戻る</a>';
        }
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=video_records');
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
            header('Location: ?action=video_records');
            exit;
        }
        $data = [
            '年月日' => $_POST['年月日'] ?? '',
            'タイトル' => $_POST['タイトル'] ?? '',
            '監督' => $_POST['監督'] ?? '',
            '主演' => $_POST['主演'] ?? '',
            '映像区分' => $_POST['映像区分'] ?? '',
            '映画館' => $_POST['映画館'] ?? '',
            '評価' => $_POST['評価'] ?? '',
            '備考' => $_POST['備考'] ?? '',
        ];
        $model = new VideoRecord();
        $result = $model->updateVideoRecord($id, $data, $_SESSION['user_id']);
        if ($result === true) {
            header('Location: ?action=video_records');
            exit;
        } else {
            echo "更新に失敗しました: $result";
            echo '<a href="?action=video_records">戻る</a>';
        }
    }

    // updateは後で実装
}
?>