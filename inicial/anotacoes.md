Tabelas SQL

CREATE TABLE aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    matricula VARCHAR(12) NOT NULL UNIQUE,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo'
);

CREATE TABLE professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    matricula VARCHAR(12) NOT NULL UNIQUE,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo'
);
================================================

$host = 'localhost';
$dbname = 'cschub'; 
$user = 'root';          
$password = '';          


CREATE TABLE aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome_completo VARCHAR(150) NOT NULL,
    data_nascimento DATE,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    endereco_residencial VARCHAR(100),
    telefone_contato VARCHAR(20),
    email VARCHAR(100),
    info_saude TEXT,
    documento_identidade TEXT,
    nome_pais VARCHAR(255),
    telefone_pais VARCHAR(20),
    cpf_pais VARCHAR(14),
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    senha VARCHAR(100) NOT NULL
);


CREATE TABLE aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    data DATE,
    genero VARCHAR(20),
    naturalidade VARCHAR(20),
    endereco VARCHAR(150),
    CEP VARCHAR(15),
    Nome_Pai VARCHAR(150),
    CPF_Pai VARCHAR(14),
    Telefone_Pai VARCHAR(14),
    Nome_Mae VARCHAR(150),
    CPF_Mae VARCHAR(14),
    Telefone_Mae VARCHAR(14),
    info_saude VARCHAR(255),
    medicamento VARCHAR(255),
    email VARCHAR(100),
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    senha VARCHAR(100) NOT NULL
);