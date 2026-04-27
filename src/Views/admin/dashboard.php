<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .admin-sidebar { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: fit-content; }
        .admin-sidebar nav a { display: block; padding: 0.8rem; margin-bottom: 0.5rem; border-radius: 4px; color: var(--text-color); }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: var(--primary-color); color: white; }
        .admin-content { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--bg-color); padding: 1.5rem; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 2.5rem; font-weight: bold; color: var(--primary-color); }
        .stat-label { color: var(--text-light); }
        .btn { display: inline-block; padding: 0.7rem 1.5rem; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: var(--primary-dark); }
        .btn-secondary { background: var(--secondary-color); }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: var(--bg-color); }
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
                <a href="<?= SITE_URL ?>/admin/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">📊 Dashboard</a>
                <a href="<?= SITE_URL ?>/admin/news" class="<?= strpos($_SERVER['REQUEST_URI'], '/news') !== false ? 'active' : '' ?>">📰 Notícias</a>
                <a href="<?= SITE_URL ?>/admin/news/add" class="<?= strpos($_SERVER['REQUEST_URI'], '/news/add') !== false ? 'active' : '' ?>">➕ Adicionar</a>
                <a href="<?= SITE_URL ?>/admin/polls" class="<?= strpos($_SERVER['REQUEST_URI'], 'polls') !== false ? 'active' : '' ?>">📋 Enquetes</a>
                <a href="<?= SITE_URL ?>/admin/settings" class="<?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">⚙️ Configurações</a>
                <a href="<?= SITE_URL ?>/admin/logout" style="margin-top: 2rem; border-top: 1px solid #ddd; padding-top: 1rem;">🚪 Sair</a>
            </nav>
        </aside>
        
        <main class="admin-content">
            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success">✅ Feeds atualizados com sucesso!</div>
            <?php endif; ?>
            
            <h1 style="margin-bottom: 1.5rem;">Dashboard</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($totalNews) ?></div>
                    <div class="stat-label">Total de Notícias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($sources) ?></div>
                    <div class="stat-label">Fontes Ativas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($CATEGORIES) ?></div>
                    <div class="stat-label">Categorias</div>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <a href="<?= SITE_URL ?>/admin/update-feeds" class="btn">🔄 Atualizar Feeds Agora</a>
                <a href="<?= SITE_URL ?>/admin/news/add" class="btn btn-secondary">➕ Nova Notícia Manual</a>
            </div>
            
            <h3 style="margin-bottom: 1rem;">📈 Notícias por Categoria</h3>
            <table>
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newsByCategory as $cat): ?>
                        <tr>
                            <td><?= Helpers::escape($CATEGORIES[$cat['category']] ?? $cat['category']) ?></td>
                            <td><?= number_format($cat['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <p style="margin-top: 2rem; color: var(--text-light);">
                Última atualização: <?= $lastUpdate ?>
            </p>
        </main>
    </div>
</body>
</html>
