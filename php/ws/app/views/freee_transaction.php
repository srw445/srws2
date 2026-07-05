<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>freee取引確認</title>
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .summary-remark-ellipsis {
            display: inline-block;
            max-width: 840px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }
    </style>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__ . '/login_user.php'; ?>
    <?php if (!empty($_SESSION['freee_import_error'])): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_SESSION['freee_import_error']); unset($_SESSION['freee_import_error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['freee_import_message'])): ?>
        <div class="alert alert-info" role="alert"><?php echo htmlspecialchars($_SESSION['freee_import_message']); unset($_SESSION['freee_import_message']); ?></div>
    <?php endif; ?>
    <h2>freee取引確認</h2>
    <?php
        $freeeTransactions = $freeeTransactions ?? [];
        $accountSummaries = $accountSummaries ?? [];
        $selectedMonth = $selectedMonth ?? date('Y-m');
        $currentSort = $_GET['sort'] ?? '発生日';
        $currentOrder = strtoupper($_GET['order'] ?? 'DESC');
        $detailHeaders = [
            '取込番号' => '取込番号',
            '収支区分' => '収支区分',
            '発生日' => '発生日',
            '取引先' => '取引先',
            '勘定科目' => '勘定科目',
            '金額' => '金額',
            '備考' => '備考',
        ];
    ?>
    <?php
        $monthBase = DateTimeImmutable::createFromFormat('Y-m', $selectedMonth) ?: new DateTimeImmutable($selectedMonth . '-01');
        $prevMonth = $monthBase->modify('-1 month')->format('Y-m');
        $nextMonth = $monthBase->modify('+1 month')->format('Y-m');
    ?>
    <div style="margin-bottom: 10px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
        <button type="button" class="btn btn-outline-secondary" onclick="location.href='?action=freee_transaction_summary'">freee取引確認(集計)</button>
        <form id="importFreeeForm" action="?action=import_freee_transaction" method="post" enctype="multipart/form-data" style="display:inline;">
            <label for="importFreeeFile" class="btn btn-outline-success mb-0">取引
                <input type="file" id="importFreeeFile" name="import_file" accept=".csv,text/csv" style="display:none;" onchange="document.getElementById('importFreeeForm').submit();">
            </label>
        </form>
    </div>
    <form method="get" action="" style="margin-bottom: 10px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <input type="hidden" name="action" value="freee_transaction">
        <label class="d-flex align-items-center gap-2 mb-0">
            <span>表示月</span>
            <input type="month" name="month" class="form-control" style="width: 160px;" value="<?= htmlspecialchars($selectedMonth) ?>">
        </label>
        <button type="submit" class="btn btn-outline-primary">検索</button>
        <a class="btn btn-outline-secondary" href="?action=freee_transaction&amp;month=<?= htmlspecialchars($prevMonth) ?>">前月</a>
        <a class="btn btn-outline-secondary" href="?action=freee_transaction&amp;month=<?= htmlspecialchars($nextMonth) ?>">翌月</a>
    </form>

    <div class="mt-3" style="max-width: 1600px;">
        <h5 class="mb-2">勘定科目集計</h5>
        <table class="table table-hover table-sm table-bordered bg-white">
            <thead>
                <tr>
                    <th>発生月</th>
                    <th>勘定科目</th>
                    <th>データ件数</th>
                    <th>合計金額</th>
                    <th>備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accountSummaries)): ?>
                    <?php foreach ($accountSummaries as $summary): ?>
                        <?php $summaryRemark = (string)($summary['備考'] ?? ''); ?>
                        <tr>
                            <td><?= htmlspecialchars($summary['発生月'] ?? '') ?></td>
                            <td><?= htmlspecialchars($summary['勘定科目'] ?? '') ?></td>
                            <td><?= number_format((int)($summary['データ件数'] ?? 0)) ?></td>
                            <td><?= number_format((float)($summary['合計金額'] ?? 0)) ?></td>
                            <td>
                                <span class="summary-remark-ellipsis" title="<?= htmlspecialchars($summaryRemark) ?>">
                                    <?= htmlspecialchars($summaryRemark) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3" style="max-width: 1600px;">
        <h5 class="mb-2">取引明細</h5>
        <table class="table table-hover table-sm">
            <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                <?php
                    echo '<tr>';
                    foreach ($detailHeaders as $key => $label) {
                        $nextOrder = ($currentSort === $key && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
                        $href = '?action=freee_transaction&month=' . urlencode($selectedMonth) . '&sort=' . urlencode($key) . '&order=' . $nextOrder;
                        echo '<th><a href="' . $href . '">' . htmlspecialchars($label) . '</a></th>';
                    }
                    echo '</tr>';
                ?>
            </thead>
            <tbody>
                <?php if (!empty($freeeTransactions)): ?>
                    <?php foreach ($freeeTransactions as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['取込番号'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['収支区分'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['発生日'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['取引先'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['勘定科目'] ?? '') ?></td>
                            <td><?= number_format((float)($row['金額'] ?? 0)) ?></td>
                            <td><?= htmlspecialchars($row['備考'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">データがありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>