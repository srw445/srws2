<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>資産集計（全履歴）</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-secondary">
    <?php include __DIR__ . '/login_user.php'; ?>
    <?php
    $assets = $assets ?? [];
    $selectedHistoryNo = (string)($selectedHistoryNo ?? '');
    $assetCategorySummary = $assetCategorySummary ?? [];
    ?>
    <h2>資産集計（全履歴）</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_summary'">戻る</button>
    </div>
    <div style="max-width:1600px; max-height:700px; overflow:auto;">
        <table class="table table-hover table-sm" style="width:100%; margin-bottom:0;">
            <thead style="position:sticky; top:0; background:#fff; z-index:2;">
                <tr>
                    <?php
                    $excludeCols = [
                        '前回比割合',
                        '現金割合',
                        '個別株割合',
                        '投信割合',
                        '仮想通貨割合',
                        '年金割合',
                        'コモディティ割合',
                        '債券',
                        '債券割合',
                        'その他',
                        'その他割合',
                    ];
                    $displayCols = [];
                    if (!empty($assets)) {
                        foreach (array_keys($assets[0]) as $col) {
                            if (in_array($col, $excludeCols, true)) continue;
                            $displayCols[] = $col;
                            echo '<th class="text-center">' . htmlspecialchars($col) . '</th>';
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assets)): ?>
                    <?php foreach ($assets as $row): ?>
                        <tr>
                            <?php foreach ($displayCols as $col): ?>
                                <?php
                                $val = $row[$col];
                                $negativeColorTargets = ['評価損益_合計', '前回比', '前回比割合'];
                                $isNegativeValue = in_array($col, $negativeColorTargets, true)
                                    && (
                                        (is_numeric($val) && (float)$val < 0)
                                        || (!is_numeric($val) && preg_match('/^\s*-/u', (string)$val))
                                    );

                                if ($col === '前回比') {
                                    $displayVal = number_format((float)$val)
                                        . ' (' . htmlspecialchars((string)($row['前回比割合'] ?? '')) . ')';
                                } elseif (in_array($col, ['現金', '個別株', '投信', '仮想通貨', '年金', 'コモディティ'], true)) {
                                    $displayVal = number_format((float)$val)
                                        . ' (' . htmlspecialchars((string)($row[$col . '割合'] ?? '')) . ')';
                                } elseif ((
                                    $col === '金額_合計' ||
                                    $col === '評価損益_合計' ||
                                    preg_match('/(金額|損益|現金|株式|投信|仮想通貨|年金|コモディティ|比)$/u', $col)
                                ) && is_numeric($val)) {
                                    $displayVal = number_format($val);
                                } elseif (preg_match('/割合$/u', $col)) {
                                    $displayVal = htmlspecialchars((string)($val ?? ''));
                                } else {
                                    $displayVal = htmlspecialchars((string)($val ?? ''));
                                }
                                ?>
                                <td class="text-end">
                                    <?php if ($isNegativeValue): ?>
                                        <span class="text-danger"><?= $displayVal ?></span>
                                    <?php else: ?>
                                        <?= $displayVal ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td>データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <form method="get" action="" class="mt-3 mb-3">
        <input type="hidden" name="action" value="asset_summaries_all">
        <label for="history_no" class="form-label">履歴番号</label>
        <select name="history_no" id="history_no" class="form-select" style="width:200px; display:inline-block;">
            <option value="">選択してください</option>
            <?php foreach ($assets as $row): ?>
                <?php $historyNo = (string)($row['履歴番号'] ?? ''); ?>
                <option value="<?= htmlspecialchars($historyNo) ?>" <?= $selectedHistoryNo === $historyNo ? 'selected' : '' ?>>
                    <?= htmlspecialchars($historyNo) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-outline-secondary btn-sm">表示</button>
    </form>
    <?php if ($selectedHistoryNo !== ''): ?>
        <div style="max-width:1000px;">
            <table id="assetCategorySummaryTable" class="table table-hover table-sm">
                <thead>
                    <tr class="table-secondary fw-bold">
                        <th>資産区分</th>
                        <th class="text-end">金額</th>
                        <th class="text-end">構成比</th>
                        <th class="text-end">前月比</th>
                        <th class="text-end">前月比割合</th>
                        <th class="text-end">評価損益</th>
                        <th class="text-end">評価損益割合</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assetCategorySummary as $row): ?>
                        <tr class="<?= $row['資産区分'] === '合計' ? 'table-secondary fw-bold' : '' ?>">
                            <td><?= htmlspecialchars($row['資産区分']) ?></td>
                            <td class="text-end"><?= number_format($row['金額']) ?>円</td>
                            <td class="text-end"><?= htmlspecialchars($row['構成比']) ?></td>
                            <td class="text-end <?= $row['前月比'] < 0 ? 'text-danger' : '' ?>">
                                <?= number_format($row['前月比']) ?>円
                            </td>
                            <td class="text-end <?= strpos($row['前月比割合'], '-') === 0 ? 'text-danger' : '' ?>">
                                <?= htmlspecialchars($row['前月比割合']) ?>
                            </td>
                            <td class="text-end <?= $row['評価損益'] < 0 ? 'text-danger' : '' ?>">
                                <?= number_format($row['評価損益']) ?>円
                            </td>
                            <td class="text-end <?= strpos($row['評価損益割合'], '-') === 0 ? 'text-danger' : '' ?>">
                                <?= htmlspecialchars($row['評価損益割合']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3" style="max-width:1000px;">
            <img id="assetCategorySummaryImage" class="img-fluid" alt="資産区分別集計表の画像" hidden>
            <p id="assetCategorySummaryImageError" class="text-danger" role="status" hidden>
                表の画像を生成できませんでした。
            </p>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
        <script>
            const summaryTable = document.getElementById('assetCategorySummaryTable');
            const summaryImage = document.getElementById('assetCategorySummaryImage');
            const summaryImageError = document.getElementById('assetCategorySummaryImageError');

            if (typeof html2canvas === 'function') {
                html2canvas(summaryTable, { backgroundColor: '#ffffff', scale: 2 })
                    .then(canvas => {
                        summaryImage.src = canvas.toDataURL('image/png');
                        summaryImage.hidden = false;
                    })
                    .catch(() => {
                        summaryImageError.hidden = false;
                    });
            } else {
                summaryImageError.hidden = false;
            }
        </script>
    <?php endif; ?>
</body>
</html>