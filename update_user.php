<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $senha = isset($_POST['senha']) ? $_POST['senha'] : null;
    $isProfessor = $_POST['isProfessor'] == 'true' ? 'professor' : 'aluno';

    // Verifique se os dados foram recebidos corretamente
    error_log("Matrícula: $matricula, Nome: $nome, Telefone: $telefone, Endereço: $endereco, Senha: $senha");

    if ($senha) {
        $sql = "UPDATE $isProfessor SET nome = ?, telefone = ?, endereco = ?, senha = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $telefone, $endereco, $senha, $matricula);
    } else {
        $sql = "UPDATE $isProfessor SET nome = ?, telefone = ?, endereco = ? WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $telefone, $endereco, $matricula);
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
