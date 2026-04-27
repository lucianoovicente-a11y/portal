<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/NewsModel.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Não autorizado');
}

$id = $_GET['id'] ?? 0;
if ($id && isset($_GET['confirm'])) {
    $newsModel = new NewsModel();
    $newsModel->delete($id);
    $_SESSION['message'] = 'Notícia excluída com sucesso!';
}

header('Location: index.php?action=news');
exit;
