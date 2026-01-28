<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="/ws/vendor/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/ws/vendor/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <title>資産マスタ追加</title>
</head>
<body class="bg-body-tertiary">
    <h2>資産マスタ追加</h2>
    <div style="margin-bottom: 10px;">
        <button type="button" class="btn btn-outline-primary" onclick="location.href='?action=asset_master'">戻る</button>
        <button type="submit" class="btn btn-outline-primary" form="addForm">追加</button>
    </div>
    <form id="addForm" method="post" action="?action=insert_asset_master">
        <table>
            <tr><td>資産区分コード:</td><td>
                <?php
                require_once __DIR__ . '/../models/AssetKubunMaster.php';
                $kubunModel = new AssetKubunMaster();
                $kubunList = $kubunModel->getAll();
                ?>
                <select name="資産区分コード" class="form-select" style="width: 400px;">
                <option value="">選択してください</option>
                <?php foreach ($kubunList as $kubun) {
                    echo '<option value="' . htmlspecialchars($kubun['資産区分コード']) . '">' . htmlspecialchars($kubun['資産区分名']) . '</option>';
                } ?>
                </select>
            </td></tr>
            <tr><td>資産コード:</td><td><input type="text" name="資産コード" style="width: 400px;"></td></tr>
            <tr><td>資産名:</td><td><input type="text" name="資産名" style="width: 400px;"></td></tr>
            <tr><td>資産略名:</td><td><input type="text" name="資産略名" style="width: 400px;"></td></tr>
            <?php
            require_once __DIR__ . '/../models/KubunCode.php';
            $kubunCodeModel = new KubunCode();
            $kubunCodes = $kubunCodeModel->getKubunCodes('区分コード', 'ASC');
            function kubunSelectAdd($name, $kubunCode, $kubunCodes) {
                echo '<select name="' . $name . '" class="form-select" style="width: 400px;">';
                echo '<option value="">選択してください</option>';
                foreach ($kubunCodes as $kubun) {
                    if ($kubun['区分コード'] === $kubunCode) {
                        echo '<option value="' . htmlspecialchars($kubun['コード']) . '">' . htmlspecialchars($kubun['コード名']) . '</option>';
                    }
                }
                echo '</select>';
            }
            ?>
            <tr><td>国内外区分:</td><td><?php kubunSelectAdd('国内外区分', '00300', $kubunCodes); ?></td></tr>
            <tr><td>通貨区分:</td><td><?php kubunSelectAdd('通貨区分', '00400', $kubunCodes); ?></td></tr>
            <tr><td>口座区分:</td><td><?php kubunSelectAdd('口座区分', '00500', $kubunCodes); ?></td></tr>
            <tr><td>長短区分:</td><td><?php kubunSelectAdd('長短区分', '00600', $kubunCodes); ?></td></tr>
            <tr><td>売買区分:</td><td><?php kubunSelectAdd('売買区分', '00700', $kubunCodes); ?></td></tr>
        </table>
    </form>
</body>
</html>
