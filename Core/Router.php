<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $uri, $action)
    {
        $this->routes[$method][$uri] = $action;
    }

    public function handle($method, $uri)
    {
        if (!isset($this->routes[$method][$uri])) {
            die("404 - Page Not Found");
        }

        [$controller, $action] = explode('@', $this->routes[$method][$uri]);

        $controller = "App\\Controllers\\$controller";

        (new $controller)->$action();
    }
}