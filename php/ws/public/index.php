<?php
// シンプルなルーター
session_start();
require_once '../app/controllers/LoginController.php';
require_once '../app/controllers/MainController.php';
require_once '../app/controllers/ProfileController.php';
require_once '../app/controllers/SettingsController.php';
require_once '../app/controllers/LogoutController.php';
require_once '../app/controllers/ReadingController.php';
require_once '../app/models/User.php';
require_once '../app/models/ReadingRecord.php';
require_once '../app/controllers/VideoController.php';
require_once '../app/models/VideoRecord.php';
require_once '../app/controllers/KubunCodeController.php';
require_once '../app/models/KubunCode.php';
require_once '../app/controllers/AssetKubunMasterController.php';
require_once '../app/controllers/AssetMasterController.php';
require_once '../app/controllers/RssFeedController.php';
require_once '../app/controllers/AssetSummaryController.php';

 $routes = [
    'asset_master' => [AssetMasterController::class, 'index'],
    'add_asset_master' => [AssetMasterController::class, 'add'],
    'insert_asset_master' => [AssetMasterController::class, 'insert'],
    'update_asset_master' => [AssetMasterController::class, 'update'],
    'edit_asset_master' => [AssetMasterController::class, 'edit'],
    'asset_kubun_master' => [AssetKubunMasterController::class, 'index'],
    'edit_asset_kubun_master' => [AssetKubunMasterController::class, 'edit'],
    'update_asset_kubun_master' => [AssetKubunMasterController::class, 'update'],
    'add_asset_kubun_master' => [AssetKubunMasterController::class, 'add'],
    'insert_asset_kubun_master' => [AssetKubunMasterController::class, 'insert'],
    'login' => [LoginController::class, 'index'],
    'authenticate' => [LoginController::class, 'authenticate'],
    'main' => [MainController::class, 'index'],
    'profile' => [ProfileController::class, 'index'],
    'settings' => [SettingsController::class, 'index'],
    'update_settings' => [SettingsController::class, 'update'],
    'logout' => [LogoutController::class, 'index'],
    'reading_records' => [ReadingController::class, 'index'],
    'edit_reading_record' => [ReadingController::class, 'edit'],
    'update_reading_record' => [ReadingController::class, 'update'],
    'add_reading_record' => [ReadingController::class, 'add'],
    'insert_reading_record' => [ReadingController::class, 'insert'],
    'video_records' => [VideoController::class, 'index'],
    'add_video_record' => [VideoController::class, 'add'],
    'edit_video_record' => [VideoController::class, 'edit'],
    'insert_video_record' => [VideoController::class, 'insert'],
    'update_video_record' => [VideoController::class, 'update'],
    'kubun_codes' => [KubunCodeController::class, 'index'],
    'edit_kubun_code' => [KubunCodeController::class, 'edit'],
    'update_kubun_code' => [KubunCodeController::class, 'update'],
    'add_kubun_code' => [KubunCodeController::class, 'add'],
    'insert_kubun_code' => [KubunCodeController::class, 'insert'],
    'rss_feed' => [RssFeedController::class, 'index'],
    'asset_summary' => [AssetSummaryController::class, 'index'],
];

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

if (isset($routes[$action])) {
    [$controllerClass, $method] = $routes[$action];
    $controller = new $controllerClass();
    $controller->$method();
} else {
    // デフォルト
    $controller = new LoginController();
    $controller->index();
}
?>