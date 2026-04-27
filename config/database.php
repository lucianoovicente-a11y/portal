<?php
/**
 * Configuração do Mega Portal de Notícias
 * Banco de dados SQLite e configurações gerais
 */

// Definir caminho base
define('BASE_DIR', dirname(__DIR__));
define('DATA_DIR', BASE_DIR . '/data');
define('DB_PATH', DATA_DIR . '/portal.db');

// Garantir que o diretório existe com permissões corretas
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}
chmod(DATA_DIR, 0777);

// Garantir que o arquivo DB existe e tem permissões corretas
if (!file_exists(DB_PATH)) {
    touch(DB_PATH);
}
chmod(DB_PATH, 0666);

// Configurações gerais
define('SITE_NAME', 'Mega Portal de Notícias');
define('NEWS_PER_PAGE', 24);
define('CACHE_TIME', 3600); // 1 hora em segundos

// Detectar automaticamente a URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$path = dirname($scriptName);
define('SITE_URL', $protocol . $host . ($path === '/' ? '' : rtrim($path, '/')));

// Categorias disponíveis
$CATEGORIES = [
    'nacional' => 'Nacional',
    'internacional' => 'Internacional',
    'esportes' => 'Esportes',
    'transferencias' => 'Janelas de Transferência',
    'tecnologia' => 'Tecnologia',
    'ia' => 'Inteligência Artificial',
    'politica' => 'Política',
    'guerra' => 'Guerra',
    'gospel' => 'Gospel'
];

// Fontes de notícias (RSS Feeds) organizadas por categoria
$NEWS_SOURCES = [
    // NACIONAL
    [
        'name' => 'G1 - Brasil',
        'url' => 'https://g1.globo.com/rss/g1/',
        'category' => 'nacional'
    ],
    [
        'name' => 'UOL Notícias',
        'url' => 'https://noticias.uol.com.br/rss/ultimas.xml',
        'category' => 'nacional'
    ],
    [
        'name' => 'R7 Notícias',
        'url' => 'https://www.r7.com/rss/noticias',
        'category' => 'nacional'
    ],
    [
        'name' => 'CNN Brasil',
        'url' => 'https://www.cnnbrasil.com.br/feed/',
        'category' => 'nacional'
    ],
    [
        'name' => 'Estadão',
        'url' => 'https://feeds.estadao.com.br/estadao/rss/home',
        'category' => 'nacional'
    ],
    [
        'name' => 'Folha de S.Paulo',
        'url' => 'https://www1.folha.uol.com.br/rss/emcimadahora.xml',
        'category' => 'nacional'
    ],
    [
        'name' => 'O Globo',
        'url' => 'https://oglobo.globo.com/rss/oglobo/',
        'category' => 'nacional'
    ],
    
    // INTERNACIONAL
    [
        'name' => 'BBC News Brasil',
        'url' => 'https://feeds.bbci.co.uk/portuguese/rss.xml',
        'category' => 'internacional'
    ],
    [
        'name' => 'Reuters Brasil',
        'url' => 'https://www.reutersagency.com/feed/?best-topics=news&post_type=best',
        'category' => 'internacional'
    ],
    [
        'name' => 'Deutsche Welle',
        'url' => 'https://rss.dw.com/xml/rss-portuguese-news',
        'category' => 'internacional'
    ],
    [
        'name' => 'France 24',
        'url' => 'https://www.france24.com/pt/rss',
        'category' => 'internacional'
    ],
    
    // ESPORTES
    [
        'name' => 'Globo Esporte',
        'url' => 'https://globoesporte.globo.com/rss/ge/',
        'category' => 'esportes'
    ],
    [
        'name' => 'ESPN Brasil',
        'url' => 'https://www.espn.com.br/rss',
        'category' => 'esportes'
    ],
    [
        'name' => 'Lance!',
        'url' => 'https://www.lance.com.br/rss',
        'category' => 'esportes'
    ],
    [
        'name' => 'Gazeta Esportiva',
        'url' => 'https://www.gazetaesportiva.com/rss/',
        'category' => 'esportes'
    ],
    
    // TRANSFERÊNCIAS
    [
        'name' => 'Mercado da Bola',
        'url' => 'https://www.mercadodabola.com/rss',
        'category' => 'transferencias'
    ],
    [
        'name' => '90min Transferências',
        'url' => 'https://www.90min.com/pt-BR/tags/transferencias/feed',
        'category' => 'transferencias'
    ],
    
    // TECNOLOGIA
    [
        'name' => 'TecMundo',
        'url' => 'https://www.tecmundo.com.br/rss',
        'category' => 'tecnologia'
    ],
    [
        'name' => 'Canaltech',
        'url' => 'https://canaltech.com.br/rss/',
        'category' => 'tecnologia'
    ],
    [
        'name' => 'Adrenaline',
        'url' => 'https://adrenaline.com.br/feed/',
        'category' => 'tecnologia'
    ],
    [
        'name' => 'Olhar Digital',
        'url' => 'https://olhardigital.com.br/feed/',
        'category' => 'tecnologia'
    ],
    
    // INTELIGÊNCIA ARTIFICIAL
    [
        'name' => 'AI News',
        'url' => 'https://artificialintelligence-news.com/feed/',
        'category' => 'ia'
    ],
    [
        'name' => 'VentureBeat AI',
        'url' => 'https://venturebeat.com/category/ai/feed/',
        'category' => 'ia'
    ],
    
    // POLÍTICA
    [
        'name' => 'Poder360',
        'url' => 'https://www.poder360.com.br/feed/',
        'category' => 'politica'
    ],
    [
        'name' => 'Congresso em Foco',
        'url' => 'https://congressoemfoco.uol.com.br/feed/',
        'category' => 'politica'
    ],
    [
        'name' => 'Política Hoje',
        'url' => 'https://www.politicahoje.com.br/feed/',
        'category' => 'politica'
    ],
    
    // GUERRA
    [
        'name' => 'BBC World',
        'url' => 'https://feeds.bbci.co.uk/news/world/rss.xml',
        'category' => 'guerra'
    ],
    [
        'name' => 'Al Jazeera',
        'url' => 'https://www.aljazeera.com/xml/rss/all.xml',
        'category' => 'guerra'
    ],
    [
        'name' => 'Reuters World',
        'url' => 'https://feeds.reuters.com/reuters/worldNews',
        'category' => 'guerra'
    ],
    
    // GOSPEL
    [
        'name' => 'Gospel+ Notícias',
        'url' => 'https://www.gospelmais.com.br/feed',
        'category' => 'gospel'
    ],
    [
        'name' => 'Guiame',
        'url' => 'https://guiame.com.br/feed',
        'category' => 'gospel'
    ],
    [
        'name' => 'CPAD News',
        'url' => 'https://cpadnews.com.br/feed/',
        'category' => 'gospel'
    ]
];

