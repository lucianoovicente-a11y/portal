<?php
/**
 * Ponto de entrada para área administrativa
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Models/NewsModel.php';
require_once __DIR__ . '/src/Models/SettingsModel.php';
require_once __DIR__ . '/src/Models/PollModel.php';
require_once __DIR__ . '/src/Services/FeedService.php';
require_once __DIR__ . '/src/Controllers/AdminController.php';
require_once __DIR__ . '/src/Utils/Helpers.php';

// Inicializar dependências
$db = getDbConnection();
$newsModel = new NewsModel($db);
$settingsModel = new SettingsModel($db);
$pollModel = new PollModel($db);
$feedService = new FeedService($GLOBALS['NEWS_SOURCES']);
$adminController = new AdminController($newsModel, $settingsModel, $pollModel, $feedService);

// Roteamento simples
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = dirname($_SERVER['SCRIPT_NAME']);

if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Remover prefixo /admin
if (strpos($requestUri, '/admin') === 0) {
    $requestUri = substr($requestUri, 6);
}

// Rotas
switch ($requestUri) {
    case '':
    case '/':
    case '/dashboard':
        $adminController->dashboard();
        break;
    
    case '/login':
        $adminController->login();
        break;
    
    case '/logout':
        $adminController->logout();
        break;
    
    case '/news':
        $adminController->news();
        break;
    
    case '/news/add':
        $adminController->addNews();
        break;
    
    case '/news/edit':
        $adminController->editNews();
        break;
    
    case '/news/delete':
        $adminController->deleteNews();
        break;
    
    case '/settings':
        $adminController->settings();
        break;
    
    case '/polls':
        $adminController->polls();
        break;
    
    case '/update-feeds':
        $adminController->updateFeeds();
        break;
    
    default:
        header('Location: ' . SITE_URL . '/admin/dashboard');
        exit;
}
