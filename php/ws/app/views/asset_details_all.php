<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>資産明細（全履歴）</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-secondary">
    <?php include __DIR__ . '/login_user.php'; ?>
    <h2>資産明細（全履歴）</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_summary'">戻る</button>
    </div>
    <?php
    $assetCodeFilter = (string)($_GET['asset_code_filter'] ?? '');
    $assetCodes = array_unique(array_map('strval', array_column($assetDetails, '資産コード')));
    $assetShortNames = [];
    foreach ($assetDetails as $row) {
        $assetCode = (string)($row['資産コード'] ?? '');
        if (!array_key_exists($assetCode, $assetShortNames)) {
            $assetShortNames[$assetCode] = (string)($row['資産略名'] ?? '');
        }
    }
    sort($assetCodes, SORT_NATURAL);
    $filteredAssetDetails = $assetCodeFilter === ''
        ? $assetDetails
        : array_values(array_filter($assetDetails, static function ($row) use ($assetCodeFilter) {
            return (string)($row['資産コード'] ?? '') === $assetCodeFilter;
        }));
    ?>
    <form method="get" action="" style="margin-bottom: 10px;">
        <input type="hidden" name="action" value="asset_details_all">
        <label for="asset_code_filter">資産コード:</label>
        <select name="asset_code_filter" id="asset_code_filter" class="form-select" style="width: 200px; display: inline-block;">
            <option value="">すべて表示</option>
            <?php foreach ($assetCodes as $assetCode): ?>
                <option value="<?= htmlspecialchars($assetCode) ?>" <?= $assetCodeFilter === $assetCode ? 'selected' : '' ?>>
                    <?= htmlspecialchars($assetCode . ':' . ($assetShortNames[$assetCode] ?? '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-outline-secondary btn-sm">絞り込む</button>
    </form>
    <div style="max-width:1600px; max-height:700px; overflow:auto;">
        <table class="table table-hover table-sm" style="width:100%; margin-bottom:0;">
            <thead style="position:sticky; top:0; background:#fff; z-index:2;">
                <tr>
                    <th>履歴番号</th>
                    <th>年月日</th>
                    <th>資産区分コード</th>
                    <th>資産区分略名</th>
                    <th>資産コード</th>
                    <th>資産略名</th>
                    <th>金額</th>
                    <th>前回比</th>
                    <th>前回比割合</th>
                    <th>評価損益</th>
                    <th>評価損益割合</th>
                    <th>国内外区分</th>
                    <th>通貨区分</th>
                    <th>長短区分</th>
                    <th>口座区分</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($filteredAssetDetails)): ?>
                    <?php foreach ($filteredAssetDetails as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($row['履歴番号'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['年月日'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['資産区分コード'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['資産区分略名'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['資産コード'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['資産略名'] ?? '')) ?></td>
                            <td><?= number_format((float)($row['金額'] ?? 0)) ?></td>
                            <td class="<?= isset($row['前回比']) && is_numeric($row['前回比']) && (float)$row['前回比'] < 0 ? 'text-danger' : '' ?>">
                                <?= isset($row['前回比']) ? number_format((float)$row['前回比']) : '' ?>
                            </td>
                            <td class="<?= isset($row['前回比割合']) && preg_match('/^\s*-/u', (string)$row['前回比割合']) ? 'text-danger' : '' ?>">
                                <?= htmlspecialchars((string)($row['前回比割合'] ?? '')) ?>
                            </td>
                            <td class="<?= isset($row['評価損益']) && is_numeric($row['評価損益']) && (float)$row['評価損益'] < 0 ? 'text-danger' : '' ?>">
                                <?= number_format((float)($row['評価損益'] ?? 0)) ?>
                            </td>
                            <td class="<?= isset($row['評価損益割合']) && preg_match('/^\s*-/u', (string)$row['評価損益割合']) ? 'text-danger' : '' ?>">
                                <?= htmlspecialchars((string)($row['評価損益割合'] ?? '')) ?>
                            </td>
                            <td><?= htmlspecialchars((string)($row['国内外区分コード名'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['通貨区分コード名'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['長短区分コード名'] ?? '')) ?></td>
                            <td><?= htmlspecialchars((string)($row['口座区分コード名'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="15">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($assetCodeFilter !== '' && !empty($filteredAssetDetails)): ?>
        <div class="mt-4" style="max-width:1600px;">
            <h5>資産推移</h5>
            <canvas id="assetHistoryChart" width="1200" height="300"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const assetHistoryRows = <?= json_encode(array_map(static function ($row) {
            return [
                '履歴番号' => (int)($row['履歴番号'] ?? 0),
                '年月日' => (string)($row['年月日'] ?? ''),
                '金額' => (float)($row['金額'] ?? 0),
                '評価損益' => (float)($row['評価損益'] ?? 0)
            ];
        }, $filteredAssetDetails), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        assetHistoryRows.sort((a, b) => a['履歴番号'] - b['履歴番号']);
        new Chart(document.getElementById('assetHistoryChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: assetHistoryRows.map(row => row['年月日']),
                datasets: [
                    {
                        label: '基準額',
                        data: assetHistoryRows.map(row => row['金額'] - row['評価損益']),
                        yAxisID: 'amount',
                        borderColor: 'rgba(35, 145, 205, 1)',
                        backgroundColor: 'rgba(91, 190, 235, 0.3)',
                        fill: 'origin',
                        tension: 0.2
                    },
                    {
                        label: '金額',
                        data: assetHistoryRows.map(row => row['金額']),
                        yAxisID: 'amount',
                        borderColor: 'rgba(224, 119, 31, 1)',
                        backgroundColor: 'rgba(255, 164, 72, 0.35)',
                        fill: '-1',
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: '年月日' } },
                    amount: {
                        type: 'linear',
                        position: 'left',
                        title: { display: true, text: '（万円）' },
                        beginAtZero: true,
                        ticks: {
                            callback: value => Number(value / 10000).toLocaleString()
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: context => `${context.dataset.label}: ${(context.parsed.y / 10000).toFixed(1)}万円`
                        }
                    }
                }
            }
        });
        </script>
    <?php endif; ?>
</body>
</html>