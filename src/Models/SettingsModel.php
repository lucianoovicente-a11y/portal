<?php
require_once __DIR__ . '/Database.php';

class SettingsModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function get($key) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : null;
    }
    
    public function set($key, $value) {
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO settings (key, value) VALUES (?, ?)");
        return $stmt->execute([$key, $value]);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT key, value FROM settings");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    public function updateBatch($data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }
}
