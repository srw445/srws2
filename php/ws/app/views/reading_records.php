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
        .compact-summary {
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
            gap: 0.8rem;
        }
        .author-top10 {
            max-width: 420px;
            margin-bottom: 0.5rem;
            font-size: 0.88rem;
        }
        .author-top10 .table td,
        .author-top10 .table th {
            padding-top: 0.18rem;
            padding-bottom: 0.18rem;
        }
        </style>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>読書記録</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <h2>読書記録</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=add_reading_record'">追加</button>
    </div>
    <?php $records = $records ?? []; $totals = $totals ?? []; $authorCounts = $authorCounts ?? []; ?>
    <div class="compact-summary d-flex flex-wrap align-items-center">
        <div>読書合計: <strong><?= htmlspecialchars($totals['読書合計'] ?? 0) ?></strong></div>
        <div>ページ数合計: <strong><?= number_format((int)($totals['ページ数合計'] ?? 0)) ?></strong></div>
        <div>定価合計: <strong><?= number_format((int)($totals['定価合計'] ?? 0)) ?></strong></div>
    </div>
    <div class="author-top10">
        <h6 class="mb-1">作者別 読書数 TOP5</h6>
        <table class="table table-bordered table-sm mb-0">
            <thead>
                <tr>
                    <th>作者</th>
                    <th style="width: 120px;">読書数</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($authorCounts)): ?>
                    <?php foreach ($authorCounts as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['作者'] ?? '') ?></td>
                            <td><?= number_format((int)($row['読書数'] ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">データがありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="max-height: 700px; overflow-y: auto; margin-top: 10px;">
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