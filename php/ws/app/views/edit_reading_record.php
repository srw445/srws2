<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>読書記録編集</title>
</head>
<body class="bg-body-secondary">
    <h2>読書記録編集</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=reading_records'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="editForm">更新</button>
    </div>
    <form id="editForm" action="?action=update_reading_record" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['連番']); ?>">
        <table>
            <tr><td>タイトル:</td><td><input type="text" name="タイトル" value="<?php echo htmlspecialchars($record['タイトル'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>作者:</td><td><input type="text" name="作者" value="<?php echo htmlspecialchars($record['作者'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>出版社:</td><td><input type="text" name="出版社" value="<?php echo htmlspecialchars($record['出版社'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>形式:</td><td>
                <?php
                require_once __DIR__ . '/../models/KubunCode.php';
                $kubunModel = new KubunCode();
                $formList = $kubunModel->getKubunCodes('区分コード', 'ASC');
                ?>
                <select name="形式" class="form-select" style="width: 400px;">
                <option value="">選択してください</option>
                <?php foreach ($formList as $kubun) {
                    if ($kubun['区分コード'] === '00100') {
                        $selected = ($record['形式'] ?? '') == $kubun['コード'] ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($kubun['コード']) . '" ' . $selected . '>' . htmlspecialchars($kubun['コード名']) . '</option>';
                    }
                } ?>
                </select>
            </td></tr>
            <tr><td>ジャンル:</td><td><input type="text" name="ジャンル" value="<?php echo htmlspecialchars($record['ジャンル'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>ページ数:</td><td><input type="number" name="ページ数" value="<?php echo htmlspecialchars($record['ページ数'] ?? ''); ?>"></td></tr>
            <tr><td>定価:</td><td><input type="number" name="定価" value="<?php echo htmlspecialchars($record['定価'] ?? ''); ?>"></td></tr>
            <tr><td>受賞:</td><td><input type="text" name="受賞" value="<?php echo htmlspecialchars($record['受賞'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>初版日:</td><td><input type="date" name="初版日" value="<?php echo htmlspecialchars($record['初版日'] ?? ''); ?>"></td></tr>
            <tr><td>読了日:</td><td><input type="date" name="読了日" value="<?php echo htmlspecialchars($record['読了日'] ?? ''); ?>"></td></tr>
            <tr><td>表紙ファイル名:</td><td><input type="text" name="表紙ファイル名" value="<?php echo htmlspecialchars($record['表紙ファイル名'] ?? ''); ?>" style="width: 400px;"></td></tr>
            <tr><td>備考:</td><td><textarea name="備考" style="width: 400px;"><?php echo htmlspecialchars($record['備考'] ?? ''); ?></textarea></td></tr>
            <tr><td>評価:</td><td>
                <?php
                require_once __DIR__ . '/../models/KubunCode.php';
                $kubunModel = new KubunCode();
                $evalList = $kubunModel->getKubunCodes('区分コード', 'ASC');
                ?>
                <select name="評価" class="form-select" style="width: 400px;">
                <option value="">選択してください</option>
                <?php foreach ($evalList as $kubun) {
                    if ($kubun['区分コード'] === '00200') {
                        $selected = ($record['評価'] ?? '') == $kubun['コード'] ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($kubun['コード']) . '" ' . $selected . '>' . htmlspecialchars($kubun['コード名']) . '</option>';
                    }
                } ?>
                </select>
            </td></tr>
        </table>
    </form>
</body>
</html>