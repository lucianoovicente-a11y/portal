<?php
/**
 * Funções utilitárias
 */

class Helpers {
    /**
     * Escapar HTML para segurança
     */
    public static function escape(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Formatar data relativa
     */
    public static function formatDate(string $date): string {
        $timestamp = strtotime($date);
        $now = time();
        $diff = $now - $timestamp;
        
        if ($diff < 60) {
            return 'Agora mesmo';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return "Há $mins minuto" . ($mins > 1 ? 's' : '');
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "Há $hours hora" . ($hours > 1 ? 's' : '');
        } else {
            return date('d/m/Y H:i', $timestamp);
        }
    }
    
    /**
     * Sanitizar input do usuário
     */
    public static function sanitizeInput(string $input): string {
        return trim(strip_tags($input));
    }
    
    /**
     * Obter parâmetro GET sanitizado
     */
    public static function getParam(string $name, $default = null) {
        if (!isset($_GET[$name])) {
            return $default;
        }
        
        return self::sanitizeInput($_GET[$name]);
    }
    
    /**
     * Obter parâmetro POST sanitizado
     */
    public static function postParam(string $name, $default = null) {
        if (!isset($_POST[$name])) {
            return $default;
        }
        
        return self::sanitizeInput($_POST[$name]);
    }
    
    /**
     * Redirecionar para URL
     */
    public static function redirect(string $url): void {
        header("Location: $url");
        exit;
    }
    
    /**
     * Renderizar view
     */
    public static function render(string $view, array $data = []): void {
        extract($data);
        include BASE_DIR . '/src/Views/' . $view . '.php';
    }
}
