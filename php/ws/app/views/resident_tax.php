<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <title>住民税</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <h2>住民税</h2>
    <?php
        $residentTaxRows = $residentTaxRows ?? [];
        $chartLabels = [];
        $chartTotalIncome = [];
        $chartDeductionTotal = [];
        $chartAnnualTax = [];

        if (!empty($residentTaxRows)) {
            $chartSourceRows = array_reverse($residentTaxRows);
            foreach ($chartSourceRows as $row) {
                $year = (string)($row['西暦'] ?? '');
                $fiscalYear = (string)($row['年度'] ?? '');
                $chartLabels[] = $year !== '' ? ($year . '年(' . $fiscalYear . ')') : $fiscalYear;
                $chartTotalIncome[] = (float)($row['総所得金額等'] ?? 0);
                $chartDeductionTotal[] = (float)($row['所得控除合計'] ?? 0);
                $chartAnnualTax[] = (float)($row['年税額'] ?? 0);
            }
        }
    ?>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
    </div>
    <div class="mt-3" style="max-width: 1800px;">
        <table class="table table-hover table-sm table-bordered bg-white">
            <thead>
                <tr>
                    <th>西暦</th>
                    <th>年度</th>
                    <th>給与収入</th>
                    <th>所得金額調整控除</th>
                    <th>給与所得_所得金額調整控除後</th>
                    <th>営業等所得</th>
                    <th>総所得金額等</th>
                    <th>医療費</th>
                    <th>社会保険料</th>
                    <th>小規模企業共済</th>
                    <th>生命保険料</th>
                    <th>基礎</th>
                    <th>所得控除合計</th>
                    <th>課税総所得</th>
                    <th>年税額</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($residentTaxRows)): ?>
                    <?php foreach ($residentTaxRows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['西暦'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['年度'] ?? '') ?></td>
                            <td><?= number_format((float)($row['給与収入'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['所得金額調整控除'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['給与所得_所得金額調整控除後'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['営業等所得'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['総所得金額等'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['医療費'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['社会保険料'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['小規模企業共済'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['生命保険料'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['基礎'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['所得控除合計'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['課税総所得'] ?? 0)) ?></td>
                            <td><?= number_format((float)($row['年税額'] ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="15">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4" style="max-width: 1800px;">
        <h5>推移チャート</h5>
        <?php if (!empty($chartLabels)): ?>
            <div class="bg-white p-3 border rounded" style="height: 360px;">
                <canvas id="residentTaxTrendChart"></canvas>
            </div>
        <?php else: ?>
            <div class="text-muted">チャート表示用のデータがありません</div>
        <?php endif; ?>
    </div>

    <?php if (!empty($chartLabels)): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const residentTaxChartLabels = <?= json_encode($chartLabels, JSON_UNESCAPED_UNICODE) ?>;
            const residentTaxTotalIncome = <?= json_encode($chartTotalIncome) ?>;
            const residentTaxDeductionTotal = <?= json_encode($chartDeductionTotal) ?>;
            const residentTaxAnnualTax = <?= json_encode($chartAnnualTax) ?>;

            const residentTaxCtx = document.getElementById('residentTaxTrendChart');
            if (residentTaxCtx) {
                new Chart(residentTaxCtx, {
                    type: 'line',
                    data: {
                        labels: residentTaxChartLabels,
                        datasets: [
                            {
                                label: '総所得金額等',
                                data: residentTaxTotalIncome,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.15)',
                                tension: 0.2,
                                fill: false
                            },
                            {
                                label: '所得控除合計',
                                data: residentTaxDeductionTotal,
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25, 135, 84, 0.15)',
                                tension: 0.2,
                                fill: false
                            },
                            {
                                label: '年税額',
                                data: residentTaxAnnualTax,
                                borderColor: '#dc3545',
                                backgroundColor: 'rgba(220, 53, 69, 0.15)',
                                tension: 0.2,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: (value) => Number(value).toLocaleString()
                                }
                            }
                        }
                    }
                });
            }
        </script>
    <?php endif; ?>
</body>
</html>
