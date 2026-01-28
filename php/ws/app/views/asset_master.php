<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>資産マスタ</title>
</head>
<body class="bg-body-tertiary">
    <h2>資産マスタ</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=add_asset_master'">追加</button>
    </div>
    <div style="max-height: 700px; overflow-y: auto; margin-top: 50px;">
    <table class="table table-hover table-sm" style="width: 100%;">
        <thead style="position: sticky; top: 0; background: white;">
            <?php
            $currentSort = $_GET['sort'] ?? '資産コード';
            $currentOrder = $_GET['order'] ?? 'ASC';
            $headers = [
                '資産区分名' => '資産区分名',
                '資産コード' => '資産コード',
                '資産名' => '資産名',
                '資産略名' => '資産略名',
                '国内外区分名' => '国内外区分',
                '通貨区分名' => '通貨区分',
                '口座区分名' => '口座区分',
                '長短区分名' => '長短区分',
                '売買区分名' => '売買区分',
            ];
            echo '<tr>';
            foreach ($headers as $key => $label) {
                $order = ($currentSort === $key && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                echo '<th><a href="?action=asset_master&sort=' . urlencode($key) . '&order=' . $order . '">' . $label . '</a></th>';
            }
            echo '<th>編集</th>';
            echo '</tr>';
            ?>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['資産区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['資産コード'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['資産名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['資産略名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['国内外区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['通貨区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['口座区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['長短区分名'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($record['売買区分名'] ?? ''); ?></td>
                    <td><a href="?action=edit_asset_master&id=<?php echo urlencode($record['連番']); ?>">編集</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
