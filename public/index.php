<?php
/**
 * @SWG\Info(title="Estacionamento IoT API", version="1.0")
 * SwaggerPHP annotations: documente suas rotas e endpoints aqui.
 */

// Carrega o autoloader (se for criado) ou os arquivos necessários
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Auth.php';

// Instancia o roteador principal
$router = new Router();

// Exemplo de definição de rota para login
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

// Outras rotas poderão ser adicionadas aqui, como rotas para dashboard, histórico etc.
$router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
