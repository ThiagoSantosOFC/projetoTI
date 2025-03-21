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
