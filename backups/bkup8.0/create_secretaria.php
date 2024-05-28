<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];
    $status = $_POST['status'];
    $senha = $_POST['senha'];

    if (empty($senha)) {
        echo "<script>alert('A senha não pode ser vazia.'); window.history.back();</script>";
        exit;
    }

    // Verificar duplicidade de CPF
    $sqlCheckCpf = "SELECT * FROM secretaria WHERE cpf = ?";
    $stmtCheckCpf = $conn->prepare($sqlCheckCpf);
    $stmtCheckCpf->bind_param("s", $cpf);
    $stmtCheckCpf->execute();
    $resultCheckCpf = $stmtCheckCpf->get_result();

    if ($resultCheckCpf->num_rows > 0) {
        echo "<script>alert('Erro: CPF já cadastrado.'); window.history.back();</script>";
        exit;
    }

    $stmtCheckCpf->close();

    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
    $matricula = gerarMatricula($conn);

    // Inserir na tabela usuario
    $sqlInsertUsuario = "INSERT INTO usuario (matricula, senha, tipo, status) VALUES (?, ?, 'secretaria', ?)";
    $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
    $stmtInsertUsuario->bind_param("sss", $matricula, $senha_criptografada, $status);

    if (!$stmtInsertUsuario->execute()) {
        echo "<script>alert('Erro ao criar usuário: " . $stmtInsertUsuario->error . "'); window.history.back();</script>";
        exit;
    }

    $user_id = $stmtInsertUsuario->insert_id; // Pegando o ID do usuário inserido

    // Inserir na tabela secretaria
    $sqlInsertSecretaria = "INSERT INTO secretaria (matricula, nome, cpf, telefone, email, endereco, cep, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsertSecretaria = $conn->prepare($sqlInsertSecretaria);
    $stmtInsertSecretaria->bind_param("ssssssssi", $matricula, $nome, $cpf, $telefone, $email, $endereco, $cep, $status, $user_id);

    if (!$stmtInsertSecretaria->execute()) {
        echo "<script>alert('Erro ao criar secretaria: " . $stmtInsertSecretaria->error . "'); window.history.back();</script>";
    } else {
        echo "<script>alert('Registro de secretaria criado com sucesso!'); window.location.href = 'index.html';</script>";
    }

    $stmtInsertSecretaria->close();
    $stmtInsertUsuario->close();
    $conn->close();
}
?>

