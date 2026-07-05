<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>freee取引確認(集計)</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-secondary">
    <?php include __DIR__ . '/login_user.php'; ?>
    <h2>freee取引確認(集計)</h2>
    <?php $monthlySummaries = $monthlySummaries ?? []; ?>

    <div style="margin-bottom: 10px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-secondary" onclick="location.href='?action=freee_transaction'">freee取引確認</button>
    </div>

    <div class="mt-3" style="max-width: 1800px;">
        <table class="table table-hover table-sm table-bordered bg-white">
            <thead>
                <tr>
                    <th>発生月</th>
                    <th>売上高</th>
                    <th>地代家賃</th>
                    <th>旅費交通費</th>
                    <th>水道光熱費</th>
                    <th>通信費</th>
                    <th>消耗品費</th>
                    <th>交際費</th>
                    <th>その他</th>
                    <th>事業主貸</th>
                    <th>事業主借</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($monthlySummaries)): ?>
                    <?php foreach ($monthlySummaries as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['発生月'] ?? '') ?></td>
                            <td><?= number_format((float)($row['売上高'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['地代家賃'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['旅費交通費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['水道光熱費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['通信費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['消耗品費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['交際費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['その他'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['事業主貸'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['事業主借'] ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="11">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
