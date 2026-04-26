<?php
/**
 * Mega Portal de Notícias - Página Principal
 * Interface responsiva e moderna em PHP puro
 */

require_once 'config.php';

// Parâmetros da requisição
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$source_filter = isset($_GET['source']) ? $_GET['source'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

// Obter notícias
$news = getNews($page, NEWS_PER_PAGE, $source_filter, $search);
$total_news = countNews($source_filter, $search);
$total_pages = ceil($total_news / NEWS_PER_PAGE);

// Obter fontes para o sidebar
$sources = getSources();

// Última notícia para destaque
$latest_news = !empty($news) ? $news[0] : null;

// Função auxiliar para formatar data
function formatDate($date) {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return 'Agora mesmo';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return "Há $mins minuto" . ($mins > 1 ? 's' : '');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Há $hours hora" . ($hours > 1 ? 's' : '');
    } else {
        return date('d/m/Y H:i', $timestamp);
    }
}

// Escapar HTML para segurança
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(SITE_NAME); ?> - As últimas notícias em tempo real</title>
    <meta name="description" content="Portal de notícias com atualização automática das principais fontes do Brasil e do mundo.">
    <style>
        :root {
            --primary-color: #c0392b;
            --primary-dark: #a93226;
            --secondary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-color: #f5f6fa;
            --card-bg: #ffffff;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #e1e8ed;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
            --shadow-hover: 0 5px 20px rgba(0,0,0,0.15);
            --radius: 8px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        
        .logo span {
            opacity: 0.9;
            font-weight: 400;
            font-size: 0.9em;
        }
        
        /* Search Form */
        .search-form {
            display: flex;
            gap: 0.5rem;
            flex: 1;
            max-width: 500px;
        }
        
        .search-input {
            flex: 1;
            padding: 0.7rem 1rem;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            outline: none;
        }
        
        .search-btn {
            padding: 0.7rem 1.5rem;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .search-btn:hover {
            background: #34495e;
        }
        
        /* Breaking News Banner */
        .breaking-news {
            background: var(--secondary-color);
            color: white;
            padding: 0.8rem 0;
            overflow: hidden;
        }
        
        .breaking-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .breaking-label {
            background: var(--primary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            white-space: nowrap;
        }
        
        .breaking-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }
        
        .breaking-text a:hover {
            text-decoration: underline;
        }
        
        /* Main Layout */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 20px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
        }
        
        @media (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
        
        /* News Grid */
        .news-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .news-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: grid;
            grid-template-columns: 280px 1fr;
        }
        
        .news-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-3px);
        }
        
        @media (max-width: 600px) {
            .news-card {
                grid-template-columns: 1fr;
            }
        }
        
        .news-image {
            width: 100%;
            height: 100%;
            min-height: 180px;
            object-fit: cover;
            background: #ecf0f1;
        }
        
        .news-placeholder {
            width: 100%;
            height: 100%;
            min-height: 180px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .news-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .news-source {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        
        .news-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            line-height: 1.3;
            color: var(--secondary-color);
        }
        
        .news-title a:hover {
            color: var(--primary-color);
        }
        
        .news-description {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: var(--text-light);
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        
        .news-time {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .news-link {
            color: var(--accent-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .news-link:hover {
            text-decoration: underline;
        }
        
        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .sidebar-widget {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        
        .widget-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            padding-bottom: 0.8rem;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .source-list {
            list-style: none;
        }
        
        .source-item {
            margin-bottom: 0.8rem;
        }
        
        .source-link {
            display: block;
            padding: 0.6rem 1rem;
            border-radius: var(--radius);
            transition: var(--transition);
            font-size: 0.9rem;
        }
        
        .source-link:hover,
        .source-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        .source-count {
            float: right;
            background: var(--bg-color);
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            color: var(--text-light);
        }
        
        .source-link:hover .source-count,
        .source-link.active .source-count {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .page-link {
            padding: 0.7rem 1.2rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            color: var(--text-color);
            font-weight: 600;
            transition: var(--transition);
        }
        
        .page-link:hover,
        .page-link.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Footer */
        footer {
            background: var(--secondary-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }
        
        .footer-text {
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .update-info {
            font-size: 0.85rem;
            opacity: 0.7;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .empty-text {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
            }
            
            .search-form {
                width: 100%;
                max-width: none;
            }
            
            .news-title {
                font-size: 1.2rem;
            }
        }
        
        /* Loading Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .loading {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo">
                📰 <?php echo SITE_NAME; ?>
                <span>- Notícias em tempo real</span>
            </div>
            <form class="search-form" method="GET" action="">
                <?php if ($source_filter): ?>
                    <input type="hidden" name="source" value="<?php echo e($source_filter); ?>">
                <?php endif; ?>
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Buscar notícias..." 
                    value="<?php echo e($search ?? ''); ?>"
                    aria-label="Buscar notícias"
                >
                <button type="submit" class="search-btn">🔍 Buscar</button>
            </form>
        </div>
    </header>

    <!-- Breaking News -->
    <?php if ($latest_news): ?>
    <div class="breaking-news">
        <div class="breaking-content">
            <span class="breaking-label">🔴 ÚLTIMA</span>
            <div class="breaking-text">
                <a href="<?php echo e($latest_news['link']); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo e($latest_news['title']); ?>
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
                        <a href="update_feeds.php" class="search-btn" style="display: inline-block;">🔄 Atualizar Agora</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($news as $item): ?>
                    <article class="news-card">
                        <?php if ($item['image_url']): ?>
                            <img 
                                src="<?php echo e($item['image_url']); ?>" 
                                alt="<?php echo e($item['title']); ?>"
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
                                <div class="news-source"><?php echo e($item['source_name']); ?></div>
                                <h2 class="news-title">
                                    <a href="<?php echo e($item['link']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo e($item['title']); ?>
                                    </a>
                                </h2>
                                <?php if ($item['description']): ?>
                                    <p class="news-description"><?php echo e($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="news-meta">
                                <span class="news-time">
                                    ⏰ <?php echo formatDate($item['pub_date']); ?>
                                </span>
                                <a href="<?php echo e($item['link']); ?>" target="_blank" rel="noopener noreferrer" class="news-link">
                                    Ler completa →
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav class="pagination" aria-label="Paginação">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $source_filter ? '&source=' . urlencode($source_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                               class="page-link">← Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == 1 || $i == $total_pages || abs($i - $page) <= 2): ?>
                                <a href="?page=<?php echo $i; ?><?php echo $source_filter ? '&source=' . urlencode($source_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                                   class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php elseif (abs($i - $page) == 3): ?>
                                <span class="page-link">...</span>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $source_filter ? '&source=' . urlencode($source_filter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                               class="page-link">Próxima →</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Filter by Source -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📑 Fontes</h3>
                <ul class="source-list">
                    <li class="source-item">
                        <a href="?" class="source-link <?php echo !$source_filter ? 'active' : ''; ?>">
                            Todas as fontes
                            <span class="source-count"><?php echo $total_news; ?></span>
                        </a>
                    </li>
                    <?php foreach ($sources as $src): ?>
                        <li class="source-item">
                            <a href="?source=<?php echo urlencode($src['source_name']); ?>" 
                               class="source-link <?php echo $source_filter === $src['source_name'] ? 'active' : ''; ?>">
                                <?php echo e($src['source_name']); ?>
                                <?php 
                                $count = countNews($src['source_name']);
                                if ($count > 0):
                                ?>
                                    <span class="source-count"><?php echo $count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Stats Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📊 Estatísticas</h3>
                <div style="font-size: 0.9rem; line-height: 2;">
                    <div><strong>Total de notícias:</strong> <?php echo number_format($total_news); ?></div>
                    <div><strong>Fontes ativas:</strong> <?php echo count($sources); ?></div>
                    <div><strong>Página atual:</strong> <?php echo $page; ?> de <?php echo $total_pages; ?></div>
                </div>
            </div>

            <!-- Info Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">ℹ️ Informações</h3>
                <p style="font-size: 0.9rem; color: var(--text-light);">
                    Este portal agrega notícias automaticamente de diversas fontes. 
                    Os feeds são atualizados a cada 5 minutos via cron job.
                </p>
                <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 1rem;">
                    <strong>Dica:</strong> Para atualizar manualmente, execute:<br>
                    <code style="background: #f1f1f1; padding: 0.3rem 0.5rem; border-radius: 4px; display: block; margin-top: 0.5rem;">php update_feeds.php</code>
                </p>
            </div>
        </aside>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p class="footer-text">
                © <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> - Todos os direitos reservados
            </p>
            <p class="update-info">
                Notícias atualizadas automaticamente das melhores fontes • PHP Puro + SQLite
            </p>
        </div>
    </footer>
</body>
</html>
