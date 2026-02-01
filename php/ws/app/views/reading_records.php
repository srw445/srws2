<!DOCTYPE html>
<html lang="ja">
<head>
        <meta charset="UTF-8">
        <style>
        .table-sm tr, .table-sm td, .table-sm th {
            padding-top: 0.1rem;
            padding-bottom: 0.1rem;
            min-height: 20px;
        }
        </style>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>読書記録</title>
</head>
<body class="bg-body-secondary">
    <h2>読書記録</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=add_reading_record'">追加</button>
    </div>
    <div style="max-height: 700px; overflow-y: auto; margin-top: 50px;">
    <table class="table table-hover table-sm" style="width: 100%;">
        <thead style="position: sticky; top: 0; background: white;">
            <tr>
                <?php
                $currentSort = isset($_GET['sort']) ? $_GET['sort'] : '読了日';
                $currentOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
                $headers = ['読了日' => '読了日', 'タイトル' => 'タイトル', '作者' => '作者', '出版社' => '出版社', '初版日' => '初版日', '評価' => '評価'];
                foreach ($headers as $key => $label) {
                    $order = ($currentSort === $key && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                    echo "<th><a href=\"?action=reading_records&sort=$key&order=$order\">$label</a></th>";
                }
                ?>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['読了日']); ?></td>
                    <td><?php echo htmlspecialchars($record['タイトル']); ?></td>
                    <td><?php echo htmlspecialchars($record['作者']); ?></td>
                    <td><?php echo htmlspecialchars($record['出版社']); ?></td>
                    <td><?php echo htmlspecialchars($record['初版日']); ?></td>
                    <td><?php echo $record['評価コード名'] !== null && $record['評価コード名'] !== '' ? htmlspecialchars($record['評価コード名']) : ''; ?></td>
                    <td><a href="?action=edit_reading_record&id=<?php echo $record['連番']; ?>">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>