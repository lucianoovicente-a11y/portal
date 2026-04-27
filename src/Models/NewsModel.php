<?php
require_once __DIR__ . '/Database.php';

class NewsModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($category = null, $limit = 50) {
        $sql = "SELECT * FROM news WHERE 1=1";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY published_at DESC LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByCategory($category, $limit = 20) {
        return $this->getAll($category, $limit);
    }
    
    public function getLatest($limit = 10) {
        return $this->getAll(null, $limit);
    }
    
    public function search($query) {
        $sql = "SELECT * FROM news WHERE title LIKE ? OR description LIKE ? ORDER BY published_at DESC LIMIT 50";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function add($data) {
        try {
            $sql = "INSERT INTO news (title, description, content, link, image, source, category, published_at, is_manual) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['title'],
                $data['description'] ?? '',
                $data['content'] ?? '',
                $data['link'] ?? '',
                $data['image'] ?? '',
                $data['source'] ?? '',
                $data['category'],
                $data['published_at'] ?? date('Y-m-d H:i:s'),
                $data['is_manual'] ?? 0
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE') !== false) {
                return false; // Já existe
            }
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE news SET title = ?, description = ?, content = ?, image = ?, source = ?, category = ?, published_at = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['content'] ?? '',
            $data['image'] ?? '',
            $data['source'] ?? '',
            $data['category'],
            $data['published_at'] ?? date('Y-m-d H:i:s'),
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE news SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countByCategory() {
        $sql = "SELECT category, COUNT(*) as count FROM news GROUP BY category";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    public function getTotalCount() {
        return $this->db->query("SELECT COUNT(*) FROM news")->fetchColumn();
    }
}
