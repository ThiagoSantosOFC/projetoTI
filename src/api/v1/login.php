<?php
// Habilitar CORS para permitir requisições Ajax
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Se a requisição for OPTIONS (preflight), retornar apenas os headers e finalizar
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Obter o corpo da requisição
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se os campos necessários foram fornecidos
if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Nome de utilizador e palavra-passe são obrigatórios']);
    exit;
}

// Incluir a classe Auth
require_once __DIR__ . '/../../class/Auth.php';

// Criar instância da classe Auth
$auth = new Auth();

// Verificar as credenciais
$username = $data['username'];
$password = $data['password'];
$remember = $data['remember'] ?? false;

if ($auth->login($username, $password)) {
    // Iniciar sessão se ainda não estiver iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Guardar informações do utilizador na sessão
    $_SESSION['user'] = $username;
    $_SESSION['authenticated'] = true;

    // Se "lembrar-me" estiver ativado, criar um cookie
    if ($remember) {
        // Cookie válido por 30 dias (em segundos)
        setcookie('remember_user', $username, time() + (30 * 24 * 60 * 60), '/');
    }

    // Retornar resposta de sucesso
    echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
} else {
    // Retornar resposta de erro
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Nome de utilizador ou palavra-passe incorretos']);
}



