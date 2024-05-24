<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "<script>alert('Por favor, faça login primeiro.'); window.location.href = 'index.html';</script>";
    exit;
}

$nomeUsuario = $_SESSION['usuario'];
$tipoUsuario = ucfirst($_SESSION['tipo']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo!</h1>
        <p class="welcome-message">Bem vindo <?php echo $tipoUsuario . ' ' . $nomeUsuario; ?>!</p>
    </div>
</body>
</html>
