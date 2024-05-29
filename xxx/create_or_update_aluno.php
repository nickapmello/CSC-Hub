<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $nome = $_POST['nome'];
    $data = $_POST['data'];
    $genero = $_POST['genero'];
    $naturalidade = $_POST['naturalidade'];
    $endereco = $_POST['endereco'];
    $CEP = $_POST['CEP'];
    $Nome_Pai = $_POST['Nome_Pai'];
    $CPF_Pai = $_POST['CPF_Pai'];
    $Telefone_Pai = $_POST['Telefone_Pai'];
    $Nome_Mae = $_POST['Nome_Mae'];
    $CPF_Mae = $_POST['CPF_Mae'];
    $Telefone_Mae = $_POST['Telefone_Mae'];
    $info_saude = $_POST['info_saude'];
    $medicamento = $_POST['medicamento'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $senha = $_POST['senha'];

    if (empty($user_id)) {
        // Criação de novo aluno
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $matricula = gerarMatricula($conn);

        $sqlInsertUsuario = "INSERT INTO usuario (matricula, senha, tipo, status) VALUES (?, ?, 'aluno', ?)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
        $stmtInsertUsuario->bind_param("sss", $matricula, $senha_criptografada, $status);

        if (!$stmtInsertUsuario->execute()) {
            echo "Erro ao criar usuário: " . $stmtInsertUsuario->error;
            exit;
        }

        $user_id = $stmtInsertUsuario->insert_id;

        $sqlInsertAluno = "INSERT INTO aluno (matricula, nome, data, genero, naturalidade, endereco, CEP, Nome_Pai, CPF_Pai, Telefone_Pai, Nome_Mae, CPF_Mae, Telefone_Mae, info_saude, medicamento, email, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertAluno = $conn->prepare($sqlInsertAluno);
        $stmtInsertAluno->bind_param("sssssssssssssssssi", $matricula, $nome, $data, $genero, $naturalidade, $endereco, $CEP, $Nome_Pai, $CPF_Pai, $Telefone_Pai, $Nome_Mae, $CPF_Mae, $Telefone_Mae, $info_saude, $medicamento, $email, $status, $user_id);

        if (!$stmtInsertAluno->execute()) {
            echo "Erro ao criar aluno: " . $stmtInsertAluno->error;
        } else {
            echo "<script>alert('Registro de aluno criado com sucesso!'); window.location.href = 'index.html';</script>";
        }

        $stmtInsertAluno->close();
        $stmtInsertUsuario->close();
    } else {
        // Atualização de aluno existente
        $updateFields = ['nome', 'data', 'genero', 'naturalidade', 'endereco', 'CEP', 'Nome_Pai', 'CPF_Pai', 'Telefone_Pai', 'Nome_Mae', 'CPF_Mae', 'Telefone_Mae', 'info_saude', 'medicamento', 'email', 'status'];
        $setFields = [];

        foreach ($updateFields as $field) {
            if (!empty($_POST[$field])) {
                $setFields[] = "$field = ?";
            }
        }

        $sqlUpdateAluno = "UPDATE aluno SET " . implode(", ", $setFields) . " WHERE user_id = ?";
        $stmtUpdateAluno = $conn->prepare($sqlUpdateAluno);

        $types = str_repeat('s', count($setFields)) . 'i';
        $values = array_map(function ($field) { return $_POST[$field]; }, $updateFields);
        $values = array_filter($values);
        $values[] = $user_id;

        $stmtUpdateAluno->bind_param($types, ...$values);

        if ($stmtUpdateAluno->execute()) {
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
            echo "Erro ao atualizar informações: " . $stmtUpdateAluno->error;
        }

        $stmtUpdateAluno->close();
    }

    $conn->close();
}
?>
