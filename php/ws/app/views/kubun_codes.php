<?php
$sort = $_GET['sort'] ?? '区分コード';
$order = $_GET['order'] ?? 'ASC';
function sort_link_kubun($label, $column, $currentSort, $currentOrder) {
    $nextOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    $arrow = '';
    if ($currentSort === $column) {
        $arrow = $currentOrder === 'ASC' ? '▲' : '▼';
    }
    $url = "?action=kubun_codes&sort=" . urlencode($column) . "&order=" . $nextOrder;
    return "<a href='$url'>$label $arrow</a>";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>区分コードマスタ</title>
</head>
<body class="bg-body-tertiary">
    <h2>区分コードマスタ</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=add_kubun_code'">追加</button>
    </div>
    <form method="get" action="">
        <input type="hidden" name="action" value="kubun_codes">
        <label for="kubun_code_filter">区分コード:</label>
        <select name="kubun_code_filter" id="kubun_code_filter" class="form-select" style="width: 200px; display: inline-block;">
            <option value="">すべて表示</option>
            <?php
            $kubunCodes = array_unique(array_column($records, '区分コード'));
            foreach ($kubunCodes as $code) {
                $selected = (isset($_GET['kubun_code_filter']) && $_GET['kubun_code_filter'] === $code) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($code) . '" ' . $selected . '>' . htmlspecialchars($code) . '</option>';
            }
            ?>
        </select>
        <button type="submit" class="btn btn-outline-secondary btn-sm">絞り込む</button>
    </form>
    <div style="max-height: 700px; overflow-y: auto; margin-top: 30px;">
    <table class="table table-hover table-sm" style="width: 100%;">
        <thead style="position: sticky; top: 0; background: white;">
            <tr>
                <th><?php echo sort_link_kubun('区分コード', '区分コード', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('区分名', '区分名', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('コード', 'コード', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('コード名', 'コード名', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('コード名2', 'コード名2', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('区分説明', '区分説明', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('設定値1', '設定値1', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('設定値2', '設定値2', $sort, $order); ?></th>
                <th><?php echo sort_link_kubun('設定値3', '設定値3', $sort, $order); ?></th>
                <th>編集</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $filter = $_GET['kubun_code_filter'] ?? '';
            foreach ($records as $record) {
                if ($filter && $record['区分コード'] !== $filter) continue;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['区分コード'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['コード'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['コード名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['コード名2'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['区分説明'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['設定値1'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['設定値2'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['設定値3'] ?? ''); ?></td>
                    <td><a href="?action=edit_kubun_code&id=<?php echo urlencode($record['連番']); ?>">編集</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</body>
</html>
