<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>映像記録追加</title>
</head>
<body class="bg-body-secondary">
    <h2>映像記録追加</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=video_records'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="addForm">追加</button>
    </div>
    <form id="addForm" action="?action=insert_video_record" method="post">
        <table>
            <tr><td>年月日:</td><td><input type="date" name="年月日" value=""></td></tr>
            <tr><td>タイトル:</td><td><input type="text" name="タイトル" value="" style="width: 400px;"></td></tr>
            <tr><td>監督:</td><td><input type="text" name="監督" value="" style="width: 400px;"></td></tr>
            <tr><td>主演:</td><td><input type="text" name="主演" value="" style="width: 400px;"></td></tr>
            <tr><td>映像区分:</td><td>
                <?php
                require_once __DIR__ . '/../models/KubunCode.php';
                $kubunModel = new KubunCode();
                $kubunList = $kubunModel->getKubunCodes('区分コード', 'ASC');
                ?>
                <select name="映像区分" class="form-select" style="width: 400px;">
                <option value="">選択してください</option>
                <?php foreach ($kubunList as $kubun) {
                    if ($kubun['区分コード'] === '00800') {
                        echo '<option value="' . htmlspecialchars($kubun['コード']) . '">' . htmlspecialchars($kubun['コード名']) . '</option>';
                    }
                } ?>
                </select>
            </td></tr>
            <tr><td>映画館:</td><td><input type="text" name="映画館" value="" style="width: 400px;"></td></tr>
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
                        echo '<option value="' . htmlspecialchars($kubun['コード']) . '">' . htmlspecialchars($kubun['コード名']) . '</option>';
                    }
                } ?>
                </select>
            </td></tr>
            <tr><td>備考:</td><td><textarea name="備考" style="width: 400px;"></textarea></td></tr>
        </table>
    </form>
</body>
</html>
