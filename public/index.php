<?php

declare(strict_types=1);

// ——————————————————————————
// Configuração de erros para desenvolvimento
// ——————————————————————————
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// ——————————————————————————
// Autoload e imports
// ——————————————————————————
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router\MiddlewareInterface;
use App\Infrastructure\Actuator\JsonActuatorRepository;
use App\Infrastructure\Router\Router;
use App\Infrastructure\Sensor\JsonSensorRepository;

// ——————————————————————————
// Middleware de autenticação
// ——————————————————————————
class AuthMiddleware implements MiddlewareInterface
{
    public function process(callable $next, array $params = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            // Redireciona para home se não estiver autenticado
            header('Location: index.php?route=/');
            exit;
        }

        $next($params);
    }
}

$authMiddleware = new AuthMiddleware();

// ——————————————————————————
// Instancia o roteador
// ——————————————————————————
$router = new Router();

// ——————————————————————————
// Normalização do REQUEST_URI para suportar ambientes sem .htaccess
// ——————————————————————————
if (isset($_GET['route'])) {
    // Caso rota venha via query string (servidor da escola, sem .htaccess)
    $requestUri = '/' . ltrim($_GET['route'], '/');
} else {
    // Caso normal, usa URI do servidor (com .htaccess local)
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Detecta o basePath dinamicamente (ex: /ti/ti156/projetoTI/public)
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

// Obtém a URI completa da requisição
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 1. Remove o basePath da URI
    if (!empty($basePath) && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }

// 2. Remove "/index.php" no início, se existir
    if (strpos($requestUri, '/index.php') === 0) {
        $requestUri = substr($requestUri, strlen('/index.php'));
    }

// 3. Se URI ficar vazia, seta para "/"
    if ($requestUri === '') {
        $requestUri = '/';
    }

}

// Detecta o basePath dinamicamente (ex: /ti/ti156/projetoTI/public)
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

// Define no roteador
$router->setBasePath($basePath);

// Define método e URI final
$method = $_SERVER['REQUEST_METHOD'];
$uri = $requestUri === '' ? '/' : $requestUri;

// ——————————————————————————
// Função para normalizar padrão de rota removendo basePath embutido
// ——————————————————————————
function normalizeRoutePattern(string $pattern, string $basePath): string
{
    if (!empty($basePath) && strpos($pattern, $basePath) === 0) {
        return substr($pattern, strlen($basePath));
    }
    return $pattern;
}

// ——————————————————————————
// Definição das rotas (sem basePath embutido)
// ——————————————————————————

// Página inicial
$router->get(normalizeRoutePattern('/', $basePath), function () {
    header('Content-Type: text/html; charset=utf-8');
    $filePath = __DIR__ . '/views/home.html';
    if (file_exists($filePath)) {
        echo file_get_contents($filePath);
    } else {
        http_response_code(404);
        echo '<h1>Erro 404</h1><p>Página inicial não encontrada.</p>';
    }
});

// Login
$router->post(normalizeRoutePattern('/src/api/v1/login', $basePath), function () {
    require_once __DIR__ . '/../src/Api/v1/login.php';
});

// Dashboard protegido
$router->addRouteWithMiddleware('GET', normalizeRoutePattern('/dashboard', $basePath), function () {
    header('Content-Type: text/html; charset=utf-8');
    $filePath = __DIR__ . '/views/dashboard.php';
    if (file_exists($filePath)) {
        include $filePath;
    } else {
        http_response_code(404);
        echo '<h1>Erro 404</h1><p>Dashboard não encontrado.</p>';
    }
}, [$authMiddleware]);

// Sensores - todos
$router->get(normalizeRoutePattern('/src/api/v1/sensors', $basePath), function () {
    header('Content-Type: application/json; charset=utf-8');
    $sensorRepository = new JsonSensorRepository();
    $sensors = $sensorRepository->findAll();

    $sensorsArray = array_map(function ($sensor) {
        // getType() já retorna string, ou retorna null se algo deu errado
        $type   = $sensor->getType()   ?? \App\Domain\Sensor\SensorType::UNKNOWN;
        $status = $sensor->getStatus() ?? \App\Domain\Sensor\SensorStatus::UNKNOWN;

        return [
            'id'       => $sensor->getId(),
            'name'     => $sensor->getName(),
            'type'     => $type,
            'location' => $sensor->getLocation(),
            'status'   => $status,
            'metadata' => $sensor->getMetadata(),
        ];
    }, $sensors);

    echo json_encode([
        'success' => true,
        'data'    => $sensorsArray,
        'count'   => count($sensors)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Atuadores - todos
$router->get(normalizeRoutePattern('/src/api/v1/actuators', $basePath), function () {
    header('Content-Type: application/json; charset=utf-8');
    $actuatorRepository = new JsonActuatorRepository();
    $acts = $actuatorRepository->findAll();

    $actsArray = array_map(function ($act) {
        $type       = $act->getType()   ?? \App\Domain\Actuator\ActuatorType::UNKNOWN;
        $status     = $act->getStatus() ?? \App\Domain\Actuator\ActuatorStatus::UNKNOWN;
        $lastAction = $act->getLastAction(); // pode ser array ou null

        return [
            'id'         => $act->getId(),
            'name'       => $act->getName(),
            'type'       => $type,
            'location'   => $act->getLocation(),
            'status'     => $status,
            'metadata'   => $act->getMetadata(),
            'lastAction' => $lastAction,
        ];
    }, $acts);

    echo json_encode([
        'success' => true,
        'data'    => $actsArray,
        'count'   => count($acts)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// Sensor por ID
$router->get(normalizeRoutePattern('/src/api/v1/sensors/:id', $basePath), function ($params) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => "Detalhes do sensor {$params['id']}"]);
});

// Criar sensor
$router->post(normalizeRoutePattern('/src/api/v1/sensors', $basePath), function () {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Sensor criado']);
});

// Atuadores
$router->get(normalizeRoutePattern('/src/api/v1/actuators', $basePath), function () {
    header('Content-Type: application/json; charset=utf-8');
    $actuatorRepository = new JsonActuatorRepository();

    $actuators = $actuatorRepository->findAll();
    $actuatorsArray = array_map(function ($actuator) {
        return [
            'id' => $actuator->getId(),
            'name' => $actuator->getName(),
            'type' => $actuator->getType()->value,
            'location' => $actuator->getLocation(),
            'status' => $actuator->getStatus()->value,
            'metadata' => $actuator->getMetadata(),
            'lastAction' => $actuator->getLastAction()
        ];
    }, $actuators);

    echo json_encode([
        'success' => true,
        'data' => $actuatorsArray,
        'count' => count($actuators)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// ——————————————————————————
// Debug — mostrar basePath, método e URI
// ——————————————————————————
var_dump('basePath:', $basePath);
var_dump('method:', $method);
var_dump('uri:', $uri);

// ——————————————————————————
// Roda o roteador
// ——————————————————————————
$router->resolve($method, $uri);
