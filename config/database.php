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
define('NEWS_PER_PAGE', 20);
define('CACHE_TIME', 300); // 5 minutos em segundos

// Detectar automaticamente a URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$path = dirname($scriptName);
define('SITE_URL', $protocol . $host . ($path === '/' ? '' : rtrim($path, '/')));

// Fontes de notícias (RSS Feeds)
$NEWS_SOURCES = [
    [
        'name' => 'G1 - Últimas Notícias',
        'url' => 'https://g1.globo.com/rss/g1/',
        'category' => 'Geral'
    ],
    [
        'name' => 'UOL Notícias',
        'url' => 'https://noticias.uol.com.br/rss/ultimas.xml',
        'category' => 'Geral'
    ],
    [
        'name' => 'R7 Notícias',
        'url' => 'https://www.r7.com/rss/noticias',
        'category' => 'Geral'
    ],
    [
        'name' => 'Terra Notícias',
        'url' => 'https://www.terra.com.br/rss/noticias/',
        'category' => 'Geral'
    ],
    [
        'name' => 'BBC News Brasil',
        'url' => 'https://feeds.bbci.co.uk/portuguese/rss.xml',
        'category' => 'Internacional'
    ],
    [
        'name' => 'CNN Brasil',
        'url' => 'https://www.cnnbrasil.com.br/feed/',
        'category' => 'Geral'
    ],
    [
        'name' => 'Estadão',
        'url' => 'https://feeds.estadao.com.br/estadao/rss/home',
        'category' => 'Geral'
    ],
    [
        'name' => 'Folha de S.Paulo',
        'url' => 'https://www1.folha.uol.com.br/rss/emcimadahora.xml',
        'category' => 'Geral'
    ],
    [
        'name' => 'O Globo',
        'url' => 'https://oglobo.globo.com/rss/oglobo/',
        'category' => 'Geral'
    ],
    [
        'name' => 'Reuters Brasil',
        'url' => 'https://www.reutersagency.com/feed/?best-topics=news&post_type=best',
        'category' => 'Internacional'
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
}

// Inicializar banco de dados
initializeDatabase();
