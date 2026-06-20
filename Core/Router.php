<?php
namespace App\Core;
class Router {
    protected $routes = [];
    public function add($method, $uri, $controller) {
        $this->routes[] = ['method' => $method, 'uri' => '/' . trim($uri, '/'), 'controller' => $controller];
    }
    public function handle($method, $uri) {
        $uri = '/' . trim(explode('?', $uri)[0], '/');
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                $parts = explode('@', $route['controller']);
                $controllerName = "App\\Controllers\\" . $parts[0];
                $methodName = $parts[1];
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName();
                        return;
                    }
                }
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page Not Found</h1><p>Route $uri not found.</p>";
    }
}
