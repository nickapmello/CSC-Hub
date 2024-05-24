<?php
include '../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    // verifica aluno/professor
    $tipos = ['aluno', 'professor'];

    foreach ($tipos as $tipo) {
        $sql = "SELECT * FROM $tipo WHERE matricula = ?";
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
                $_SESSION['tipo'] = $tipo;

                header("Location: home.php");
                exit;
            }
        }

        $stmt->close();
    }

    echo "<script>alert('Dados incorretos.'); window.location.href = 'index.html';</script>";
    $conn->close();
}
?>
