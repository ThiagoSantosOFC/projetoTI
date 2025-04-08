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
$router->setBasePath('/projetoti/public');


$router->addRoute('GET', '/public/', function() {
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

$router->addRoute('POST', '/login', function() {
    // Obtém dados de entrada (em JSON ou form data)
    $data = json_decode(file_get_contents('php://input'), true);
    $auth = new Auth();
    if ($auth->login($data['username'] ?? '', $data['password'] ?? '')) {
        echo json_encode(['message' => 'Login realizado com sucesso']);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Credenciais inválidas']);
    }
});
$router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


