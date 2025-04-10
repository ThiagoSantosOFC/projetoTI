<?php
declare(strict_types=1);
/**
 * @SWG\Info(title="Estacionamento IoT API", version="1.0")
 * SwaggerPHP annotations: documente suas rotas e endpoints aqui.
 */

//iniciar o autoload
require_once __DIR__ . '/../vendor/autoload.php';
// Importa as classes necessárias
use App\Core\Router\MiddlewareInterface;
use App\Infrastructure\Router\Router;

// Instancia o router principal
$router = new Router();
$router->setBasePath(''); // Ajuste conforme necessário para o seu ambiente

// Classe de middleware para autenticação
class AuthMiddleware implements MiddlewareInterface
{
    public function process(callable $next, array $params = []): void
    {
        // Iniciar sessão se ainda não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar se o utilizador está autenticado
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: /');
            exit;
        }

        // Se estiver autenticado, executa o próximo middleware ou handler
        $next($params);
    }
}

// Criando instância do middleware de autenticação
$authMiddleware = new AuthMiddleware();

// Define as rotas da aplicação
$router->get('/', function() {
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

$router->post('/src/api/v1/login', function() {
    // Incluir o arquivo de login
    require_once __DIR__ . '/../src/Api/v1/login.php';
});

// Rota para o dashboard (protegida com middleware)
$router->addRouteWithMiddleware('GET', '/dashboard', function() {
    header('Content-Type: text/html; charset=utf-8');
    $filePath = __DIR__ . '/views/dashboard.php';
    if (file_exists($filePath)) {
        include $filePath;
    } else {
        http_response_code(404);
        echo '<h1>Erro 404</h1><p>Dashboard não encontrado.</p>';
    }
}, [$authMiddleware]);

// Adicionando rotas de API para sensores (apenas como exemplo)
$router->get('/src/api/v1/sensors', function() {
    header('Content-Type: application/json; charset=utf-8');
    // Aqui você pode incluir a lógica para buscar todos os sensores
    echo json_encode(['message' => 'Lista de sensores']);
});

$router->get('/src/api/v1/sensors/:id', function($params) {
    header('Content-Type: application/json; charset=utf-8');
    $id = $params['id'];
    // Aqui você pode incluir a lógica para buscar um sensor específico
    echo json_encode(['message' => "Detalhes do sensor $id"]);
});


$router->post('/src/api/v1/sensors', function() {
    header('Content-Type: application/json; charset=utf-8');
    // Aqui você pode incluir a lógica para criar um sensor
    echo json_encode(['message' => 'Sensor criado']);
});

// Responder à requisição atual
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Roteia a requisição
$router->resolve($method, $uri);