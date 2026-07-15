<?php

// Define BASE_PATH constante (caminho absoluto até à raiz do projeto web)
define('BASE_PATH', dirname(__DIR__));

// Autoload PSR-4 simples
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relativeClass = substr($class, strlen($prefix));
    $file = BASE_PATH . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Carrega configuração
$config = require BASE_PATH . '/config/app.php';
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}

// Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

// Auth middleware
$authMiddleware = function (Request $req, callable $next) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    if (!$auth->isAuthenticated()) {
        $response = new Response();
        $response->json(['error' => 'Não autenticado'], 401);
    }
    return $next($req);
};

// Instancia Router
$router = new Router();
$request = new Request();

// -- Rotas das views --
$router->addRoute('GET', '/', function (Request $req, Response $res) {
    $content = file_get_contents(BASE_PATH . '/public/views/home.html');
    if ($content === false) {
        $res->json(['error' => 'View home.html não encontrada'], 500);
    }
    $res->html($content);
});

$router->addRoute('GET', '/dashboard', function (Request $req, Response $res) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    if (!$auth->isAuthenticated()) {
        $res->redirect('/');
    }
    ob_start();
    require BASE_PATH . '/public/views/dashboard.php';
    $content = ob_get_clean();
    $res->html($content);
});

$router->addRoute('GET', '/dashboard/historico', function (Request $req, Response $res) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    if (!$auth->isAuthenticated()) {
        $res->redirect('/');
    }
    ob_start();
    require BASE_PATH . '/public/views/historico.php';
    $content = ob_get_clean();
    $res->html($content);
});

// -- Rotas de autenticação --
$router->addRoute('POST', '/api/auth/login', function (Request $req, Response $res) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    $body = $req->getBody();
    $username = $body['username'] ?? '';
    $password = $body['password'] ?? '';

    if ($auth->login($username, $password)) {
        $res->json(['success' => true, 'user' => $username]);
    } else {
        $res->json(['success' => false, 'error' => 'Credenciais inválidas'], 401);
    }
});

$router->addRoute('POST', '/api/auth/logout', function (Request $req, Response $res) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    $auth->logout();
    $res->json(['success' => true]);
});

$router->addRoute('GET', '/api/auth/session', function (Request $req, Response $res) {
    $auth = new Auth(BASE_PATH . '/data/auth/utilizadores.txt');
    $res->json([
        'authenticated' => $auth->isAuthenticated(),
        'user' => $auth->getCurrentUser(),
    ]);
});

// -- Rotas API de Resources --
$router->addRoute('GET', '/api/resources', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->index($req);
});

$router->addRoute('GET', '/api/resources/{id}', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->show($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('POST', '/api/resources', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->store($req);
});

$router->addRoute('PUT', '/api/resources/{id}', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->update($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('PATCH', '/api/resources/{id}', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->patch($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('DELETE', '/api/resources/{id}', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->destroy($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('GET', '/api/resources/{id}/historico', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->historico($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('POST', '/api/resources/{id}/comando', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->comando($req, ['id' => $req->getParam('id')]);
});

$router->addRoute('DELETE', '/api/resources/{id}/historico', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\ResourceController();
    return $ctrl->limparHistorico($req, ['id' => $req->getParam('id')]);
});

// -- Rotas API de Câmara --
$router->addRoute('POST', '/api/camera', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\CameraController();
    return $ctrl->upload($req);
});

$router->addRoute('GET', '/api/camera/ultima', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\CameraController();
    return $ctrl->ultima($req);
});

$router->addRoute('GET', '/api/camera/historico', function (Request $req, Response $res) {
    $ctrl = new \App\Controllers\CameraController();
    return $ctrl->historico($req);
});

// -- Dispatch --
try {
    $router->dispatch($request);
} catch (\Throwable $e) {
    $response = new Response();
    $response->json([
        'error'   => 'Erro interno do servidor',
        'message' => $config['debug'] ? $e->getMessage() : 'Contacte o administrador',
    ], 500);
}
