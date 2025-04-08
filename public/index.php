<?php
/**
 * @SWG\Info(title="Estacionamento IoT API", version="1.0")
 * SwaggerPHP annotations: documente suas rotas e endpoints aqui.
 */

// Carrega o autoloader (se for criado) ou os arquivos necessários
require_once __DIR__ . '/../src/class/Router.php';
require_once __DIR__ . '/../src/class/Auth.php';

// Instancia o roteador principal
$router = new Router();

// Função para verificar se o utilizador está autenticado
function isAuthenticated(): bool
{
    // Iniciar sessão se ainda não estiver iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar se o utilizador está autenticado
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Middleware para rotas protegidas
function authMiddleware($callback): Closure
{
    return function() use ($callback) {
        if (!isAuthenticated()) {
            header('Location: /');
            exit;
        }

        // Se estiver autenticado, executa o callback original
        return call_user_func($callback);

    };
}

$router->addRoute('GET', '/', function() {
    // Define o cabeçalho para HTML
    header('Content-Type: text/html; charset=utf-8');

    // Caminho para o arquivo HTML
    $filePath = __DIR__ . '/views/home.html';
    if (file_exists($filePath)) {
        echo file_get_contents($filePath);
    } else {
        http_response_code(404);
        echo '<h1>Erro 404</h1><p>Página inicial não encontrada.</p>';
    }
});
$router->addRoute('POST', '/src/api/v1/login', function() {
    // Incluir o arquivo de login
    require_once __DIR__ . '/../src/api/v1/login.php';


});


// Rota para o dashboard (protegida)

$router->addRoute('GET', '/dashboard', authMiddleware(function() {
    header('Content-Type: text/html; charset=utf-8');
    $filePath = __DIR__ . '/views/dashboard.php';
    if (file_exists($filePath)) {
        include $filePath;
    } else {
        http_response_code(404);
        echo '<h1>Erro 404</h1><p>Dashboard não encontrado.</p>';
    }
}));



$router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


