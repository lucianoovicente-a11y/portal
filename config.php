<?php
/**
 * Configuração do Mega Portal de Notícias
 * Banco de dados SQLite e funções auxiliares
 */

// Configurações do banco de dados
define('DB_PATH', __DIR__ . '/data/portal.db');

// Fontes de notícias (RSS Feeds)
$news_sources = [
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

// Configurações gerais
define('SITE_NAME', 'Mega Portal de Notícias');
define('NEWS_PER_PAGE', 20);
define('CACHE_TIME', 300); // 5 minutos em segundos

/**
 * Conectar ao banco de dados SQLite
 */
function getDbConnection() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

/**
 * Inicializar o banco de dados
 */
function initializeDatabase() {
    $db = getDbConnection();
    
    // Criar tabela de notícias
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
    
    // Criar índice para busca
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_pub_date ON news(pub_date DESC)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_source ON news(source_name)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_category ON news(category)");
}

/**
 * Extrair imagem do conteúdo ou descrição
 */
function extractImage($content) {
    $patterns = [
        '/<img[^>]+src="([^"]+)"/i',
        '/<meta property="og:image" content="([^"]+)"/i',
        '/url\(([^)]+)\)/i'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }
    }
    
    return null;
}

/**
 * Buscar e processar feed RSS
 */
function fetchFeed($source) {
    $rss = @file_get_contents($source['url']);
    
    if (!$rss) {
        return [];
    }
    
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($rss);
    libxml_clear_errors();
    
    if (!$xml) {
        return [];
    }
    
    $items = [];
    
    // Suporte para diferentes formatos RSS
    $channel = isset($xml->channel) ? $xml->channel : $xml;
    $entries = isset($channel->item) ? $channel->item : (isset($channel->entry) ? $channel->entry : []);
    
    foreach ($entries as $item) {
        $title = (string)($item->title ?? '');
        $link = (string)($item->link ?? '');
        
        // Lidar com links complexos
        if (is_object($link) && isset($link['href'])) {
            $link = (string)$link['href'];
        } elseif (is_array($link)) {
            $link = $link[0] ?? '';
        }
        
        $description = (string)($item->description ?? $item->summary ?? '');
        $pubDate = (string)($item->pubDate ?? $item->published ?? date('r'));
        
        // Tentar extrair imagem
        $imageUrl = null;
        
        // Verificar media:content
        if (isset($item->children('media', true)->content)) {
            $media = $item->children('media', true)->content;
            $imageUrl = (string)$media['url'];
        }
        
        // Verificar enclosure
        if (!$imageUrl && isset($item->enclosure)) {
            $imageUrl = (string)$item->enclosure['url'];
        }
        
        // Extrair do conteúdo se não encontrou
        if (!$imageUrl && !empty($description)) {
            $imageUrl = extractImage($description);
        }
        
        // Converter data
        $timestamp = strtotime($pubDate);
        if ($timestamp === false) {
            $timestamp = time();
        }
        
        $items[] = [
            'title' => strip_tags($title),
            'description' => strip_tags($description),
            'link' => $link,
            'pub_date' => date('Y-m-d H:i:s', $timestamp),
            'source_name' => $source['name'],
            'source_url' => $source['url'],
            'category' => $source['category'],
            'image_url' => $imageUrl
        ];
    }
    
    return $items;
}

/**
 * Salvar notícias no banco de dados
 */
function saveNews($items) {
    $db = getDbConnection();
    $count = 0;
    
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO news (title, description, link, pub_date, source_name, source_url, category, image_url)
        VALUES (:title, :description, :link, :pub_date, :source_name, :source_url, :category, :image_url)
    ");
    
    foreach ($items as $item) {
        $stmt->execute([
            ':title' => $item['title'],
            ':description' => $item['description'],
            ':link' => $item['link'],
            ':pub_date' => $item['pub_date'],
            ':source_name' => $item['source_name'],
            ':source_url' => $item['source_url'],
            ':category' => $item['category'],
            ':image_url' => $item['image_url']
        ]);
        
        if ($stmt->rowCount() > 0) {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Obter notícias do banco de dados
 */
function getNews($page = 1, $limit = NEWS_PER_PAGE, $source = null, $search = null) {
    $db = getDbConnection();
    $offset = ($page - 1) * $limit;
    
    $where = [];
    $params = [];
    
    if ($source) {
        $where[] = "source_name = :source";
        $params[':source'] = $source;
    }
    
    if ($search) {
        $where[] = "(title LIKE :search OR description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    $sql = "SELECT * FROM news $whereClause ORDER BY pub_date DESC LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Contar total de notícias
 */
function countNews($source = null, $search = null) {
    $db = getDbConnection();
    
    $where = [];
    $params = [];
    
    if ($source) {
        $where[] = "source_name = :source";
        $params[':source'] = $source;
    }
    
    if ($search) {
        $where[] = "(title LIKE :search OR description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    $sql = "SELECT COUNT(*) as total FROM news $whereClause";
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $result = $stmt->fetch();
    
    return (int)$result['total'];
}

/**
 * Obter todas as fontes únicas
 */
function getSources() {
    $db = getDbConnection();
    $stmt = $db->query("SELECT DISTINCT source_name, category FROM news ORDER BY source_name");
    return $stmt->fetchAll();
}

/**
 * Limpar notícias antigas (opcional)
 */
function cleanOldNews($days = 30) {
    $db = getDbConnection();
    $cutoff = date('Y-m-d H:i:s', strtotime("-$days days"));
    
    $stmt = $db->prepare("DELETE FROM news WHERE pub_date < :cutoff");
    $stmt->execute([':cutoff' => $cutoff]);
    
    return $stmt->rowCount();
}

// Inicializar banco de dados ao carregar a config
initializeDatabase();
