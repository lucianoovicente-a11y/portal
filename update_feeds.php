<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Models/Database.php';
require_once __DIR__ . '/src/Services/FeedService.php';

// Suporte a atualização manual via POST do admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manual_update'])) {
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        die('Não autorizado');
    }
}

echo "=== Atualização de Feeds RSS ===\n";
echo "Iniciado em: " . date('Y-m-d H:i:s') . "\n\n";

$feedService = new FeedService();
$totalAdded = $feedService->fetchAllFeeds();

echo "\nConcluído em: " . date('Y-m-d H:i:s') . "\n";
echo "Total de novas notícias adicionadas: $totalAdded\n";

// Redirecionamento se solicitado
if (isset($_GET['redirect'])) {
    header('Location: /?skip_update=1');
    exit;
}
