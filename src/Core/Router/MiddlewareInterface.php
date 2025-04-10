<?php
// src/Core/Router/MiddlewareInterface.php
declare(strict_types=1);

namespace App\Core\Router;

interface MiddlewareInterface
{
    public function process(callable $next, array $params = []): void;
}