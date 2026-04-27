<?php
/**
 * Model para gerenciamento de notícias
 */

class NewsModel {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obter notícias com paginação e filtros
     */
    public function getNews(int $page = 1, int $limit = 20, ?string $source = null, ?string $search = null): array {
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
        $stmt = $this->db->prepare($sql);
        
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
    public function countNews(?string $source = null, ?string $search = null): int {
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
        $stmt = $this->db->prepare($sql);
        
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
    public function getSources(): array {
        $stmt = $this->db->query("SELECT DISTINCT source_name, category FROM news ORDER BY source_name");
        return $stmt->fetchAll();
    }
    
    /**
     * Salvar notícias no banco
     */
    public function saveNews(array $items): int {
        $count = 0;
        
        $stmt = $this->db->prepare("
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
     * Obter última notícia
     */
    public function getLatestNews(): ?array {
        $stmt = $this->db->query("SELECT * FROM news ORDER BY pub_date DESC LIMIT 1");
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Limpar notícias antigas
     */
    public function cleanOldNews(int $days = 30): int {
        $cutoff = date('Y-m-d H:i:s', strtotime("-$days days"));
        
        $stmt = $this->db->prepare("DELETE FROM news WHERE pub_date < :cutoff");
        $stmt->execute([':cutoff' => $cutoff]);
        
        return $stmt->rowCount();
    }
}
