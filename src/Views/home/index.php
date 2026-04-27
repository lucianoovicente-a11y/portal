<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - As últimas notícias em tempo real</title>
    <meta name="description" content="Portal de notícias com atualização automática das principais fontes do Brasil e do mundo.">
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo">
                📰 <?= SITE_NAME ?>
                <span>- Notícias em tempo real</span>
            </div>
            <form class="search-form" method="GET" action="">
                <?php if ($category_filter): ?>
                    <input type="hidden" name="category" value="<?= Helpers::escape($category_filter) ?>">
                <?php endif; ?>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Buscar notícias..." 
                    value="<?= Helpers::escape($search ?? '') ?>"
                    aria-label="Buscar notícias"
                >
                <button type="submit" class="search-btn">🔍 Buscar</button>
            </form>
            <a href="<?= SITE_URL ?>/admin/login" class="btn btn-admin" style="background: white; color: var(--primary-color); padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 600;">⚙️ Admin</a>
        </div>
    </header>

    <!-- Breaking News -->
    <?php if ($latest_news): ?>
    <div class="breaking-news">
        <div class="breaking-content">
            <span class="breaking-label">🔴 ÚLTIMA</span>
            <div class="breaking-text">
                <a href="<?= Helpers::escape($latest_news['link']) ?>" target="_blank" rel="noopener noreferrer">
                    <?= Helpers::escape($latest_news['title']) ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container">
        <!-- News Grid -->
        <main class="news-grid">
            <?php if (empty($news)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h2 class="empty-title">Nenhuma notícia encontrada</h2>
                    <p class="empty-text">
                        <?php if ($search || $source_filter): ?>
                            Tente ajustar seus filtros ou termos de busca.
                        <?php else: ?>
                            Execute a atualização dos feeds para carregar as notícias.
                        <?php endif; ?>
                    </p>
                    <?php if (!$search && !$source_filter): ?>
                        <a href="<?= SITE_URL ?>/update_feeds.php" class="search-btn" style="display: inline-block;">🔄 Atualizar Agora</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($news as $item): ?>
                    <article class="news-card">
                        <?php if ($item['image_url']): ?>
                            <img 
                                src="<?= Helpers::escape($item['image_url']) ?>" 
                                alt="<?= Helpers::escape($item['title']) ?>"
                                class="news-image"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="news-placeholder" style="display: none;">📰</div>
                        <?php else: ?>
                            <div class="news-placeholder">📰</div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <div>
                                <div class="news-source"><?= Helpers::escape($item['source_name']) ?></div>
                                <h2 class="news-title">
                                    <a href="<?= Helpers::escape($item['link']) ?>" target="_blank" rel="noopener noreferrer">
                                        <?= Helpers::escape($item['title']) ?>
                                    </a>
                                </h2>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="news-description"><?= Helpers::escape($item['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="news-meta">
                                <span class="news-time">🕒 <?= Helpers::formatDate($item['pub_date']) ?></span>
                                <a href="<?= Helpers::escape($item['link']) ?>" class="news-link" target="_blank" rel="noopener noreferrer">
                                    Ler matéria completa →
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?><?= $source_filter ? '&source=' . urlencode($source_filter) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="page-link">← Anterior</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <a href="?page=<?= $i ?><?= $source_filter ? '&source=' . urlencode($source_filter) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                               class="page-link <?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?><?= $source_filter ? '&source=' . urlencode($source_filter) : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="page-link">Próxima →</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title">📁 Categorias</h3>
                <ul class="source-list">
                    <li class="source-item">
                        <a href="?page=1" class="source-link <?= empty($category_filter) ? 'active' : '' ?>">
                            Todas
                            <span class="source-count"><?= $total_news ?></span>
                        </a>
                    </li>
                    <?php foreach ($CATEGORIES as $key => $label): ?>
                        <li class="source-item">
                            <a href="?page=1&category=<?= urlencode($key) ?>" 
                               class="source-link <?= $category_filter === $key ? 'active' : '' ?>">
                                <?= $label ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">📊 Estatísticas</h3>
                <div style="font-size: 0.9rem;">
                    <p><strong>Total de notícias:</strong> <?= number_format($total_news) ?></p>
                    <p><strong>Categorias:</strong> <?= count($CATEGORIES) ?></p>
                    <p><strong>Página atual:</strong> <?= $page ?> de <?= $total_pages ?></p>
                </div>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">⚙️ Sistema</h3>
                <div style="font-size: 0.85rem; color: var(--text-light);">
                    <p>Última atualização: <?= date('d/m/Y H:i') ?></p>
                    <p>Atualização automática a cada hora</p>
                </div>
            </div>
            
            <?php if (!empty($settingsModel)): 
                $adSidebar = $settingsModel->getValue('ad_code_sidebar', '');
                if ($adSidebar): ?>
                <div class="sidebar-widget">
                    <h3 class="widget-title">📢 Publicidade</h3>
                    <?= $adSidebar ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </aside>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="footer-text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Todos os direitos reservados.</p>
            <p class="update-info">Notícias atualizadas automaticamente das melhores fontes.</p>
        </div>
    </footer>
</body>
</html>
