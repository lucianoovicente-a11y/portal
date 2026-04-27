<?php
/**
 * Mega Portal de Notícias - Página Principal
 * Interface responsiva e moderna em PHP puro
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Models/NewsModel.php';
require_once __DIR__ . '/src/Models/SettingsModel.php';
require_once __DIR__ . '/src/Services/FeedService.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Utils/Helpers.php';

// Inicializar dependências
$db = getDbConnection();
$newsModel = new NewsModel($db);
$settingsModel = new SettingsModel($db);
$feedService = new FeedService($GLOBALS['NEWS_SOURCES']);
$controller = new HomeController($newsModel, $feedService, $settingsModel);

// Executar controller
$controller->index();
