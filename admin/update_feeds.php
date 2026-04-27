<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Services/FeedService.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Não autorizado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Atualizando feeds...\n";
    $feedService = new FeedService();
    $totalAdded = $feedService->fetchAllFeeds();
    $_SESSION['message'] = "Atualização concluída! $totalAdded novas notícias.";
}

header('Location: index.php?action=dashboard');
exit;
