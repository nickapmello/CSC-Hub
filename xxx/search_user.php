<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];

    // Função para buscar o usuário e definir o tipo
    function buscarUsuario($conn, $matricula) {
        $tables = ['aluno', 'professor', 'secretaria'];
        foreach ($tables as $table) {
            $sql = "SELECT *, '$table' as tipo FROM $table WHERE matricula = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $matricula);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        }
        return null;
    }

    $user = buscarUsuario($conn, $matricula);

    if ($user) {
        // Codificar os dados do usuário em JSON e enviá-los ao cliente
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "Matrícula não encontrada."]);
    }

    $conn->close();
}
?>
