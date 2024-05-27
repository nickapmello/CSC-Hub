<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM aluno WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $senha_armazenada = $user['senha'];

        if (password_verify($senha, $senha_armazenada)) {
            session_start();
            $_SESSION['usuario'] = $user['nome'];
            $_SESSION['matricula'] = $user['matricula'];
            $_SESSION['tipo'] = 'aluno'; // Como agora temos só alunos

            header("Location: home.php");
            exit;
        }
    }

    $stmt->close();
    echo "<script>alert('Dados incorretos.'); window.location.href = 'index.html';</script>";
    $conn->close();
}
?>
