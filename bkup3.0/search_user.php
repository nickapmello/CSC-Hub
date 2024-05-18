<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Recebido POST em search_user.php"); // Log para debug

    $matricula = $_POST['matricula'];
    $isProfessor = $_POST['isProfessor'] == 'true' ? 'professor' : 'aluno';

    error_log("Matrícula: $matricula, Tabela: $isProfessor"); // Log para debug

    // Preparar a query SQL para buscar a matrícula na tabela correta
    $sql = "SELECT nome FROM $isProfessor WHERE matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Matrícula encontrada em: " . $row['nome'];
    } else {
        echo "Matrícula não encontrada.";
    }

    $stmt->close();
    $conn->close();
}
?>
