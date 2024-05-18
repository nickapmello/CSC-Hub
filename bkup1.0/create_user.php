<?php
include 'db.php';
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $endereco = $_POST['endereco'];
    $tipo = isset($_POST['tipo']) && $_POST['tipo'] == 'professor' ? 'professor' : 'aluno';

    // telefone check
    $sqlCheck = "SELECT telefone FROM aluno WHERE telefone = ? UNION SELECT telefone FROM professor WHERE telefone = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ss", $telefone, $telefone);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "Erro: O número de telefone já está sendo usado.";
        $stmtCheck->close();
        $conn->close();
        exit;
    }
    $stmtCheck->close();

    // insert na tabela certa
    $matricula = gerarMatricula();
    $sqlInsert = "INSERT INTO $tipo (nome, telefone, senha, endereco, matricula) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("sssss", $nome, $telefone, $senha, $endereco, $matricula);
    $stmtInsert->execute();

    if ($stmtInsert->affected_rows > 0) {
        echo "<script>alert('Registro criado com sucesso!'); window.location.href = 'http://localhost/estagio/index.html';</script>";
    } else {
        echo "Erro ao criar registro: " . $stmtInsert->error;
    }

    $stmtInsert->close();
    $conn->close();
}
?>
