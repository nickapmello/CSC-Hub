<?php
session_start(); // Inicia a sessão PHP

include '../xxx/db.php'; // Inclui o arquivo de conexão do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];
    
    // Função para buscar o usuário na tabela usuario
    function buscarUsuario($conn, $matricula) {
        $sql = "SELECT * FROM usuario WHERE matricula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Função para converter tipo para caixa alta
    function tipoCaixaAlta($tipo) {
        return strtoupper($tipo);
    }

    $user = buscarUsuario($conn, $matricula);

    if ($user) {
        $senha_armazenada = $user['senha'];
        // Verifica se a senha inserida corresponde à hash armazenada no banco
        if (password_verify($senha, $senha_armazenada)) {
            $tipo = $user['tipo'];
            $user_id = $user['id'];

            // Buscar detalhes do usuário na tabela correspondente
            if ($tipo === 'aluno') {
                $sql = "SELECT * FROM aluno WHERE user_id = ?";
            } elseif ($tipo === 'professor') {
                $sql = "SELECT * FROM professor WHERE user_id = ?";
            } else {
                $sql = "SELECT * FROM secretaria WHERE user_id = ?";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $userDetails = $result->fetch_assoc();
                $_SESSION['usuario'] = $userDetails['nome'];
                $_SESSION['matricula'] = $user['matricula'];
                $_SESSION['tipo'] = $tipo;

                $tipoFormatado = tipoCaixaAlta($tipo);
                // Exibe uma mensagem de sucesso no login
                echo "<script>alert('Login bem-sucedido! Bem-vindo, $tipoFormatado " . $userDetails['nome'] . "!'); window.location.href = 'index.html';</script>";
                exit;
            } else {
                echo "<script>alert('Usuário não encontrado.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Senha incorreta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Matrícula não encontrada.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
