<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Admin</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .admin-sidebar { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: fit-content; }
        .admin-sidebar nav a { display: block; padding: 0.8rem; margin-bottom: 0.5rem; border-radius: 4px; color: var(--text-color); text-decoration: none; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: var(--primary-color); color: white; }
        .admin-content { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 0.7rem 1.5rem; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: var(--primary-dark); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        .form-group textarea { min-height: 100px; font-family: monospace; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .settings-section { margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #eee; }
        .settings-section h3 { margin-bottom: 1rem; color: var(--primary-color); }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">⚙️ Painel Administrativo</div>
            <a href="<?= SITE_URL ?>" class="btn btn-secondary">Ver Site</a>
        </div>
    </header>
    
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav>
                <a href="<?= SITE_URL ?>/admin/dashboard">📊 Dashboard</a>
                <a href="<?= SITE_URL ?>/admin/news">📰 Notícias</a>
                <a href="<?= SITE_URL ?>/admin/news/add">➕ Adicionar</a>
                <a href="<?= SITE_URL ?>/admin/polls">📋 Enquetes</a>
                <a href="<?= SITE_URL ?>/admin/settings" class="active">⚙️ Configurações</a>
                <a href="<?= SITE_URL ?>/admin/logout" style="margin-top: 2rem; border-top: 1px solid #ddd; padding-top: 1rem;">🚪 Sair</a>
            </nav>
        </aside>
        
        <main class="admin-content">
            <h1 style="margin-bottom: 1.5rem;">⚙️ Configurações do Portal</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= Helpers::escape($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= Helpers::escape($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="settings-section">
                    <h3>🏷️ Informações do Site</h3>
                    <div class="form-group">
                        <label>Nome do Site</label>
                        <input type="text" name="site_name" value="<?= Helpers::escape($settings['site_name']) ?>">
                    </div>
                    <div class="form-group">
                        <label>URL do Logotipo</label>
                        <input type="url" name="site_logo" value="<?= Helpers::escape($settings['site_logo']) ?>" placeholder="https://exemplo.com/logo.png">
                    </div>
                </div>
                
                <div class="settings-section">
                    <h3>📞 Contato</h3>
                    <div class="form-group">
                        <label>WhatsApp</label>
                        <input type="text" name="whatsapp" value="<?= Helpers::escape($settings['whatsapp']) ?>" placeholder="+55 11 99999-9999">
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="<?= Helpers::escape($settings['email']) ?>">
                    </div>
                </div>
                
                <div class="settings-section">
                    <h3>🔐 Acesso Administrativo</h3>
                    <div class="form-group">
                        <label>Usuário Admin</label>
                        <input type="text" name="admin_username" value="<?= Helpers::escape($settings['admin_username']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Senha Admin</label>
                        <input type="text" name="admin_password" value="<?= Helpers::escape($settings['admin_password']) ?>">
                    </div>
                </div>
                
                <div class="settings-section">
                    <h3>📢 Publicidade (Códigos HTML/JS)</h3>
                    <div class="form-group">
                        <label>Código Header (aparece no topo)</label>
                        <textarea name="ad_code_header"><?= Helpers::escape($settings['ad_code_header']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Código Sidebar (barra lateral)</label>
                        <textarea name="ad_code_sidebar"><?= Helpers::escape($settings['ad_code_sidebar']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Código Footer (rodapé)</label>
                        <textarea name="ad_code_footer"><?= Helpers::escape($settings['ad_code_footer']) ?></textarea>
                    </div>
                </div>
                
                <button type="submit" class="btn">💾 Salvar Configurações</button>
            </form>
        </main>
    </div>
</body>
</html>
