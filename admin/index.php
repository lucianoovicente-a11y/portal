<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/NewsModel.php';
require_once __DIR__ . '/../src/Models/SettingsModel.php';
require_once __DIR__ . '/../src/Models/PollModel.php';

$settingsModel = new SettingsModel();
$newsModel = new NewsModel();
$pollModel = new PollModel();

// Login check
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$config = $settingsModel->getAll();
$action = $_GET['action'] ?? 'dashboard';
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// Handle delete action
if ($action === 'delete_news' && isset($_GET['id']) && isset($_GET['confirm'])) {
    $newsModel->delete($_GET['id']);
    $message = 'Notícia excluída com sucesso!';
    $action = 'news';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .admin-header { background: #2c3e50; color: white; padding: 15px 0; }
        .admin-nav { display: flex; gap: 15px; margin-top: 15px; }
        .admin-nav a { color: white; text-decoration: none; padding: 8px 15px; background: rgba(255,255,255,0.1); border-radius: 5px; }
        .admin-nav a:hover, .admin-nav a.active { background: #3498db; }
        .admin-content { padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-card h3 { font-size: 36px; margin-bottom: 5px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form input, form textarea, form select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1>Painel Administrativo</h1>
            <nav class="admin-nav">
                <a href="?action=dashboard" class="<?= $action=='dashboard'?'active':'' ?>">Dashboard</a>
                <a href="?action=news" class="<?= $action=='news'?'active':'' ?>">Notícias</a>
                <a href="?action=add_news" class="<?= $action=='add_news'?'active':'' ?>">Adicionar</a>
                <a href="?action=settings" class="<?= $action=='settings'?'active':'' ?>">Configurações</a>
                <a href="?action=polls" class="<?= $action=='polls'?'active':'' ?>">Enquetes</a>
                <a href="/" target="_blank">Ver Site</a>
                <a href="logout.php" style="background:#e74c3c;">Sair</a>
            </nav>
        </div>
    </header>
    
    <div class="admin-content">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php
        switch ($action) {
            case 'dashboard':
                include 'dashboard.php';
                break;
            case 'news':
                include 'news.php';
                break;
            case 'add_news':
                include 'add_news.php';
                break;
            case 'edit_news':
                include 'edit_news.php';
                break;
            case 'settings':
                include 'settings.php';
                break;
            case 'polls':
                include 'polls.php';
                break;
            default:
                include 'dashboard.php';
        }
        ?>
    </div>
</body>
</html>
