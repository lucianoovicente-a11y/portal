<?php
/**
 * Mega Portal de Notícias - PHP Puro + SQLite
 * Sistema de agregação automática de notícias
 */

// Configurações do banco de dados
define('DB_PATH', __DIR__ . '/data/news.db');

// Fontes de notícias (RSS Feeds)
$news_sources = [
    'G1' => 'https://g1.globo.com/rss/g1/',
    'UOL Notícias' => 'https://rss.uol.com.br/feed/noticias.xml',
    'R7' => 'https://feeds.r7.com.br/r7-com-br-geral',
    'Terra' => 'https://www.terra.com.br/rss/noticias/',
    'BBC Brasil' => 'https://feeds.bbci.co.uk/portuguese/rss.xml',
    'CNN Brasil' => 'https://www.cnnbrasil.com.br/feed/',
    'Estadão' => 'https://feeds.estadao.com.br/estadao/internacional',
    'Folha de S.Paulo' => 'https://rss.folha.uol.com.br/emcimadahora/rss.xml',
    'O Globo' => 'https://oglobo.globo.com/rss/oglobo/',
    'Reuters' => 'https://feeds.reuters.com/reuters/topNews'
];

// Criar diretório de dados se não existir
if (!file_exists(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

// Conectar ao SQLite
function getDB() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}

// Inicializar banco de dados
function initDatabase($db) {
    $db->exec("
        CREATE TABLE IF NOT EXISTS sources (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL,
            url TEXT NOT NULL,
            active INTEGER DEFAULT 1,
            last_update DATETIME
        )
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            content TEXT,
            link TEXT UNIQUE NOT NULL,
            image_url TEXT,
            source_id INTEGER,
            category TEXT,
            published_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (source_id) REFERENCES sources(id)
        )
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        )
    ");

    // Índices para performance
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_source ON news(source_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_published ON news(published_at DESC)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_news_category ON news(category)");
}

// Parse RSS Feed
function parseRSSFeed($url) {
    $items = [];
    
    try {
        $xml = @simplexml_load_file($url);
        
        if (!$xml) {
            return $items;
        }

        $channel = $xml->channel;
        
        if (!$channel) {
            return $items;
        }

        foreach ($channel->item as $item) {
            $news_item = [
                'title' => (string)$item->title,
                'description' => (string)$item->description,
                'link' => (string)$item->link,
                'published_at' => isset($item->pubDate) ? (string)$item->pubDate : date('Y-m-d H:i:s'),
                'content' => '',
                'image_url' => ''
            ];

            // Tentar extrair conteúdo
            $namespaces = $item->getNamespaces(true);
            
            if (isset($namespaces['content'])) {
                $content = $item->children($namespaces['content']);
                $news_item['content'] = (string)$content->encoded;
            }

            if (isset($namespaces['media'])) {
                $media = $item->children($namespaces['media']);
                if (isset($media->content)) {
                    $attributes = $media->content->attributes();
                    $news_item['image_url'] = (string)$attributes['url'];
                } elseif (isset($media->thumbnail)) {
                    $attributes = $media->thumbnail->attributes();
                    $news_item['image_url'] = (string)$attributes['url'];
                }
            }

            // Tentar encontrar imagem no description
            if (empty($news_item['image_url']) && preg_match('/<img[^>]+src="([^"]+)"/', $news_item['description'], $matches)) {
                $news_item['image_url'] = $matches[1];
            }

            // Limpar HTML do description
            $news_item['description'] = strip_tags($news_item['description']);
            
            $items[] = $news_item;
        }
    } catch (Exception $e) {
        error_log("Erro ao parsear feed $url: " . $e->getMessage());
    }

    return $items;
}

// Salvar fonte no banco
function saveSource($db, $name, $url) {
    try {
        $stmt = $db->prepare("INSERT OR IGNORE INTO sources (name, url) VALUES (:name, :url)");
        $stmt->execute([':name' => $name, ':url' => $url]);
        
        $stmt = $db->prepare("UPDATE sources SET last_update = CURRENT_TIMESTAMP WHERE name = :name");
        $stmt->execute([':name' => $name]);
        
        return $db->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erro ao salvar fonte: " . $e->getMessage());
        return false;
    }
}

// Salvar notícia no banco
function saveNews($db, $news, $source_id) {
    try {
        $stmt = $db->prepare("
            INSERT OR IGNORE INTO news 
            (title, description, content, link, image_url, source_id, published_at) 
            VALUES (:title, :description, :content, :link, :image_url, :source_id, :published_at)
        ");
        
        $stmt->execute([
            ':title' => $news['title'],
            ':description' => substr($news['description'], 0, 500),
            ':content' => $news['content'],
            ':link' => $news['link'],
            ':image_url' => $news['image_url'],
            ':source_id' => $source_id,
            ':published_at' => date('Y-m-d H:i:s', strtotime($news['published_at']))
        ]);
        
        return $db->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erro ao salvar notícia: " . $e->getMessage());
        return false;
    }
}

// Atualizar todas as fontes
function updateAllFeeds($db, $sources) {
    $total_news = 0;
    
    foreach ($sources as $name => $url) {
        echo "Atualizando: $name...\n";
        
        $source_id = saveSource($db, $name, $url);
        
        if (!$source_id) {
            $stmt = $db->prepare("SELECT id FROM sources WHERE name = :name");
            $stmt->execute([':name' => $name]);
            $source_id = $stmt->fetchColumn();
        }
        
        $items = parseRSSFeed($url);
        
        foreach ($items as $item) {
            if (saveNews($db, $item, $source_id)) {
                $total_news++;
            }
        }
        
        echo "  -> " . count($items) . " notícias encontradas\n";
    }
    
    return $total_news;
}

// Buscar notícias do banco
function getNews($db, $limit = 20, $offset = 0, $source_id = null, $category = null) {
    $sql = "
        SELECT n.*, s.name as source_name, s.url as source_url
        FROM news n
        JOIN sources s ON n.source_id = s.id
        WHERE 1=1
    ";
    
    $params = [];
    
    if ($source_id) {
        $sql .= " AND n.source_id = :source_id";
        $params[':source_id'] = $source_id;
    }
    
    if ($category) {
        $sql .= " AND n.category = :category";
        $params[':category'] = $category;
    }
    
    $sql .= " ORDER BY n.published_at DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar todas as fontes
function getSources($db, $active_only = true) {
    $sql = "SELECT * FROM sources";
    
    if ($active_only) {
        $sql .= " WHERE active = 1";
    }
    
    $sql .= " ORDER BY name";
    
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Contar total de notícias
function countNews($db, $source_id = null) {
    $sql = "SELECT COUNT(*) FROM news WHERE 1=1";
    
    if ($source_id) {
        $sql .= " AND source_id = :source_id";
    }
    
    $stmt = $db->prepare($sql);
    
    if ($source_id) {
        $stmt->execute([':source_id' => $source_id]);
    } else {
        $stmt->execute();
    }
    
    return $stmt->fetchColumn();
}

// Função para formatação de data
function formatDate($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Agora mesmo';
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "Há $minutes minuto(s)";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Há $hours hora(s)";
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return "Há $days dia(s)";
    } else {
        return date('d/m/Y H:i', $timestamp);
    }
}

// Se executado diretamente, atualiza os feeds
if (php_sapi_name() === 'cli' && isset($argv[0]) && basename($argv[0]) === 'update_feeds.php') {
    echo "=== Mega Portal de Notícias ===\n";
    echo "Iniciando atualização dos feeds...\n\n";
    
    $db = getDB();
    initDatabase($db);
    
    $total = updateAllFeeds($db, $news_sources);
    
    echo "\n=== Atualização concluída ===\n";
    echo "Total de notícias processadas: $total\n";
    echo "Data: " . date('d/m/Y H:i:s') . "\n";
}
