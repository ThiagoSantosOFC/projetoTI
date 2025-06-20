<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\api\ResponseFactory;
use App\Infrastructure\Middleware\CorsMiddleware;

// Aplicar middleware CORS
$cors = new CorsMiddleware();
$cors->process(function () {
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ResponseFactory::error('Método não permitido', 405);
        return;
    }

    try {
        // Iniciar a sessão se ainda não estiver ativa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Destruir dados da sessão
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $params["path"],
                    'domain' => $params["domain"],
                    'secure' => $params["secure"],
                    'httponly' => $params["httponly"],
                    'samesite' => 'Strict' // Ajustar conforme necessário
                ]
            );
        }
        session_destroy();

        // Remover cookie de "lembrar-me"
        setcookie(
            'remember_user',
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );

        // Resposta de sucesso
        ResponseFactory::json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    } catch (Exception $e) {
        // Erro no servidor
        ResponseFactory::error('Erro no servidor: ' . $e->getMessage(), 500);
    }
}, []);
