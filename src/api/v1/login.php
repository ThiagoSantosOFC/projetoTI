<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\api\ResponseFactory;
use App\Core\Auth\Auth;
use App\Infrastructure\Middleware\CorsMiddleware;

// Aplicar middleware CORS
$cors = new CorsMiddleware();
$cors->process(function() {
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ResponseFactory::error('Método não permitido', 405);
        return;
    }

    try {
        // Obter dados do corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar se os dados são válidos
        if (!$data || !isset($data['username']) || !isset($data['password'])) {
            ResponseFactory::error('Nome de utilizador e palavra-passe são obrigatórios');
            return;
        }

        $username = $data['username'];
        $password = $data['password'];
        $remember = $data['remember'] ?? false;

        // Instanciar o Auth
        $auth = new Auth();

        // Verificar credenciais
        if ($auth->login($username, $password)) {
            // Iniciar sessão se necessário
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Guardar informações na sessão
            $_SESSION['user'] = $username;
            $_SESSION['authenticated'] = true;

            // Configurar cookie "lembrar-me" se solicitado
            if ($remember) {
                $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
                $sameSite = 'Strict';

                setcookie(
                    'remember_user',
                    $username,
                    [
                        'expires' => time() + (30 * 24 * 60 * 60),
                        'path' => '/',
                        'domain' => '',
                        'secure' => $secure,
                        'httponly' => true,
                        'samesite' => $sameSite
                    ]
                );
            }

            // Resposta de sucesso
            ResponseFactory::json([
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'user' => [
                    'username' => $username
                ]
            ]);
        } else {
            // Resposta de erro de autenticação
            ResponseFactory::error('Nome de utilizador ou palavra-passe incorretos', 401);
        }
    } catch (Exception $e) {
        // Resposta de erro do servidor
        ResponseFactory::error('Erro no servidor: ' . $e->getMessage(), 500);
    }
}, []);