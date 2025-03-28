#!/bin/bash
# Estrutura inicial do projeto "Estacionamento IoT"

# Diretórios
echo "Criando diretórios..."
mkdir -p src tests public config swagger

# Arquivo de credenciais (armazenando dois usuários)
cat << 'EOF' > config/credentials.php
<?php
// Configuração simples de usuários para autenticação
// Formato: username => password (senha hasheada ou em texto, dependendo da implementação)
return [
    'usuario1' => 'senhaSegura1',
    'usuario2' => 'senhaSegura2'
];
EOF

# Arquivo principal de roteamento da API
cat << 'EOF' > public/index.php
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
EOF

# Classe de roteamento (sem MVC)
cat << 'EOF' > src/Router.php
<?php
/**
 * Classe Router - responsável por roteamento simples de requisições.
 * Segue os padrões e naming conventions recomendados.
 */
class Router {
    // Armazena as rotas definidas
    private array $routes = [];

    /**
     * Adiciona uma rota à API.
     *
     * @param string   $method   Método HTTP (GET, POST, etc.)
     * @param string   $uri      URI da rota
     * @param callable $handler  Função callback que trata a requisição
     */
    public function addRoute(string $method, string $uri, callable $handler): void {
        $this->routes[$method][$uri] = $handler;
    }

    /**
     * Roteia a requisição para o handler adequado.
     *
     * @param string $method
     * @param string $requestUri
     */
    public function route(string $method, string $requestUri): void {
        // Remove query strings, se houver
        $uri = parse_url($requestUri, PHP_URL_PATH);
        if (isset($this->routes[$method][$uri])) {
            // Executa a função associada à rota
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Rota não encontrada']);
        }
    }
}
EOF

# Classe de autenticação
cat << 'EOF' > src/Auth.php
<?php
/**
 * Classe Auth - Gerencia a autenticação dos usuários.
 */
class Auth {
    private array $users;

    public function __construct() {
        // Carrega os usuários do arquivo de configuração
        $this->users = require __DIR__ . '/../config/credentials.php';
    }

    /**
     * Realiza o login verificando as credenciais.
     *
     * @param string $username
     * @param string $password
     * @return bool Retorna true se a autenticação for bem-sucedida
     */
    public function login(string $username, string $password): bool {
        // Exemplo simples: verificação direta (ideal utilizar hash e salt em produção)
        return isset($this->users[$username]) && $this->users[$username] === $password;
    }
}
EOF

# Exemplo de arquivo de teste unitário usando PHP assert ou PHPUnit (aqui usamos um teste simples com assert)
cat << 'EOF' > tests/RouterTest.php
<?php
/**
 * Teste unitário para a classe Router.
 * Execute com: php tests/RouterTest.php
 */

require_once __DIR__ . '/../src/Router.php';

echo "Iniciando testes para Router...\n";

// Cria uma instância do Router
$router = new Router();

// Define uma rota de teste
$router->addRoute('GET', '/teste', function() {
    echo 'Teste OK';
});

// Simula uma requisição para a rota definida
ob_start();
$router->route('GET', '/teste');
$output = ob_get_clean();

// Verifica se o output é o esperado
assert($output === 'Teste OK', 'Rota /teste não funcionou corretamente.');

echo "Todos os testes passaram!\n";
EOF

# Exemplo de uma página de login para o website (pode ser expandida)
cat << 'EOF' > public/login.php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Estacionamento IoT</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Login</h1>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Digite seu usuário" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</body>
</html>
EOF

# Exemplo de arquivo CSS personalizado com 10 seletores mínimos
mkdir -p public/css
cat << 'EOF' > public/css/estilos.css
/* Seletores personalizados */
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}

.container {
    margin-top: 50px;
}

h1 {
    color: #343a40;
}

form {
    border: 1px solid #ced4da;
    padding: 20px;
    border-radius: 5px;
}

.form-group label {
    font-weight: bold;
}

.form-control {
    margin-bottom: 15px;
}

.btn-primary {
    background-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.footer {
    text-align: center;
    margin-top: 30px;
    font-size: 0.9em;
}
EOF

echo "Projeto criado com sucesso!"
