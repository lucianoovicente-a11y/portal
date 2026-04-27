<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsModel->updateBatch([
        'site_name' => $_POST['site_name'],
        'site_logo' => $_POST['site_logo'],
        'whatsapp' => $_POST['whatsapp'],
        'email' => $_POST['email'],
        'admin_user' => $_POST['admin_user'],
        'admin_password' => $_POST['admin_password'],
        'ads_header' => $_POST['ads_header'],
        'ads_sidebar' => $_POST['ads_sidebar'],
        'ads_footer' => $_POST['ads_footer']
    ]);
    $_SESSION['message'] = 'Configurações salvas com sucesso!';
    header('Location: index.php?action=settings');
    exit;
}
?>
<div class="card">
    <h2>Configurações do Site</h2>
    <form method="POST">
        <h3>Geral</h3>
        <label>Nome do Site</label>
        <input type="text" name="site_name" value="<?= htmlspecialchars($config['site_name']) ?>">
        
        <label>URL do Logotipo</label>
        <input type="text" name="site_logo" value="<?= htmlspecialchars($config['site_logo']) ?>" placeholder="https://...">
        
        <label>WhatsApp</label>
        <input type="text" name="whatsapp" value="<?= htmlspecialchars($config['whatsapp']) ?>" placeholder="+55 11 99999-9999">
        
        <label>E-mail</label>
        <input type="email" name="email" value="<?= htmlspecialchars($config['email']) ?>">
        
        <h3>Acesso Admin</h3>
        <label>Usuário Admin</label>
        <input type="text" name="admin_user" value="<?= htmlspecialchars($config['admin_user']) ?>">
        
        <label>Senha Admin</label>
        <input type="text" name="admin_password" value="<?= htmlspecialchars($config['admin_password']) ?>">
        
        <h3>Publicidade (códigos HTML)</h3>
        <label>Anúncio Header</label>
        <textarea name="ads_header" rows="3"><?= htmlspecialchars($config['ads_header']) ?></textarea>
        
        <label>Anúncio Sidebar</label>
        <textarea name="ads_sidebar" rows="3"><?= htmlspecialchars($config['ads_sidebar']) ?></textarea>
        
        <label>Anúncio Footer</label>
        <textarea name="ads_footer" rows="3"><?= htmlspecialchars($config['ads_footer']) ?></textarea>
        
        <button type="submit" class="btn btn-success">Salvar Configurações</button>
    </form>
</div>
