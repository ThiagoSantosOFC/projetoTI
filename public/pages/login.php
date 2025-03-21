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
