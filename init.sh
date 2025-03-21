#!/bin/bash
# Script de inicialização do projeto - Entrega 1
# Requisitos da Entrega 1:
#   - API para recolha e envio de informações, com validações seguindo boas práticas.
#   - Página de autenticação (mínimo 2 usuários) com credenciais armazenadas em arquivo separado.
#   - Página de dashboard personalizada, preparada para pelo menos 3 sensores e 3 atuadores.
#   - Página de histórico para cada sensor e atuador.
#   - Design apelativo e responsivo com Bootstrap.
#   - Pelo menos 1 arquivo CSS com no mínimo 10 seletores customizados.
#   - Código bem comentado, isento de warnings/erros.
#   - Não utilizar frameworks como Laravel, Node.js, Vue.js, etc. e nem motores de bases de dados.

echo "Criando estrutura de diretórios..."

# Diretórios principais
mkdir -p src/api
mkdir -p src/config
mkdir -p public/css
mkdir -p public/js
mkdir -p public/pages
mkdir -p public/assets

echo "Criando arquivos iniciais..."

# Arquivo de configuração de usuários (credenciais)
cat <<'EOL' > src/config/users.php
<?php
// Configuração de usuários (credenciais)
// Importante: As senhas estão armazenadas em texto plano apenas para fins de exemplo.
return [
    'user1' => 'senha1',
    'user2' => 'senha2',
];
EOL

# Arquivo principal da API RESTful
cat <<'EOL' > src/api/index.php
<?php
// API RESTful básica seguindo princípios SOLID, POO, DRY e Design Patterns

// Autoload simples para classes
spl_autoload_register(function ($class) {
    require_once __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
});

// Roteamento simples (Exemplo)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Aqui você deverá implementar o roteamento para os endpoints da API, 
// direcionando as requisições para os respectivos controllers e validando os dados.
header('Content-Type: application/json');
echo json_encode(['message' => 'API em desenvolvimento']);
EOL

# Página de autenticação
cat <<'EOL' > public/pages/login.php
<?php
// Página de autenticação
session_start();
$users = include __DIR__ . '/../../src/config/users.php';

// Verifica se os dados foram enviados e valida as credenciais
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciais inválidas!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="post" action="login.php">
        <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
</body>
</html>
EOL

# Página de dashboard
cat <<'EOL' > public/pages/dashboard.php
<?php
// Dashboard personalizada
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Dashboard</h2>
    <p>Bem-vindo, <?php echo $_SESSION['user']; ?>!</p>
    <!-- Seção para 3 sensores -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Sensor 1</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Sensor 2</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Sensor 3</div>
            </div>
        </div>
    </div>
    <!-- Seção para 3 atuadores -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Atuador 1</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Atuador 2</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">Atuador 3</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
EOL

# Página de histórico para sensores e atuadores
cat <<'EOL' > public/pages/historico.php
<?php
// Página de histórico
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Histórico</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Histórico de Sensores e Atuadores</h2>
    <!-- Aqui deverá ser implementada a exibição dinâmica do histórico -->
    <p>Aqui serão exibidos os históricos dos sensores e atuadores.</p>
</div>
</body>
</html>
EOL

# Arquivo CSS personalizado com pelo menos 10 seletores
cat <<'EOL' > public/css/style.css
/* CSS personalizado */
/* 1. Seleciona o body */
body {
    background-color: #f8f9fa;
}
/* 2. Seleciona os títulos h2 */
h2 {
    color: #343a40;
}
/* 3. Seleciona o container */
.container {
    margin-top: 50px;
}
/* 4. Seleciona os cards */
.card {
    margin-bottom: 20px;
}
/* 5. Seleciona botões primários */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}
/* 6. Seleciona a navbar */
.navbar {
    margin-bottom: 20px;
}
/* 7. Seleciona o rodapé */
.footer {
    text-align: center;
    padding: 10px;
    background-color: #343a40;
    color: white;
}
/* 8. Seleciona alertas */
.alert {
    margin-top: 20px;
}
/* 9. Seleciona labels em formulários */
.form-group label {
    font-weight: bold;
}
/* 10. Seleciona inputs dos formulários */
.form-control {
    border-radius: 4px;
}
EOL

echo "Estrutura inicial do projeto criada com sucesso!"
