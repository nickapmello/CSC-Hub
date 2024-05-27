<?php
session_start(); // Inicia a sessão PHP

include '../xxx/db.php'; // Inclui o arquivo de conexão do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    // Prepara a query para evitar SQL Injection
    $sql = "SELECT * FROM aluno WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $senha_armazenada = $user['senha'];

        // Verifica se a senha inserida corresponde à hash armazenada no banco
        if (password_verify($senha, $senha_armazenada)) {
            // Definir variáveis de sessão se necessário
            $_SESSION['usuario'] = $user['nome'];
            $_SESSION['matricula'] = $user['matricula'];
            $_SESSION['tipo'] = 'aluno'; // Assumindo que estamos lidando apenas com alunos

            // Exibe uma mensagem de sucesso no login
            echo "<script>alert('Login bem-sucedido! Bem-vindo, " . $user['nome'] . "!'); window.location.href = 'index.html';</script>";
            exit;
        } else {
            echo "<script>alert('Senha incorreta.'); window.location.href = 'index.html';</script>";
        }
    } else {
        echo "<script>alert('Matrícula não encontrada.'); window.location.href = 'index.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
