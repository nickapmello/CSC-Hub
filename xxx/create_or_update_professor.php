<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
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

    if (empty($user_id)) {
        // Criação de novo professor
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $matricula = gerarMatricula($conn);

        $sqlInsertUsuario = "INSERT INTO usuario (matricula, senha, tipo, status) VALUES (?, ?, 'professor', ?)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
        $stmtInsertUsuario->bind_param("sss", $matricula, $senha_criptografada, $status);

        if (!$stmtInsertUsuario->execute()) {
            echo "Erro ao criar usuário: " . $stmtInsertUsuario->error;
            exit;
        }

        $user_id = $stmtInsertUsuario->insert_id;

        $sqlInsertProfessor = "INSERT INTO professor (matricula, nome, cpf, data_nascimento, genero, telefone, email, endereco, cep, formacao_academica, area_ensino, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertProfessor = $conn->prepare($sqlInsertProfessor);
        $stmtInsertProfessor->bind_param("ssssssssssssi", $matricula, $nome, $cpf, $data_nascimento, $genero, $telefone, $email, $endereco, $cep, $formacao_academica, $area_ensino, $status, $user_id);

        if (!$stmtInsertProfessor->execute()) {
            echo "Erro ao criar professor: " . $stmtInsertProfessor->error;
        } else {
            echo "<script>alert('Registro de professor criado com sucesso!'); window.location.href = 'index.html';</script>";
        }

        $stmtInsertProfessor->close();
        $stmtInsertUsuario->close();
    } else {
        // Atualização de professor existente
        $updateFields = ['nome', 'data_nascimento', 'genero', 'cpf', 'telefone', 'email', 'endereco', 'cep', 'formacao_academica', 'area_ensino', 'status'];
        $setFields = [];

        foreach ($updateFields as $field) {
            if (!empty($_POST[$field])) {
                $setFields[] = "$field = ?";
            }
        }

        $sqlUpdateProfessor = "UPDATE professor SET " . implode(", ", $setFields) . " WHERE user_id = ?";
        $stmtUpdateProfessor = $conn->prepare($sqlUpdateProfessor);

        $types = str_repeat('s', count($setFields)) . 'i';
        $values = array_map(function ($field) { return $_POST[$field]; }, $updateFields);
        $values = array_filter($values);
        $values[] = $user_id;

        $stmtUpdateProfessor->bind_param($types, ...$values);

        if ($stmtUpdateProfessor->execute()) {
            if (!empty($senha)) {
                $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                $sqlUpdateSenha = "UPDATE usuario SET senha = ? WHERE id = ?";
                $stmtUpdateSenha = $conn->prepare($sqlUpdateSenha);
                $stmtUpdateSenha->bind_param("si", $senha_criptografada, $user_id);
                $stmtUpdateSenha->execute();
                $stmtUpdateSenha->close();
            }
            echo "<script>alert('Informações atualizadas com sucesso!'); window.location.href = 'index.html';</script>";
        } else {
            echo "Erro ao atualizar informações: " . $stmtUpdateProfessor->error;
        }

        $stmtUpdateProfessor->close();
    }

    $conn->close();
}
?>
