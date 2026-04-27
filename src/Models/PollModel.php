<?php
/**
 * Model para enquetes
 */

class PollModel {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obter enquete ativa
     */
    public function getActive(): ?array {
        $stmt = $this->db->query("SELECT * FROM polls WHERE is_active = 1 AND end_date > CURRENT_TIMESTAMP ORDER BY created_at DESC LIMIT 1");
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obter todas as enquetes
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM polls ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Obter enquete por ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM polls WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obter opções de uma enquete
     */
    public function getOptions(int $pollId): array {
        $stmt = $this->db->prepare("SELECT * FROM poll_options WHERE poll_id = :poll_id ORDER BY option_order");
        $stmt->execute([':poll_id' => $pollId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Criar enquete
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO polls (question, start_date, end_date, is_active, created_at)
            VALUES (:question, :start_date, :end_date, :is_active, CURRENT_TIMESTAMP)
        ");
        
        $stmt->execute([
            ':question' => $data['question'],
            ':start_date' => $data['start_date'] ?? date('Y-m-d H:i:s'),
            ':end_date' => $data['end_date'] ?? date('Y-m-d H:i:s', strtotime('+7 days')),
            ':is_active' => $data['is_active'] ?? 1
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Adicionar opção à enquete
     */
    public function addOption(int $pollId, string $optionText, int $order = 0): int {
        $stmt = $this->db->prepare("
            INSERT INTO poll_options (poll_id, option_text, option_order, created_at)
            VALUES (:poll_id, :option_text, :option_order, CURRENT_TIMESTAMP)
        ");
        
        $stmt->execute([
            ':poll_id' => $pollId,
            ':option_text' => $optionText,
            ':option_order' => $order
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Registrar voto
     */
    public function vote(int $optionId, string $userIp): bool {
        // Verificar se já votou
        $stmt = $this->db->prepare("SELECT id FROM poll_votes WHERE option_id = :option_id AND user_ip = :user_ip");
        $stmt->execute([
            ':option_id' => $optionId,
            ':user_ip' => $userIp
        ]);
        
        if ($stmt->fetch()) {
            return false; // Já votou
        }
        
        // Registrar voto
        $stmt = $this->db->prepare("INSERT INTO poll_votes (option_id, user_ip, voted_at) VALUES (:option_id, :user_ip, CURRENT_TIMESTAMP)");
        return $stmt->execute([
            ':option_id' => $optionId,
            ':user_ip' => $userIp
        ]);
    }
    
    /**
     * Contar votos de uma opção
     */
    public function countVotes(int $optionId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM poll_votes WHERE option_id = :option_id");
        $stmt->execute([':option_id' => $optionId]);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Atualizar enquete
     */
    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];
        
        $allowed = ['question', 'start_date', 'end_date', 'is_active'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE polls SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Excluir enquete
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM polls WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
