<?php
namespace App\Core;

class Router {
    protected $routes = [];
    
    public function add($method, $uri, $controller) {
        $this->routes[] = [
            'method' => $method,
            'uri' => '/' . trim($uri, '/'),
            'controller' => $controller
        ];
    }
    
    public function handle($method, $uri) {
        $uri = '/' . trim(explode('?', $uri)[0], '/');
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                $parts = explode('@', $route['controller']);
                if (count($parts) !== 2) {
                    header("HTTP/1.0 500 Internal Server Error");
                    echo "<h1>500 - Server Error</h1>";
                    return;
                }
                
                $controllerName = "App\\Controllers\\" . $parts[0];
                $methodName = $parts[1];
                
                if (!class_exists($controllerName)) {
                    header("HTTP/1.0 500 Internal Server Error");
                    echo "<h1>500 - Controller not found</h1>";
                    return;
                }
                
                try {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName();
                        return;
                    }
                } catch (\Exception $e) {
                    header("HTTP/1.0 500 Internal Server Error");
                    echo "<h1>500 - Error</h1>";
                    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                    return;
                }
            }
        }
        
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>Route " . htmlspecialchars($uri) . " not found.</p>";
    }
}
