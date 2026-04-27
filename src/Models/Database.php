<?php
require_once __DIR__ . '/../../config/database.php';

class Database {
    private static $instance = null;
    private $db;
    
    private function __construct() {
        try {
            $this->db = new PDO('sqlite:' . DB_PATH);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die("Erro na conexão com banco de dados: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    private function createTables() {
        // Tabela de notícias
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS news (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                content TEXT,
                link TEXT UNIQUE,
                image TEXT,
                source TEXT,
                category TEXT NOT NULL,
                published_at DATETIME,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                is_manual INTEGER DEFAULT 0,
                views INTEGER DEFAULT 0
            )
        ");
        
        // Tabela de configurações
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS settings (
                key TEXT PRIMARY KEY,
                value TEXT
            )
        ");
        
        // Tabela de enquetes
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS polls (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                question TEXT NOT NULL,
                options TEXT NOT NULL,
                votes TEXT DEFAULT '{}',
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Tabela de estatísticas
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS statistics (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                event_type TEXT NOT NULL,
                event_data TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Inserir configurações padrão se não existirem
        $settings = $this->db->query("SELECT COUNT(*) FROM settings")->fetchColumn();
        if ($settings == 0) {
            foreach (DEFAULT_CONFIG as $key => $value) {
                $stmt = $this->db->prepare("INSERT INTO settings (key, value) VALUES (?, ?)");
                $stmt->execute([$key, $value]);
            }
        }
    }
}