/**
 * Obter conexão com banco de dados
 */
function getDbConnection(): PDO {
    static $db = null;
    
    if ($db === null) {
        try {
            $db = new PDO('sqlite:' . DB_PATH);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    return $db;
}

/**
 * Inicializar o banco de dados
 */
function initializeDatabase(): void {
    $db = getDbConnection();
    
    // Tabela de notícias
    $db->exec("
        CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            link TEXT UNIQUE NOT NULL,
            pub_date DATETIME,
            source_name TEXT,
            source_url TEXT,
            category TEXT,
            image_url TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_pub_date ON news(pub_date DESC)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_source ON news(source_name)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_category ON news(category)");
    
    // Tabela de configurações
    $db->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT UNIQUE NOT NULL,
            setting_value TEXT,
            setting_section TEXT DEFAULT 'general',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $db->exec("CREATE INDEX IF NOT EXISTS idx_settings_key ON settings(setting_key)");
    
    // Tabela de enquetes
    $db->exec("
        CREATE TABLE IF NOT EXISTS polls (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            question TEXT NOT NULL,
            start_date DATETIME,
            end_date DATETIME,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabela de opções de enquete
    $db->exec("
        CREATE TABLE IF NOT EXISTS poll_options (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            poll_id INTEGER NOT NULL,
            option_text TEXT NOT NULL,
            option_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE
        )
    ");
    
    // Tabela de votos
    $db->exec("
        CREATE TABLE IF NOT EXISTS poll_votes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            option_id INTEGER NOT NULL,
            user_ip TEXT NOT NULL,
            voted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (option_id) REFERENCES poll_options(id) ON DELETE CASCADE
        )
    ");
    
    // Inserir configurações padrão se não existirem
    $defaultSettings = [
        ['site_name', SITE_NAME, 'general'],
        ['whatsapp', '', 'contact'],
        ['email', '', 'contact'],
        ['admin_username', 'admin', 'admin'],
        ['admin_password', 'admin123', 'admin'],
        ['ad_code_header', '', 'advertising'],
        ['ad_code_sidebar', '', 'advertising'],
        ['ad_code_footer', '', 'advertising']
    ];
    
    foreach ($defaultSettings as $setting) {
        $db->exec("INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_section) VALUES ('{$setting[0]}', '{$setting[1]}', '{$setting[2]}')");
    }
}

// Inicializar banco de dados
initializeDatabase();
