<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);  // Debug: Exibir dados recebidos do formulário
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

    if (empty($senha)) {
        echo "A senha não pode ser vazia.";
        exit;
    }

    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
    $matricula = gerarMatricula($conn);  // Gerar matrícula usando função pré-definida

    // Preparar a instrução SQL
    $sqlInsert = "INSERT INTO aluno (nome, data, genero, naturalidade, endereco, CEP, Nome_Pai, CPF_Pai, Telefone_Pai, Nome_Mae, CPF_Mae, Telefone_Mae, info_saude, medicamento, email, status, matricula, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssssssssssssssss", $nome, $data, $genero, $naturalidade, $endereco, $CEP, $Nome_Pai, $CPF_Pai, $Telefone_Pai, $Nome_Mae, $CPF_Mae, $Telefone_Mae, $info_saude, $medicamento, $email, $status, $matricula, $senha_criptografada);

    // Executar a inserção
    if (!$stmtInsert->execute()) {
        echo "Erro ao criar registro de aluno: " . $stmtInsert->error;
    } else {
        echo "<script>alert('Registro de aluno criado com sucesso!'); window.location.href = 'http://localhost/estagio/xxx/index.html';</script>";
    }

    $stmtInsert->close();
    $conn->close();
}
?>
