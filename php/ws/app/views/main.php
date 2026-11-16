<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>メインメニュー</title>
</head>
<body class="bg-body-secondary">
    <?php include __DIR__.'/login_user.php'; ?>
    <?php
    require_once __DIR__.'/../models/Message.php';
    $userId = $_SESSION['user_id'] ?? '';
    $msg = Message::getRandomMessage($userId);
    if ($msg) {
        echo '<div class="alert alert-info mt-2 mb-3" style="font-size:1.1em;">'.nl2br(htmlspecialchars($msg)).'</div>';
    }
    ?>
    <h2>メインメニュー</h2>
    <?php
        $menuButtons = [
            ['label' => '読書記録', 'action' => 'reading_records'],
            ['label' => '映像記録', 'action' => 'video_records'],
            ['label' => '区分コードマスタ', 'action' => 'kubun_codes'],
            ['label' => 'RSSリーダー', 'action' => 'rss_feed'],
            ['label' => '資産区分マスタ', 'action' => 'asset_kubun_master'],
            ['label' => '資産マスタ', 'action' => 'asset_master'],
            ['label' => '資産管理', 'action' => 'asset_summary'],
            ['label' => 'freee取引確認', 'action' => 'freee_transaction'],
            ['label' => 'freee取引確認(集計)', 'action' => 'freee_transaction_summary'],
            ['label' => '住民税', 'action' => 'resident_tax'],
            ['label' => 'カレンダー', 'action' => 'calendar'],
            ['label' => '試験学習', 'action' => 'exam_study'],
        ];
    ?>
    <div class="table-responsive" style="max-width: 1100px;">
        <table class="table table-bordered align-middle bg-white">
            <tbody>
                <?php foreach (array_chunk($menuButtons, 4) as $rowButtons): ?>
                    <tr>
                        <?php foreach ($rowButtons as $button): ?>
                            <td style="width: 25%;">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="location.href='?action=<?= htmlspecialchars($button['action']) ?>'">
                                    <?= htmlspecialchars($button['label']) ?>
                                </button>
                            </td>
                        <?php endforeach; ?>
                        <?php for ($i = count($rowButtons); $i < 4; $i++): ?>
                            <td style="width: 25%;"></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- ログアウトボタンを一番下に配置 -->
    <div class="mt-5 d-flex justify-content-start">
        <button type="button" class="btn btn-primary mt-3" onclick="location.href='?action=logout'">ログアウト</button>
    </div>
    </div>
</body>
</html>