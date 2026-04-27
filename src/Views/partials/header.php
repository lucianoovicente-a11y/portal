<header class="site-header">
    <div class="header-top">
        <div class="container header-content">
            <div class="logo">
                <?php if (!empty($config['site_logo'])): ?>
                    <img src="<?= htmlspecialchars($config['site_logo']) ?>" alt="Logo">
                <?php else: ?>
                    <h1><?= htmlspecialchars($config['site_name'] ?? 'Portal de Notícias') ?></h1>
                <?php endif; ?>
            </div>
            
            <div class="header-actions">
                <form class="search-form" method="GET">
                    <input type="text" name="search" placeholder="Buscar notícias..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button type="submit">🔍</button>
                </form>
                <a href="/admin" class="btn-admin">Admin</a>
            </div>
        </div>
    </div>
    
    <nav class="main-nav">
        <div class="container">
            <ul>
                <li><a href="/" class="<?= !$category ? 'active' : '' ?>">Todas</a></li>
                <?php foreach (CATEGORIES as $key => $label): ?>
                    <li><a href="/?category=<?= $key ?>" class="<?= $category === $key ? 'active' : '' ?>"><?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    
    <?php if (!empty($config['ads_header'])): ?>
        <div class="ads-banner ads-header"><?= $config['ads_header'] ?></div>
    <?php endif; ?>
</header>
