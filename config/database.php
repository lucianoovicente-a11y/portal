<?php
/**
 * Configuração do Portal de Notícias
 */

// Fontes RSS por categoria
define('RSS_FEEDS', [
    'nacional' => [
        ['name' => 'G1', 'url' => 'https://g1.globo.com/rss/g1/'],
        ['name' => 'UOL Notícias', 'url' => 'https://noticias.uol.com.br/cotidiano/ultimas-noticias.xml'],
        ['name' => 'R7', 'url' => 'https://feeds.r7.com.br/geral.xml'],
        ['name' => 'CNN Brasil', 'url' => 'https://www.cnnbrasil.com.br/feed/'],
        ['name' => 'Estadão', 'url' => 'https://feeds.estadao.com.br/estadao/rss/home.xml'],
        ['name' => 'Folha de S.Paulo', 'url' => 'https://feeds.folha.uol.com.br/emcimadahora/rss091.xml'],
        ['name' => 'O Globo', 'url' => 'https://oglobo.globo.com/rss/']
    ],
    'internacional' => [
        ['name' => 'BBC News', 'url' => 'http://feeds.bbci.co.uk/news/world/rss.xml'],
        ['name' => 'Reuters World', 'url' => 'https://www.reutersagency.com/feed/?best-topics=news&post_type=best'],
        ['name' => 'Deutsche Welle', 'url' => 'https://rss.dw.com/xml/rss-english-news'],
        ['name' => 'France 24', 'url' => 'https://www.france24.com/en/rss']
    ],
    'esportes' => [
        ['name' => 'Globo Esporte', 'url' => 'https://ge.globo.com/Esportes/rss.xml'],
        ['name' => 'ESPN', 'url' => 'https://www.espn.com.br/espn/rss/noticias'],
        ['name' => 'Lance!', 'url' => 'https://www.lance.com.br/rss'],
        ['name' => 'Gazeta Esportiva', 'url' => 'https://www.gazetaesportiva.net/rss/'],
        ['name' => 'Mercado da Bola', 'url' => 'https://mercadodobola.com/feed/']
    ],
    'transferencias' => [
        ['name' => '90min Transferências', 'url' => 'https://www.90min.com/posts/tag/transfers.rss'],
        ['name' => 'Goal Transferências', 'url' => 'https://www.goal.com/pt-br/feeds/news']
    ],
    'tecnologia' => [
        ['name' => 'TecMundo', 'url' => 'https://www.tecmundo.com.br/rss'],
        ['name' => 'Canaltech', 'url' => 'https://canaltech.com.br/rss/'],
        ['name' => 'Adrenaline', 'url' => 'https://adrenaline.com.br/feed/'],
        ['name' => 'Olhar Digital', 'url' => 'https://olhardigital.com.br/feed/']
    ],
    'ia' => [
        ['name' => 'VentureBeat AI', 'url' => 'https://venturebeat.com/category/ai/feed/'],
        ['name' => 'AI News', 'url' => 'https://artificialintelligence-news.com/feed/']
    ],
    'politica' => [
        ['name' => 'Poder360', 'url' => 'https://www.poder360.com.br/feed/'],
        ['name' => 'Congresso em Foco', 'url' => 'https://congressoemfoco.uol.com.br/feed/'],
        ['name' => 'Política Hoje', 'url' => 'https://www.politicahoje.com.br/feed/']
    ],
    'guerra' => [
        ['name' => 'BBC World', 'url' => 'http://feeds.bbci.co.uk/news/world/rss.xml'],
        ['name' => 'Al Jazeera', 'url' => 'https://www.aljazeera.com/xml/rss/all.xml'],
        ['name' => 'Reuters World', 'url' => 'https://www.reutersagency.com/feed/?best-topics=world-news&post_type=best']
    ],
    'gospel' => [
        ['name' => 'Gospel+', 'url' => 'https://www.gospelmais.com.br/feed'],
        ['name' => 'Guiame', 'url' => 'https://guiame.com.br/feed'],
        ['name' => 'CPAD News', 'url' => 'https://cpadnews.com.br/feed/']
    ]
]);

// Configurações do banco de dados SQLite
define('DB_PATH', __DIR__ . '/../data/portal.db');

// Configurações padrão do site
define('DEFAULT_CONFIG', [
    'site_name' => 'Mega Portal de Notícias',
    'site_logo' => '',
    'whatsapp' => '',
    'email' => '',
    'admin_user' => 'admin',
    'admin_password' => '132004',
    'ads_header' => '',
    'ads_sidebar' => '',
    'ads_footer' => '',
    'update_interval' => 3600 // 1 hora em segundos
]);

// Categorias disponíveis
define('CATEGORIES', [
    'nacional' => 'Nacional',
    'internacional' => 'Internacional',
    'esportes' => 'Esportes',
    'transferencias' => 'Janelas de Transferência',
    'tecnologia' => 'Tecnologia',
    'ia' => 'Inteligência Artificial',
    'politica' => 'Política',
    'guerra' => 'Guerra (Brasil e Mundo)',
    'gospel' => 'Gospel'
]);

// Timezone
date_default_timezone_set('America/Sao_Paulo');
