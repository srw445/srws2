<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>資産管理</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <?php if (!empty($_SESSION['asset_import_error'])): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_SESSION['asset_import_error']); unset($_SESSION['asset_import_error']); ?></div>
    <?php endif; ?>
    <h2>資産管理</h2>
    <div style="margin-bottom: 10px; display: flex; gap: 8px; align-items: center;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <form id="importForm" action="?action=import_asset_file" method="post" enctype="multipart/form-data" style="display:inline;">
            <label for="importFile" class="btn btn-outline-success mb-0">取込
                <input type="file" id="importFile" name="import_file" style="display:none;" onchange="document.getElementById('importForm').submit();">
            </label>
        </form>
    </div>
    <div class="mt-4" style="max-width:1600px;">
        <h5>資産集計</h5>
    </div>
    <div style="max-height: 250px; overflow-y: auto;">
    <table class="table table-hover table-sm" style="margin-bottom:0;">
        <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
            <tr>
                <?php
                $excludeCols = ['債券', '債券割合', 'その他', 'その他割合'];
                $displayCols = [];
                if (!empty($assets)) {
                    foreach (array_keys($assets[0]) as $col) {
                        if (in_array($col, $excludeCols, true)) continue;
                        $displayCols[] = $col;
                        echo '<th>' . htmlspecialchars($col) . '</th>';
                    }
                    // 削除ボタン用の列
                    echo '<th></th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($assets)): ?>
                <?php foreach ($assets as $row): ?>
                    <tr>
                        <?php foreach ($displayCols as $col): ?>
                            <td>
                                <?php
                                if ($col === '履歴番号') {
                                    echo '<form method="get" style="display:inline; margin:0;">
                                        <input type="hidden" name="action" value="asset_summary">
                                        <input type="hidden" name="history_no" value="' . htmlspecialchars($row['履歴番号']) . '">
                                        <button type="submit" class="btn btn-link p-0">' . htmlspecialchars($row['履歴番号']) . '</button>
                                    </form>';
                                } else {
                                    $val = $row[$col];
                                    $negativeColorTargets = ['評価損益_合計', '前回比', '前回比割合'];
                                    $isNegativeValue = in_array($col, $negativeColorTargets, true)
                                        && (
                                            (is_numeric($val) && (float)$val < 0)
                                            || (!is_numeric($val) && preg_match('/^\s*-/u', (string)$val))
                                        );

                                    if ((
                                        $col === '金額_合計' ||
                                        $col === '評価損益_合計' ||
                                        preg_match('/(金額|損益|現金|株式|仮想通貨|年金|コモディティ|比)$/u', $col)
                                    ) && is_numeric($val)) {
                                        $displayVal = number_format($val);
                                    } elseif (preg_match('/割合$/u', $col)) {
                                        $displayVal = htmlspecialchars($val);
                                    } else {
                                        $displayVal = htmlspecialchars($val);
                                    }

                                    if ($isNegativeValue) {
                                        echo '<span class="text-danger">' . $displayVal . '</span>';
                                    } else {
                                        echo $displayVal;
                                    }
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td>
                            <form method="post" action="?action=delete_asset_manager" style="display:inline; margin:0;" onsubmit="return confirm('本当に削除しますか？');">
                                <input type="hidden" name="history_no" value="<?= htmlspecialchars($row['履歴番号']) ?>">
                                <button type="submit" class="btn btn-danger btn-sm">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td>データがありません</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
    <div class="mt-4" style="max-width:1600px;">
        <h5>資産明細</h5>
        <div style="max-height:600px; overflow-y:auto;">
            <table class="table table-hover table-sm" style="margin-bottom:0;">
                <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                    <tr>
                    <th>年月日</th>
                    <th>資産区分コード</th>
                    <th>資産区分略名</th>
                    <th>資産コード</th>
                    <th>資産略名</th>
                    <th>金額</th>
                    <th>前回比</th>
                    <th>評価損益</th>
                    <th>評価損益割合</th>
                    <th>国内外区分</th>
                    <th>通貨区分</th>
                    <th>長短区分</th>
                    <th>口座区分</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assetDetails)): ?>
                    <?php foreach ($assetDetails as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['年月日']) ?></td>
                            <td><?= htmlspecialchars($row['資産区分コード']) ?></td>
                            <td><?= htmlspecialchars($row['資産区分略名']) ?></td>
                            <td><?= htmlspecialchars($row['資産コード']) ?></td>
                            <td><?= htmlspecialchars($row['資産略名']) ?></td>
                            <td><?= number_format($row['金額']) ?></td>
                            <td class="<?= (isset($row['前回比']) && is_numeric($row['前回比']) && (float)$row['前回比'] < 0) ? 'text-danger' : '' ?>"><?= isset($row['前回比']) ? number_format($row['前回比']) : '' ?></td>
                            <td class="<?= (is_numeric($row['評価損益']) && (float)$row['評価損益'] < 0) ? 'text-danger' : '' ?>"><?= number_format($row['評価損益']) ?></td>
                            <td class="<?= (isset($row['評価損益割合']) && preg_match('/^\s*-/u', (string)$row['評価損益割合'])) ? 'text-danger' : '' ?>"><?= isset($row['評価損益割合']) ? htmlspecialchars($row['評価損益割合']) : '' ?></td>
                            <td><?= htmlspecialchars($row['国内外区分コード名']) ?></td>
                            <td><?= htmlspecialchars($row['通貨区分コード名']) ?></td>
                            <td><?= htmlspecialchars($row['長短区分コード名']) ?></td>
                            <td><?= htmlspecialchars($row['口座区分コード名'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>
    <div class="mt-4 d-flex" style="gap: 32px;">
        <div style="max-width:400px;">
            <h5>資産区分割合</h5>
            <div style="margin-bottom: 4px; font-size: 1rem; color: #555;">
                <?php if (!empty($assetRatios) && isset($assetRatios[0]['履歴番号'])): ?>
                    履歴番号: <?= htmlspecialchars($assetRatios[0]['履歴番号']) ?>
                <?php endif; ?>
            </div>
            <canvas id="ratioChart" width="350" height="200"></canvas>
        </div>
        <div style="max-width:400px;">
            <h5>国内外区分割合</h5>
            <div style="margin-bottom: 4px; font-size: 1rem; color: #555;">
                <?php if (!empty($assetRatioCountries) && isset($assetRatioCountries[0]['履歴番号'])): ?>
                    履歴番号: <?= htmlspecialchars($assetRatioCountries[0]['履歴番号']) ?>
                <?php endif; ?>
            </div>
            <canvas id="countryRatioChart" width="350" height="200"></canvas>
        </div>
        <div style="max-width:400px;">
            <h5>通貨区分割合</h5>
            <div style="margin-bottom: 4px; font-size: 1rem; color: #555;">
                <?php if (!empty($assetRatioCashs) && isset($assetRatioCashs[0]['履歴番号'])): ?>
                    履歴番号: <?= htmlspecialchars($assetRatioCashs[0]['履歴番号']) ?>
                <?php endif; ?>
            </div>
            <canvas id="cashRatioChart" width="350" height="200"></canvas>
        </div>
        <div style="max-width:400px;">
            <h5>長短区分割合</h5>
            <div style="margin-bottom: 4px; font-size: 1rem; color: #555;">
                <?php if (!empty($assetRatioScales) && isset($assetRatioScales[0]['履歴番号'])): ?>
                    履歴番号: <?= htmlspecialchars($assetRatioScales[0]['履歴番号']) ?>
                <?php endif; ?>
            </div>
            <canvas id="scaleRatioChart" width="350" height="200"></canvas>
        </div>
        <div style="max-width:400px;">
            <h5>口座区分割合</h5>
            <div style="margin-bottom: 4px; font-size: 1rem; color: #555;">
                <?php if (!empty($assetRatioAccounts) && isset($assetRatioAccounts[0]['履歴番号'])): ?>
                    履歴番号: <?= htmlspecialchars($assetRatioAccounts[0]['履歴番号']) ?>
                <?php endif; ?>
            </div>
            <canvas id="accountRatioChart" width="350" height="200"></canvas>
        </div>
    </div>
    <div class="mt-4" style="max-width:1600px;">
        <h5>資産推移</h5>
        <div class="d-flex flex-column" style="gap: 16px;">
            <div>
                <h6>金額合計</h6>
                <canvas id="profitChart" width="1200" height="260"></canvas>
            </div>
            <div>
                <h6>評価損益合計</h6>
                <canvas id="profitLossChart" width="1200" height="260"></canvas>
            </div>
            <div>
                <h6>前回比</h6>
                <canvas id="diffChart" width="1200" height="260"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-4" style="max-width:1600px;">
        <h5>オルカン・S&P500・FANG+推移</h5>
        <div class="d-flex flex-column" style="gap: 16px;">
            <div>
                <h6>金額</h6>
                <canvas id="investmentAmountChart" width="1200" height="260"></canvas>
            </div>
            <div>
                <h6>評価損益(金額)</h6>
                <canvas id="investmentProfitChart" width="1200" height="260"></canvas>
            </div>
            <div>
                <h6>評価損益(%)</h6>
                <canvas id="investmentProfitRatioChart" width="1200" height="260"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // 履歴番号で昇順ソートし、表示は年月日を使う
    let chartRaw = <?php echo json_encode(array_map(function($row){
        return [
            '履歴番号' => $row['履歴番号'],
            '年月日' => $row['年月日'] ?? '',
            '金額_合計' => (float)($row['金額_合計'] ?? 0),
            '評価損益_合計' => (float)($row['評価損益_合計'] ?? 0),
            '前回比' => (float)($row['前回比'] ?? 0)
        ];
    }, $assets ?? [])); ?>;
    chartRaw.sort((a, b) => a['履歴番号'] - b['履歴番号']);
    const chartLabels = chartRaw.map(row => row['年月日'] || row['履歴番号']);
    const chartData = chartRaw.map(row => row['金額_合計']);
    const chartProfitData = chartRaw.map(row => row['評価損益_合計']);
    const chartDiffData = chartRaw.map(row => Number(row['履歴番号']) === 1 ? 0 : Number(row['前回比'] ?? 0));

    const investmentTrendRaw = <?php echo json_encode(array_map(function($row){
        return [
            '履歴番号' => $row['履歴番号'],
            '年月日' => $row['年月日'],
            'ALLC_金額' => (float)($row['ALLC_金額'] ?? 0),
            'ALLC_損益金額' => (float)($row['ALLC_損益金額'] ?? 0),
            'ALLC_損益割合' => (float)($row['ALLC_損益割合'] ?? 0),
            'SP500_金額' => (float)($row['SP500_金額'] ?? 0),
            'SP500_損益金額' => (float)($row['SP500_損益金額'] ?? 0),
            'SP500_損益割合' => (float)($row['SP500_損益割合'] ?? 0),
            'FANG_金額' => (float)($row['FANG_金額'] ?? 0),
            'FANG_損益金額' => (float)($row['FANG_損益金額'] ?? 0),
            'FANG_損益割合' => (float)($row['FANG_損益割合'] ?? 0),
        ];
    }, $assetTrendRows ?? [])); ?>;
    investmentTrendRaw.sort((a, b) => Number(a['履歴番号']) - Number(b['履歴番号']));
    const investmentLabels = investmentTrendRaw.map(row => row['年月日']);
    const investmentAllcAmount = investmentTrendRaw.map(row => row['ALLC_金額']);
    const investmentSp500Amount = investmentTrendRaw.map(row => row['SP500_金額']);
    const investmentFangAmount = investmentTrendRaw.map(row => row['FANG_金額']);
    const investmentAllcProfit = investmentTrendRaw.map(row => row['ALLC_損益金額']);
    const investmentSp500Profit = investmentTrendRaw.map(row => row['SP500_損益金額']);
    const investmentFangProfit = investmentTrendRaw.map(row => row['FANG_損益金額']);
    const investmentAllcProfitRatio = investmentTrendRaw.map(row => Number(row['ALLC_損益割合']) * 100);
    const investmentSp500ProfitRatio = investmentTrendRaw.map(row => Number(row['SP500_損益割合']) * 100);
    const investmentFangProfitRatio = investmentTrendRaw.map(row => Number(row['FANG_損益割合']) * 100);

    const investmentColors = {
        allc: 'rgba(54, 162, 235, 1)',
        sp500: 'rgba(255, 159, 64, 1)',
        fang: 'rgba(75, 192, 192, 1)'
    };

    if (chartLabels.length > 0) {
        const amountCtx = document.getElementById('profitChart').getContext('2d');
        new Chart(amountCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: '金額合計',
                    data: chartData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: '年月日' }, reverse: false },
                    y: { title: { display: true, text: '金額合計' } }
                }
            }
        });

        const profitCtx = document.getElementById('profitLossChart').getContext('2d');
        new Chart(profitCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: '評価損益合計',
                    data: chartProfitData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: '年月日' }, reverse: false },
                    y: { title: { display: true, text: '評価損益合計' } }
                }
            }
        });

        const diffCtx = document.getElementById('diffChart').getContext('2d');
        new Chart(diffCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: '前回比',
                    data: chartDiffData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { title: { display: true, text: '年月日' }, reverse: false },
                    y: { title: { display: true, text: '前回比' } }
                }
            }
        });
    }

    if (investmentLabels.length > 0) {
        const investmentAmountCtx = document.getElementById('investmentAmountChart').getContext('2d');
        new Chart(investmentAmountCtx, {
            type: 'line',
            data: {
                labels: investmentLabels,
                datasets: [
                    {
                        label: '全世界株式（つみたて）',
                        data: investmentAllcAmount,
                        borderColor: investmentColors.allc,
                        backgroundColor: 'rgba(54, 162, 235, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'S&P500（つみたて）',
                        data: investmentSp500Amount,
                        borderColor: investmentColors.sp500,
                        backgroundColor: 'rgba(255, 159, 64, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'FANG+（つみたて）',
                        data: investmentFangAmount,
                        borderColor: investmentColors.fang,
                        backgroundColor: 'rgba(75, 192, 192, 0.15)',
                        fill: false,
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { title: { display: true, text: '年月日' } },
                    y: { title: { display: true, text: '金額' }, ticks: { callback: (value) => Number(value).toLocaleString() } }
                }
            }
        });

        const investmentProfitCtx = document.getElementById('investmentProfitChart').getContext('2d');
        new Chart(investmentProfitCtx, {
            type: 'line',
            data: {
                labels: investmentLabels,
                datasets: [
                    {
                        label: '全世界株式（つみたて）',
                        data: investmentAllcProfit,
                        borderColor: investmentColors.allc,
                        backgroundColor: 'rgba(54, 162, 235, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'S&P500（つみたて）',
                        data: investmentSp500Profit,
                        borderColor: investmentColors.sp500,
                        backgroundColor: 'rgba(255, 159, 64, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'FANG+（つみたて）',
                        data: investmentFangProfit,
                        borderColor: investmentColors.fang,
                        backgroundColor: 'rgba(75, 192, 192, 0.15)',
                        fill: false,
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { title: { display: true, text: '年月日' } },
                    y: { title: { display: true, text: '評価損益' }, ticks: { callback: (value) => Number(value).toLocaleString() } }
                }
            }
        });

        const investmentProfitRatioCtx = document.getElementById('investmentProfitRatioChart').getContext('2d');
        new Chart(investmentProfitRatioCtx, {
            type: 'line',
            data: {
                labels: investmentLabels,
                datasets: [
                    {
                        label: '全世界株式（つみたて）',
                        data: investmentAllcProfitRatio,
                        borderColor: investmentColors.allc,
                        backgroundColor: 'rgba(54, 162, 235, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'S&P500（つみたて）',
                        data: investmentSp500ProfitRatio,
                        borderColor: investmentColors.sp500,
                        backgroundColor: 'rgba(255, 159, 64, 0.15)',
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'FANG+（つみたて）',
                        data: investmentFangProfitRatio,
                        borderColor: investmentColors.fang,
                        backgroundColor: 'rgba(75, 192, 192, 0.15)',
                        fill: false,
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { title: { display: true, text: '年月日' } },
                    y: {
                        title: { display: true, text: '損益割合(%)' },
                        ticks: { callback: (value) => `${Number(value).toFixed(1)}%` }
                    }
                }
            }
        });
    }

    // 資産区分割合 円グラフ
    let ratioRaw = <?php echo json_encode($assetRatios ?? []); ?>;
    const ratioLabels = ratioRaw.map(row => row['資産区分略名']);
    const ratioData = ratioRaw.map(row => Number(row['金額']));
    const ratioColors = [
        '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'
    ];
    if (ratioLabels.length > 0) {
        const ctx2 = document.getElementById('ratioChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ratioLabels,
                datasets: [{
                    data: ratioData,
                    backgroundColor: ratioColors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.chart._metasets[0].total || context.dataset.data.reduce((a,b)=>a+b,0);
                                const percent = total ? (value / total * 100).toFixed(1) : 0;
                                return `${label}: ${value.toLocaleString()}円 (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 国内外区分割合 円グラフ
    let countryRaw = <?php echo json_encode($assetRatioCountries ?? []); ?>;
    const countryLabels = countryRaw.map(row => row['国内外区分コード名']);
    const countryData = countryRaw.map(row => Number(row['金額']));
    const countryColors = ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2'];
    if (countryLabels.length > 0) {
        const ctx3 = document.getElementById('countryRatioChart').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: countryLabels,
                datasets: [{
                    data: countryData,
                    backgroundColor: countryColors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.chart._metasets[0].total || context.dataset.data.reduce((a,b)=>a+b,0);
                                const percent = total ? (value / total * 100).toFixed(1) : 0;
                                return `${label}: ${value.toLocaleString()}円 (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 通貨区分割合 円グラフ
    let cashRaw = <?php echo json_encode($assetRatioCashs ?? []); ?>;
    const cashLabels = cashRaw.map(row => row['通貨区分コード名'] || row['通貨区分名'] || row['通貨区分'] || '');
    const cashData = cashRaw.map(row => Number(row['金額']));
    const cashColors = ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'];
    if (cashLabels.length > 0) {
        const ctx4 = document.getElementById('cashRatioChart').getContext('2d');
        new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: cashLabels,
                datasets: [{
                    data: cashData,
                    backgroundColor: cashColors,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.chart._metasets[0].total || context.dataset.data.reduce((a,b)=>a+b,0);
                                const percent = total ? (value / total * 100).toFixed(1) : 0;
                                return `${label}: ${value.toLocaleString()}円 (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

        // 長短区分割合 円グラフ
        let scaleRaw = <?php echo json_encode($assetRatioScales ?? []); ?>;
        const scaleLabels = scaleRaw.map(row => row['長短区分コード名']);
        const scaleData = scaleRaw.map(row => Number(row['金額']));
        const scaleColors = ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'];
        if (scaleLabels.length > 0) {
            const ctx5 = document.getElementById('scaleRatioChart').getContext('2d');
            new Chart(ctx5, {
                type: 'pie',
                data: {
                    labels: scaleLabels,
                    datasets: [{
                        data: scaleData,
                        backgroundColor: scaleColors,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.chart._metasets[0].total || context.dataset.data.reduce((a,b)=>a+b,0);
                                    const percent = total ? (value / total * 100).toFixed(1) : 0;
                                    return `${label}: ${value.toLocaleString()}円 (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 口座区分割合 円グラフ
        let accountRaw = <?php echo json_encode($assetRatioAccounts ?? []); ?>;
        const accountLabels = accountRaw.map(row => row['口座区分コード名'] || row['口座区分名'] || row['口座区分'] || '');
        const accountData = accountRaw.map(row => Number(row['金額']));
        const accountColors = ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'];
        if (accountLabels.length > 0) {
            const ctx6 = document.getElementById('accountRatioChart').getContext('2d');
            new Chart(ctx6, {
                type: 'pie',
                data: {
                    labels: accountLabels,
                    datasets: [{
                        data: accountData,
                        backgroundColor: accountColors,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.chart._metasets[0].total || context.dataset.data.reduce((a,b)=>a+b,0);
                                    const percent = total ? (value / total * 100).toFixed(1) : 0;
                                    return `${label}: ${value.toLocaleString()}円 (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
