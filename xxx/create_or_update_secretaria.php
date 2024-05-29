<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];
    $status = $_POST['status'];
    $senha = $_POST['senha'];

    if (empty($user_id)) {
        // Criação de nova secretaria
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $matricula = gerarMatricula($conn);

        $sqlInsertUsuario = "INSERT INTO usuario (matricula, senha, tipo, status) VALUES (?, ?, 'secretaria', ?)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
        $stmtInsertUsuario->bind_param("sss", $matricula, $senha_criptografada, $status);

        if (!$stmtInsertUsuario->execute()) {
            echo "Erro ao criar usuário: " . $stmtInsertUsuario->error;
            exit;
        }

        $user_id = $stmtInsertUsuario->insert_id;

        $sqlInsertSecretaria = "INSERT INTO secretaria (matricula, nome, cpf, telefone, email, endereco, cep, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertSecretaria = $conn->prepare($sqlInsertSecretaria);
        $stmtInsertSecretaria->bind_param("ssssssssi", $matricula, $nome, $cpf, $telefone, $email, $endereco, $cep, $status, $user_id);

        if (!$stmtInsertSecretaria->execute()) {
            echo "Erro ao criar secretaria: " . $stmtInsertSecretaria->error;
        } else {
            echo "<script>alert('Registro de secretaria criado com sucesso!'); window.location.href = 'index.html';</script>";
        }

        $stmtInsertSecretaria->close();
        $stmtInsertUsuario->close();
    } else {
        // Atualização de secretaria existente
        $updateFields = ['nome', 'cpf', 'telefone', 'email', 'endereco', 'cep', 'status'];
        $setFields = [];

        foreach ($updateFields as $field) {
            if (!empty($_POST[$field])) {
                $setFields[] = "$field = ?";
            }
        }

        $sqlUpdateSecretaria = "UPDATE secretaria SET " . implode(", ", $setFields) . " WHERE user_id = ?";
        $stmtUpdateSecretaria = $conn->prepare($sqlUpdateSecretaria);

        $types = str_repeat('s', count($setFields)) . 'i';
        $values = array_map(function ($field) { return $_POST[$field]; }, $updateFields);
        $values = array_filter($values);
        $values[] = $user_id;

        $stmtUpdateSecretaria->bind_param($types, ...$values);

        if ($stmtUpdateSecretaria->execute()) {
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
            echo "Erro ao atualizar informações: " . $stmtUpdateSecretaria->error;
        }

        $stmtUpdateSecretaria->close();
    }

    $conn->close();
}
?>
