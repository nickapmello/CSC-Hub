<?php
include 'db.php';
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $endereco = $_POST['endereco'];
    $status = $_POST['status'];
    $tipo = isset($_POST['tipo']) && $_POST['tipo'] == 'professor' ? 'professor' : 'aluno';

    // valida 11 digitos
    if (strlen($telefone) != 11 || !ctype_digit($telefone)) {
        echo "O número de telefone deve ter exatamente 11 dígitos e ser numérico.";
        exit;
    }

    // senha hash
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    $matricula = gerarMatricula($conn);

    $sqlInsert = "INSERT INTO $tipo (nome, telefone, senha, endereco, matricula, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssss", $nome, $telefone, $senha_criptografada, $endereco, $matricula, $status);
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
