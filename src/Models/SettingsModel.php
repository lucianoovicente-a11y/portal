<?php
/**
 * Model para configurações do portal
 */

class SettingsModel {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obter todas as configurações
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM settings ORDER BY setting_key");
        return $stmt->fetchAll();
    }
    
    /**
     * Obter uma configuração por chave
     */
    public function get(string $key): ?array {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE setting_key = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obter valor de uma configuração
     */
    public function getValue(string $key, $default = null) {
        $setting = $this->get($key);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    /**
     * Atualizar ou criar uma configuração
     */
    public function set(string $key, string $value): bool {
        $existing = $this->get($key);
        
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE settings SET setting_value = :value, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key");
            return $stmt->execute([
                ':key' => $key,
                ':value' => $value
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (:key, :value, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            return $stmt->execute([
                ':key' => $key,
                ':value' => $value
            ]);
        }
    }
    
    /**
     * Atualizar múltiplas configurações
     */
    public function setMultiple(array $settings): int {
        $count = 0;
        foreach ($settings as $key => $value) {
            if ($this->set($key, $value)) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Obter configurações da seção
     */
    public function getBySection(string $section): array {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE setting_section = :section ORDER BY setting_key");
        $stmt->execute([':section' => $section]);
        return $stmt->fetchAll();
    }
}
