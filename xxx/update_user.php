<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $tipo = $_POST['tipo'];
    $senha = $_POST['senha'];

    // Atualizar a tabela correspondente
    $updateFields = [];
    foreach ($_POST as $key => $value) {
        if ($key !== 'user_id' && $key !== 'tipo' && $key !== 'senha') {
            $updateFields[$key] = $value;
        }
    }

    if ($tipo === 'aluno') {
        $sql = "UPDATE aluno SET ";
    } elseif ($tipo === 'professor') {
        $sql = "UPDATE professor SET ";
    } else {
        $sql = "UPDATE secretaria SET ";
    }

    foreach ($updateFields as $key => $value) {
        $sql .= "$key = ?, ";
    }

    $sql = rtrim($sql, ', ');
    $sql .= " WHERE user_id = ?";

    $stmt = $conn->prepare($sql);

    $types = str_repeat('s', count($updateFields)) . 'i';
    $values = array_values($updateFields);
    $values[] = $user_id;

    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        if (!empty($senha)) {
            $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
            $sqlUpdateSenha = "UPDATE usuario SET senha = ? WHERE id = ?";
            $stmtUpdateSenha = $conn->prepare($sqlUpdateSenha);
            $stmtUpdateSenha->bind_param("si", $senha_criptografada, $user_id);
            $stmtUpdateSenha->execute();
            $stmtUpdateSenha->close();
        }
        echo "Informações atualizadas com sucesso!";
    } else {
        echo "Erro ao atualizar informações: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>