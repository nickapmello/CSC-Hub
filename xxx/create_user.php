<?php
include 'db.php';
include 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    $nome_completo = $_POST['nome_completo'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $endereco_residencial = $_POST['endereco_residencial'];
    $telefone_contato = $_POST['telefone_contato'];
    $email = $_POST['email'];
    $info_saude = $_POST['info_saude'];
    $documento_identidade = $_POST['documento_identidade'];
    $nome_pais = $_POST['nome_pais'];
    $telefone_pais = $_POST['telefone_pais'];
    $cpf_pais = $_POST['cpf_pais'];
    $status = $_POST['status'];
    $senha = $_POST['senha']; // Considerando que a senha já vem do formulário

    if (empty($senha)) {
        echo "A senha não pode ser vazia.";
        exit;
    }
    
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Gerar matrícula
    $matricula = gerarMatricula($conn);

    // Instrução SQL para inserção
    $sqlInsert = "INSERT INTO aluno (nome_completo, data_nascimento, cpf, endereco_residencial, telefone_contato, email, info_saude, documento_identidade, nome_pais, telefone_pais, cpf_pais, status, matricula, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssssssssssss", $nome_completo, $data_nascimento, $cpf, $endereco_residencial, $telefone_contato, $email, $info_saude, $documento_identidade, $nome_pais, $telefone_pais, $cpf_pais, $status, $matricula, $senha_criptografada);

    // Executar inserção
    if (!$stmtInsert->execute()) {
        echo "Erro ao criar registro de aluno: " . $stmtInsert->error;
    } else {
        echo "<script>alert('Registro de aluno criado com sucesso!'); window.location.href = 'http://localhost/estagio/xxx/index.html';</script>";
    }    

    $stmtInsert->close();
    $conn->close();
}
?>
