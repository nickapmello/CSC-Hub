<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];
    $formacao_academica = $_POST['formacao_academica'];
    $area_ensino = $_POST['area_ensino'];
    $status = $_POST['status'];
    $senha = $_POST['senha'];

    if (empty($senha)) {
        echo "<script>alert('A senha não pode ser vazia.'); window.history.back();</script>";
        exit;
    }

    // Verificar duplicidade de CPF
    $sqlCheckCpf = "SELECT * FROM professor WHERE cpf = ?";
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
    $sqlInsertUsuario = "INSERT INTO usuario (matricula, senha, tipo, status) VALUES (?, ?, 'professor', ?)";
    $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
    $stmtInsertUsuario->bind_param("sss", $matricula, $senha_criptografada, $status);

    if (!$stmtInsertUsuario->execute()) {
        echo "<script>alert('Erro ao criar usuário: " . $stmtInsertUsuario->error . "'); window.history.back();</script>";
        exit;
    }

    $user_id = $stmtInsertUsuario->insert_id; // Pegando o ID do usuário inserido

    // Inserir na tabela professor
    $sqlInsertProfessor = "INSERT INTO professor (matricula, nome, cpf, data_nascimento, genero, telefone, email, endereco, cep, formacao_academica, area_ensino, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsertProfessor = $conn->prepare($sqlInsertProfessor);
    $stmtInsertProfessor->bind_param("ssssssssssssi", $matricula, $nome, $cpf, $data_nascimento, $genero, $telefone, $email, $endereco, $cep, $formacao_academica, $area_ensino, $status, $user_id);

    if (!$stmtInsertProfessor->execute()) {
        echo "<script>alert('Erro ao criar professor: " . $stmtInsertProfessor->error . "'); window.history.back();</script>";
    } else {
        echo "<script>alert('Registro de professor criado com sucesso!'); window.location.href = 'index.html';</script>";
    }

    $stmtInsertProfessor->close();
    $stmtInsertUsuario->close();
    $conn->close();
}
?>
