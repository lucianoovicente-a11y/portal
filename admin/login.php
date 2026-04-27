<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/SettingsModel.php';

$settingsModel = new SettingsModel();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    $adminUser = $settingsModel->get('admin_user') ?: 'admin';
    $adminPass = $settingsModel->get('admin_password') ?: 'admin123';
    
    if ($user === $adminUser && $pass === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Usuário ou senha inválidos';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #5568d3; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🔐 Login Admin</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p style="text-align:center;margin-top:20px;color:#666;font-size:14px;">Padrão: admin / 132004</p>
    </div>
</body>
</html>
