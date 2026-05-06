<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/AssetSummary.php';
require_once __DIR__ . '/../models/AssetDetail.php';
require_once __DIR__ . '/../models/AssetRatio.php';
require_once __DIR__ . '/../models/AssetRatioCountry.php';
require_once __DIR__ . '/../models/AssetRatioCash.php';
require_once __DIR__ . '/../models/AssetRatioScale.php';

class AssetSummaryController {
    public function index() {
        $pdo = Database::getInstance();
        $assets = AssetSummary::getAll($pdo);
        // 最大履歴番号を取得
        $historyNo = isset($_GET['history_no']) ? $_GET['history_no'] : null;
        if ($historyNo === null && !empty($assets)) {
            // $assetsは履歴番号降順なので先頭が最大
            $historyNo = $assets[0]['履歴番号'];
        }
        $assetDetails = AssetDetail::getAll($pdo, $historyNo);
        $assetRatios = AssetRatio::getAll($pdo, $historyNo);
        $assetRatioCountries = AssetRatioCountry::getAll($pdo, $historyNo);
        $assetRatioCashs = AssetRatioCash::getAll($pdo, $historyNo);
        require_once __DIR__ . '/../models/AssetRatioAccount.php';
        $assetRatioScales = AssetRatioScale::getAll($pdo, $historyNo);
        $assetRatioAccounts = AssetRatioAccount::getAll($pdo, $historyNo);
        include __DIR__ . '/../views/asset_summary.php';
    }
}
