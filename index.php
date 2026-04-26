<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Portal de Notícias - Todas as notícias em um só lugar</title>
    <meta name="description" content="Portal agregador de notícias dos principais portais do Brasil e do mundo">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #c4170c;
            --secondary-color: #1a1a2e;
            --accent-color: #0f3460;
            --text-color: #333;
            --light-bg: #f5f5f5;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            padding: 1rem 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
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
            font-size: 2rem;
            font-weight: bold;
            text-decoration: none;
            color: var(--white);
        }

        .logo span {
            color: #ffcc00;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
        }

        .search-form input {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            min-width: 250px;
        }

        .search-form button {
            padding: 0.5rem 1.5rem;
            background-color: var(--accent-color);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-form button:hover {
            background-color: var(--primary-color);
        }

        /* Main Content */
        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 20px;
        }

        /* Breaking News Banner */
        .breaking-news {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .breaking-news-label {
            background-color: var(--white);
            color: var(--primary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 3px;
            font-weight: bold;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .breaking-news-text {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Filters */
        .filters {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .filter-group select {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .filter-group select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        /* News Grid */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .news-card {
            background: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #ddd;
        }

        .news-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-source {
            color: var(--primary-color);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--secondary-color);
            line-height: 1.4;
        }

        .news-title a {
            color: inherit;
            text-decoration: none;
        }

        .news-title a:hover {
            color: var(--primary-color);
        }

        .news-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            flex: 1;
        }

        .news-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: #888;
        }

        .news-time {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .read-more {
            display: inline-block;
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.7rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
            text-align: center;
            margin-top: 1rem;
        }

        .read-more:hover {
            background-color: var(--accent-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.7rem 1.2rem;
            background: var(--white);
            border: 2px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 600;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .pagination .current {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        /* Sidebar */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        @media (max-width: 1024px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .sidebar-widget {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .widget-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--secondary-color);
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }

        .source-list {
            list-style: none;
        }

        .source-list li {
            margin-bottom: 0.8rem;
        }

        .source-list a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .source-list a:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .source-count {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.2rem 0.6rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .most-read-list {
            list-style: none;
            counter-reset: news-counter;
        }

        .most-read-list li {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 1rem;
        }

        .most-read-list li::before {
            counter-increment: news-counter;
            content: counter(news-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .most-read-list a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 600;
            line-height: 1.4;
        }

        .most-read-list a:hover {
            color: var(--primary-color);
        }

        /* Footer */
        footer {
            background: var(--secondary-color);
            color: var(--white);
            padding: 3rem 0 1.5rem;
            margin-top: 3rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #ffcc00;
        }

        .footer-section p,
        .footer-section a {
            color: #ccc;
            line-height: 1.8;
            text-decoration: none;
            display: block;
        }

        .footer-section a:hover {
            color: var(--white);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #888;
        }

        /* Stats Bar */
        .stats-bar {
            background: var(--white);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 3rem;
            color: #888;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }

            nav ul {
                justify-content: center;
            }

            .search-form {
                width: 100%;
            }

            .search-form input {
                width: 100%;
                min-width: auto;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .breaking-news {
                flex-direction: column;
                text-align: center;
            }
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .no-results h2 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .no-results p {
            color: #666;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/config.php';

    $db = getDB();
    initDatabase($db);

    // Parâmetros de paginação e filtro
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;

    $source_filter = isset($_GET['source']) ? (int)$_GET['source'] : null;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Buscar fontes
    $sources = getSources($db);

    // Contar total de notícias
    $total_news = countNews($db, $source_filter);
    $total_pages = ceil($total_news / $per_page);

    // Buscar notícias
    $news_list = getNews($db, $per_page, $offset, $source_filter);

    // Notícia de destaque (mais recente)
    $breaking_news = !empty($news_list) ? $news_list[0] : null;
    ?>

    <header>
        <div class="header-container">
            <a href="/" class="logo">Mega<span>Portal</span></a>
            
            <nav>
                <ul>
                    <li><a href="/">Início</a></li>
                    <li><a href="/?source=">Todas as Fontes</a></li>
                    <?php foreach(array_slice($sources, 0, 5) as $src): ?>
                        <li><a href="/?source=<?= $src['id'] ?>"><?= htmlspecialchars($src['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <form class="search-form" method="GET" action="/">
                <input type="text" name="search" placeholder="Buscar notícias..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </header>

    <main>
        <?php if ($breaking_news): ?>
        <div class="breaking-news">
            <span class="breaking-news-label">ÚLTIMA</span>
            <span class="breaking-news-text"><?= htmlspecialchars($breaking_news['title']) ?></span>
        </div>
        <?php endif; ?>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?= number_format($total_news) ?></div>
                <div class="stat-label">Notícias</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($sources) ?></div>
                <div class="stat-label">Fontes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= date('H:i') ?></div>
                <div class="stat-label">Atualizado em</div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="main-content">
                <div class="filters">
                    <div class="filter-group">
                        <label for="source-filter">Filtrar por fonte:</label>
                        <select id="source-filter" onchange="window.location.href='/?source='+this.value">
                            <option value="">Todas as fontes</option>
                            <?php foreach($sources as $src): ?>
                                <option value="<?= $src['id'] ?>" <?= $source_filter == $src['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($src['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if (empty($news_list)): ?>
                <div class="no-results">
                    <h2>Nenhuma notícia encontrada</h2>
                    <p>Tente executar o script de atualização ou verifique sua conexão com a internet.</p>
                    <p><strong>Para atualizar as notícias, execute no terminal:</strong></p>
                    <code style="background: #f0f0f0; padding: 1rem; display: block; margin: 1rem 0; border-radius: 5px;">
                        php update_feeds.php
                    </code>
                </div>
                <?php else: ?>
                <div class="news-grid">
                    <?php foreach($news_list as $news): ?>
                    <article class="news-card">
                        <?php if ($news['image_url']): ?>
                        <img src="<?= htmlspecialchars($news['image_url']) ?>" 
                             alt="<?= htmlspecialchars($news['title']) ?>" 
                             class="news-image"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ESem imagem%3C/text%3E%3C/svg%3E'">
                        <?php else: ?>
                        <div class="news-image" style="display:flex;align-items:center;justify-content:center;background:#ddd;color:#999;">
                            Sem imagem
                        </div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <div class="news-source"><?= htmlspecialchars($news['source_name']) ?></div>
                            <h2 class="news-title">
                                <a href="<?= htmlspecialchars($news['link']) ?>" target="_blank" rel="noopener noreferrer">
                                    <?= htmlspecialchars($news['title']) ?>
                                </a>
                            </h2>
                            <p class="news-description">
                                <?= htmlspecialchars(substr($news['description'], 0, 150)) ?>...
                            </p>
                            <div class="news-meta">
                                <span class="news-time">🕐 <?= formatDate($news['published_at']) ?></span>
                            </div>
                            <a href="<?= htmlspecialchars($news['link']) ?>" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="read-more">Ler matéria completa →</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?><?= $source_filter ? '&source='.$source_filter : '' ?>">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current"><?= $i ?></span>
                        <?php elseif ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                            <a href="?page=<?= $i ?><?= $source_filter ? '&source='.$source_filter : '' ?>"><?= $i ?></a>
                        <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?><?= $source_filter ? '&source='.$source_filter : '' ?>">Próxima &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <aside class="sidebar">
                <div class="sidebar-widget">
                    <h3 class="widget-title">Fontes de Notícias</h3>
                    <ul class="source-list">
                        <?php foreach($sources as $src): 
                            $count = countNews($db, $src['id']);
                        ?>
                        <li>
                            <a href="/?source=<?= $src['id'] ?>">
                                <span><?= htmlspecialchars($src['name']) ?></span>
                                <span class="source-count"><?= $count ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Mais Recentes</h3>
                    <ul class="most-read-list">
                        <?php 
                        $recent = getNews($db, 5, 0, $source_filter);
                        foreach($recent as $item): 
                        ?>
                        <li>
                            <a href="<?= htmlspecialchars($item['link']) ?>" target="_blank">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Sobre</h3>
                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">
                        O Mega Portal agrega notícias automaticamente dos principais portais do Brasil e do mundo. 
                        Nosso sistema atualiza a cada 5 minutos para trazer sempre as últimas informações.
                    </p>
                    <p style="margin-top: 1rem; color: #666; font-size: 0.9rem;">
                        <strong>Última atualização:</strong><br>
                        <?= date('d/m/Y H:i:s') ?>
                    </p>
                </div>
            </aside>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Mega Portal de Notícias</h3>
                <p>Agregador automático de notícias em tempo real dos principais portais nacionais e internacionais.</p>
            </div>

            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <a href="/">Início</a>
                <a href="/?source=">Todas as Fontes</a>
                <a href="#">Política de Privacidade</a>
                <a href="#">Termos de Uso</a>
            </div>

            <div class="footer-section">
                <h3>Fontes Parceiras</h3>
                <?php foreach(array_slice($sources, 0, 5) as $src): ?>
                    <a href="/?source=<?= $src['id'] ?>"><?= htmlspecialchars($src['name']) ?></a>
                <?php endforeach; ?>
            </div>

            <div class="footer-section">
                <h3>Atualização Automática</h3>
                <p>Configure o cron para atualizações automáticas:</p>
                <code style="background: rgba(255,255,255,0.1); padding: 0.5rem; display: block; margin: 0.5rem 0; border-radius: 3px; font-size: 0.85rem;">
                    */5 * * * * php /path/update_feeds.php
                </code>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Mega Portal de Notícias. Todos os direitos reservados.</p>
            <p>Desenvolvido em PHP Puro + SQLite</p>
        </div>
    </footer>
</body>
</html>
