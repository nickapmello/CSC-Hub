<?php
include 'db.php';
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta dos dados do formulário
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? NULL;  // NULL se não for fornecido
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $info_saude = $_POST['info_saude'] ?? '';
    $documento_identidade = $_POST['documento_identidade'] ?? '';
    $nome_pais = $_POST['nome_pais'] ?? '';
    $telefone_pais = $_POST['telefone_pais'] ?? '';
    $cpf_pais = $_POST['cpf_pais'] ?? '';
    $status = $_POST['status'] ?? 'Ativo';  // Default to 'Ativo' if not provided
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'] ?? 'aluno'; // Assume 'aluno' se nenhum tipo for fornecido

    // Hash da senha (aqui assumindo que o campo 'senha' é enviado via POST)
    $senha = $_POST['senha'] ?? '';
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Geração da matrícula
    $matricula = gerarMatricula($conn);

    // Preparação do SQL para inserção
    $sqlInsert = "INSERT INTO $tipo (matricula, nome, cpf, data_nascimento, telefone, email, endereco, documento_identidade, info_saude, nome_pais, telefone_pais, cpf_pais, status, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssssssssssssss", $matricula, $nome, $cpf, $data_nascimento, $telefone, $email, $endereco, $documento_identidade, $info_saude, $nome_pais, $telefone_pais, $cpf_pais, $status, $senha_criptografada);

    // Execução e verificação
    if ($stmtInsert->execute()) {
        echo "<script>alert('Registro criado com sucesso!'); window.location.href = 'http://localhost/estagio/index.html';</script>";
    } else {
        echo "Erro ao criar registro: " . $stmtInsert->error;
    }

    $stmtInsert->close();
    $conn->close();
}
?>
