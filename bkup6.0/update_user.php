<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $status = $_POST['status'];
    $senha = isset($_POST['senha']) ? $_POST['senha'] : null;
    $isProfessor = $_POST['isProfessor'] == 'true' ? 'professor' : 'aluno';

    // Adicionando logs para verificar os dados recebidos
    error_log("Recebido POST para update_user.php");
    error_log("Matrícula: $matricula, Nome: $nome, Telefone: $telefone, Endereço: $endereco, Status: $status, Senha: $senha, isProfessor: $isProfessor");

    if ($senha) {
        $sql = "UPDATE $isProfessor SET nome = ?, telefone = ?, endereco = ?, status = ?, senha = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nome, $telefone, $endereco, $status, $senha, $matricula);
    } else {
        $sql = "UPDATE $isProfessor SET nome = ?, telefone = ?, endereco = ?, status = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $telefone, $endereco, $status, $matricula);
    }

    if ($stmt->execute()) {
        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro ao atualizar os dados: " . $stmt->error;
        error_log("Erro ao atualizar os dados: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>