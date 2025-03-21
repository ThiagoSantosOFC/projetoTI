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
