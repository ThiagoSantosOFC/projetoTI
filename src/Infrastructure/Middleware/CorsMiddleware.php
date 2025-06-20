<?php

namespace App\Infrastructure\Middleware;

class CorsMiddleware
{
    public function process(callable $next, array $params): void
    {
        // Permitir requisições de qualquer origem
        header('Access-Control-Allow-Origin: *');

        // Métodos HTTP permitidos
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        // Headers permitidos
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Cache para preflight
        header('Access-Control-Max-Age: 86400');

        // Se for uma requisição OPTIONS (preflight), retornar apenas os headers
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        // Continuar com a requisição
        $next($params);
    }
}