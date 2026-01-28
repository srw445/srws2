<?php
require_once '../app/models/ReadingRecord.php';

class ReadingController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // ソートパラメータを取得
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '読了日';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        // 安全のため、許可されたカラムのみ
        $allowedSorts = ['読了日', 'タイトル', '作者', '出版社', '初版日', '評価'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = '読了日';
        }
        if ($order !== 'ASC' && $order !== 'DESC') {
            $order = 'ASC';
        }

        // モデルから読書記録を取得
        $readingRecordModel = new ReadingRecord();
        $userId = $_SESSION['user_id'] ?? '';
        $records = $readingRecordModel->getReadingRecords($userId, $sort, $order);
        // ビューにデータを渡す
        include '../app/views/reading_records.php';
    }

    public function add() {
        include '../app/views/add_reading_record.php';
    }

    public function insert() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=reading_records');
            exit;
        }
        $data = [
            'タイトル' => $_POST['タイトル'] ?? '',
            '作者' => $_POST['作者'] ?? '',
            '出版社' => $_POST['出版社'] ?? '',
            '形式' => $_POST['形式'] ?? '',
            'ジャンル' => $_POST['ジャンル'] ?? '',
            'ページ数' => !empty($_POST['ページ数']) ? (int)$_POST['ページ数'] : null,
            '定価' => !empty($_POST['定価']) ? (int)$_POST['定価'] : null,
            '受賞' => $_POST['受賞'] ?? '',
            '初版日' => !empty($_POST['初版日']) ? $_POST['初版日'] : null,
            '読了日' => !empty($_POST['読了日']) ? $_POST['読了日'] : null,
            '表紙ファイル名' => $_POST['表紙ファイル名'] ?? '',
            '備考' => $_POST['備考'] ?? '',
            '評価' => $_POST['評価'] ?? '',
            'ユーザID' => $_SESSION['user_id'] ?? '',
        ];
        $readingRecordModel = new ReadingRecord();
        $result = $readingRecordModel->insertReadingRecord($data);
        if (is_numeric($result)) {
            header('Location: ?action=reading_records');
            exit;
        } else {
            echo "追加に失敗しました: $result";
            echo '<a href="?action=reading_records">戻る</a>';
        }
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ?action=reading_records');
            exit;
        }
        $readingRecordModel = new ReadingRecord();
        $userId = $_SESSION['user_id'] ?? '';
        $record = $readingRecordModel->getReadingRecordById($id, $userId);
        if (!$record) {
            header('Location: ?action=reading_records');
            exit;
        }
        include '../app/views/edit_reading_record.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header('Location: ?action=reading_records');
            exit;
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            header('Location: ?action=reading_records');
            exit;
        }
        $data = [
            'タイトル' => $_POST['タイトル'] ?? '',
            '作者' => $_POST['作者'] ?? '',
            '出版社' => $_POST['出版社'] ?? '',
            '形式' => $_POST['形式'] ?? '',
            'ジャンル' => $_POST['ジャンル'] ?? '',
            'ページ数' => !empty($_POST['ページ数']) ? (int)$_POST['ページ数'] : null,
            '定価' => !empty($_POST['定価']) ? (int)$_POST['定価'] : null,
            '受賞' => $_POST['受賞'] ?? '',
            '初版日' => !empty($_POST['初版日']) ? $_POST['初版日'] : null,
            '読了日' => !empty($_POST['読了日']) ? $_POST['読了日'] : null,
            '表紙ファイル名' => $_POST['表紙ファイル名'] ?? '',
            '備考' => $_POST['備考'] ?? '',
            '評価' => $_POST['評価'] ?? '',
        ];
        $readingRecordModel = new ReadingRecord();
        $result = $readingRecordModel->updateReadingRecord($id, $data);
        if ($result === true) {
            header('Location: ?action=reading_records');
            exit;
        } else {
            echo "更新に失敗しました: $result";
            echo '<a href="?action=reading_records">戻る</a>';
        }
    }
}
?>