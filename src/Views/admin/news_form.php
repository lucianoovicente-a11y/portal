<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editMode ? 'Editar' : 'Adicionar' ?> Notícia - Admin</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">
    <style>
        .admin-layout { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .admin-sidebar { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); height: fit-content; }
        .admin-sidebar nav a { display: block; padding: 0.8rem; margin-bottom: 0.5rem; border-radius: 4px; color: var(--text-color); text-decoration: none; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: var(--primary-color); color: white; }
        .admin-content { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 0.7rem 1.5rem; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: var(--primary-dark); }
        .btn-secondary { background: var(--secondary-color); }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        .form-group textarea { min-height: 150px; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
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
            <h1 style="margin-bottom: 1.5rem;"><?= $editMode ? '✏️ Editar' : '➕ Adicionar' ?> Notícia</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= Helpers::escape($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= Helpers::escape($error) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Título *</label>
                    <input type="text" name="title" required value="<?= Helpers::escape($news['title'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="description"><?= Helpers::escape($news['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Link *</label>
                    <input type="url" name="link" required value="<?= Helpers::escape($news['link'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label>Categoria *</label>
                    <select name="category" required>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($news['category'] ?? '') === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>URL da Imagem</label>
                    <input type="url" name="image_url" value="<?= Helpers::escape($news['image_url'] ?? '') ?>">
                </div>
                
                <?php if (!$editMode): ?>
                <div class="form-group">
                    <label>Fonte/Nome da Publicação</label>
                    <input type="text" name="source_name" value="<?= Helpers::escape($news['source_name'] ?? 'Manual') ?>">
                </div>
                <?php endif; ?>
                
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn"><?= $editMode ? 'Atualizar' : 'Adicionar' ?></button>
                    <a href="<?= SITE_URL ?>/admin/news" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
