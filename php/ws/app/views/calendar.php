<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <title>カレンダー</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <h2>カレンダー</h2>
    <?php
        $calendarEvents = $calendarEvents ?? [];
        $calendarError = $calendarError ?? '';
    ?>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=main'">戻る</button>
    </div>

    <?php if ($calendarError !== ''): ?>
        <div class="alert alert-warning" role="alert" style="max-width: 1200px;">
            <?= htmlspecialchars($calendarError) ?>
        </div>
    <?php endif; ?>

    <div class="mt-3" style="max-width: 1800px;">
        <h5>Googleカレンダー予定</h5>
        <table class="table table-hover table-sm table-bordered bg-white">
            <thead>
                <tr>
                    <th>件名</th>
                    <th>開始</th>
                    <th>終了</th>
                    <th>場所</th>
                    <th>説明</th>
                    <th>リンク</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($calendarEvents)): ?>
                    <?php foreach ($calendarEvents as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['summary'] ?? '') ?></td>
                            <td><?= htmlspecialchars($event['start'] ?? '') ?></td>
                            <td><?= htmlspecialchars($event['end'] ?? '') ?></td>
                            <td><?= htmlspecialchars($event['location'] ?? '') ?></td>
                            <td style="max-width: 460px; white-space: pre-wrap; word-break: break-word;"><?= htmlspecialchars($event['description'] ?? '') ?></td>
                            <td>
                                <?php if (!empty($event['link'])): ?>
                                    <a href="<?= htmlspecialchars($event['link']) ?>" target="_blank" rel="noopener noreferrer">開く</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">表示できる予定がありません</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
