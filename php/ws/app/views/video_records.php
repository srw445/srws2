<?php
// ソート状態取得
$sort = $_GET['sort'] ?? '年月日';
$order = $_GET['order'] ?? 'DESC';
// ソート用関数
function sort_link($label, $column, $currentSort, $currentOrder) {
    $nextOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentOrder === 'ASC' ? '▲' : '▼';
    }
    $url = "?action=video_records&sort=" . urlencode($column) . "&order=" . $nextOrder;
    return "<a href='$url'>$label $arrow</a>";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>映像記録</title>
    <style>
    .table-sm tr, .table-sm td, .table-sm th {
      padding-top: 0.1rem;
      padding-bottom: 0.1rem;
      min-height: 20px;
    }
    </style>
</head>
<body class="bg-body-secondary">
    <h2>映像記録</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=add_video_record'">追加</button>
    </div>
    <div style="max-height: 700px; overflow-y: auto; margin-top: 50px;">
    <table class="table table-hover table-sm" style="width: 100%;">
        <thead style="position: sticky; top: 0; background: white;">
            <tr>
                <th><?php echo sort_link('年月日', '年月日', $sort, $order); ?></th>
                <th><?php echo sort_link('映像区分', '映像区分', $sort, $order); ?></th>
                <th><?php echo sort_link('タイトル', 'タイトル', $sort, $order); ?></th>
                <th><?php echo sort_link('監督', '監督', $sort, $order); ?></th>
                <th><?php echo sort_link('主演', '主演', $sort, $order); ?></th>
                <th><?php echo sort_link('評価', '評価', $sort, $order); ?></th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['年月日'] ?? ''); ?></td>
                    <td><?php echo $record['映像区分コード名'] !== null && $record['映像区分コード名'] !== '' ? htmlspecialchars($record['映像区分コード名']) : ''; ?></td>
                    <td><?php echo htmlspecialchars($record['タイトル'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['監督'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['主演'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['評価コード名'] ?? ''); ?></td>
                    <td><a href="?action=edit_video_record&id=<?php echo $record['連番']; ?>">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
