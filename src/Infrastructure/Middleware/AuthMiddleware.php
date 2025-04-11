<?php
// src/Infrastructure/Middleware/AuthMiddleware.php
declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\api\ResponseFactory;
use App\Core\Router\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(callable $next, array $params = []): void
    {
        // Verificar autenticação (exemplo simplificado)
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (empty($token) || !$this->validateToken($token)) {
            ResponseFactory::error('Não autorizado', 401);
            return;
        }

        // Chamar o próximo middleware ou handler
        $next($params);
    }

    private function validateToken(string $token): bool
    {
        // Implementação de validação (simplificada para exemplo)
        return str_starts_with($token, 'Bearer ');
    }
}