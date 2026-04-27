<?php
/**
 * Router simples para o portal
 */

class Router {
    private array $routes = [];
    
    public function addRoute(string $method, string $path, callable $handler): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover base path se existir
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                call_user_func($route['handler']);
                return;
            }
        }
        
        // Rota não encontrada
        http_response_code(404);
        echo "404 - Página não encontrada";
    }
    
    private function matchPath(string $pattern, string $uri): bool {
        // Converter padrão para regex
        $regex = str_replace('*', '.*', $pattern);
        return preg_match('#^' . $regex . '$#', $uri);
    }
}
