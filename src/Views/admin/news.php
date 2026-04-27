<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Notícias - Admin</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .admin-sidebar { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: fit-content; }
        .admin-sidebar nav a { display: block; padding: 0.8rem; margin-bottom: 0.5rem; border-radius: 4px; color: var(--text-color); text-decoration: none; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: var(--primary-color); color: white; }
        .admin-content { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 0.7rem 1.5rem; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; }
        .btn:hover { background: var(--primary-dark); }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: var(--bg-color); }
        .filters { display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .filters select, .filters input { padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 2rem; }
        .pagination a { padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; }
        .pagination a.active { background: var(--primary-color); color: white; border-color: var(--primary-color); }
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
                <a href="<?= SITE_URL ?>/admin/news" class="active">📰 Notícias</a>
                <a href="<?= SITE_URL ?>/admin/news/add">➕ Adicionar</a>
                <a href="<?= SITE_URL ?>/admin/polls">📋 Enquetes</a>
                <a href="<?= SITE_URL ?>/admin/settings">⚙️ Configurações</a>
                <a href="<?= SITE_URL ?>/admin/logout" style="margin-top: 2rem; border-top: 1px solid #ddd; padding-top: 1rem;">🚪 Sair</a>
            </nav>
        </aside>
        
        <main class="admin-content">
            <h1 style="margin-bottom: 1.5rem;">📰 Gerenciar Notícias</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                    <?= Helpers::escape($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form class="filters" method="GET">
                <select name="category">
                    <option value="">Todas Categorias</option>
                    <?php foreach ($categories as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $currentCategory === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search" placeholder="Buscar..." value="<?= Helpers::escape($search ?? '') ?>">
                <button type="submit" class="btn">Filtrar</button>
                <a href="<?= SITE_URL ?>/admin/news" class="btn btn-secondary">Limpar</a>
            </form>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Fonte</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td style="max-width: 400px;"><?= Helpers::escape(substr($item['title'], 0, 80)) ?><?= strlen($item['title']) > 80 ? '...' : '' ?></td>
                            <td><?= Helpers::escape($categories[$item['category']] ?? $item['category']) ?></td>
                            <td><?= Helpers::escape($item['source_name']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($item['pub_date'])) ?></td>
                            <td>
                                <a href="<?= SITE_URL ?>/admin/news/edit?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="<?= SITE_URL ?>/admin/news/delete?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?><?= $currentCategory ? '&category=' . $currentCategory : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                           class="<?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
