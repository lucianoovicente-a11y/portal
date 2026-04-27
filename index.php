<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Models/Database.php';
require_once __DIR__ . '/src/Models/NewsModel.php';
require_once __DIR__ . '/src/Models/SettingsModel.php';
require_once __DIR__ . '/src/Models/PollModel.php';

$settingsModel = new SettingsModel();
$newsModel = new NewsModel();
$pollModel = new PollModel();

$config = $settingsModel->getAll();
$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;

if ($search) {
    $news = $newsModel->search($search);
} elseif ($category && isset(CATEGORIES[$category])) {
    $news = $newsModel->getByCategory($category, 30);
} else {
    $news = $newsModel->getLatest(50);
}

$activePoll = $pollModel->getActive();
$categoryCounts = $newsModel->countByCategory();

// Se não houver notícias, redireciona para atualização
if (empty($news) && !isset($_GET['skip_update'])) {
    header('Location: update_feeds.php?redirect=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['site_name'] ?? 'Portal de Notícias') ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/src/Views/partials/header.php'; ?>
    
    <div class="container main-content">
        <aside class="sidebar">
            <?php include __DIR__ . '/src/Views/partials/sidebar.php'; ?>
        </aside>
        
        <main class="content">
            <?php if ($category && isset(CATEGORIES[$category])): ?>
                <h2 class="category-title"><?= CATEGORIES[$category] ?></h2>
            <?php elseif ($search): ?>
                <h2 class="category-title">Resultados para: <?= htmlspecialchars($search) ?></h2>
            <?php else: ?>
                <h2 class="category-title">Últimas Notícias</h2>
            <?php endif; ?>
            
            <div class="news-grid">
                <?php foreach ($news as $item): ?>
                    <article class="news-card">
                        <?php if (!empty($item['image'])): ?>
                            <div class="news-image">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy" onerror="this.parentElement.classList.add('no-image'); this.style.display='none'; this.parentElement.innerHTML='📰'">
                            </div>
                        <?php else: ?>
                            <div class="news-image no-image">📰</div>
                        <?php endif; ?>
                        <div class="news-content">
                            <span class="news-category"><?= CATEGORIES[$item['category']] ?? $item['category'] ?></span>
                            <h3><a href="<?= htmlspecialchars($item['link']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($item['title']) ?></a></h3>
                            <p class="news-description"><?= htmlspecialchars(substr(strip_tags($item['description']), 0, 150)) ?>...</p>
                            <div class="news-meta">
                                <span class="news-source"><?= htmlspecialchars($item['source']) ?></span>
                                <span class="news-date"><?= date('d/m/Y H:i', strtotime($item['published_at'])) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($news)): ?>
                <p class="no-news">Nenhuma notícia encontrada.</p>
            <?php endif; ?>
        </main>
    </div>
    
    <?php include __DIR__ . '/src/Views/partials/footer.php'; ?>
</body>
</html>
