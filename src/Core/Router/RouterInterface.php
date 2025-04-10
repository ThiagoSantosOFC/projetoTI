<?php
// src/Core/Router/RouterInterface.php
declare(strict_types=1);

namespace App\Core\Router;

interface RouterInterface
{
    public function addRoute(string $method, string $pattern, callable $handler): void;
    public function resolve(string $method, string $uri): void;

    public string $basePath {
        set;
    }
}