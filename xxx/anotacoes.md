CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    tipo ENUM('aluno', 'professor', 'secretaria') NOT NULL,
    status ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo'
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
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES usuario(id)
);

CREATE TABLE professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE,
    genero ENUM('Masculino', 'Feminino', 'Outro'),
    email VARCHAR(100),
    telefone VARCHAR(15),
    endereco VARCHAR(255),
    cep VARCHAR(10),
    formacao_academica VARCHAR(255),
    area_ensino VARCHAR(100),
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES usuario(id)
);

CREATE TABLE secretaria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    telefone VARCHAR(15),
    email VARCHAR(100),
    endereco VARCHAR(150),
    cep VARCHAR(15),
    status ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES usuario(id)
);
    
/-/-/--/-/-/--/-/-/-/--/-/--/-/-/-/-/--/-/-/--/-/--/-/-/--/-/-/-/-/
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

CREATE TABLE professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE,
    genero ENUM('Masculino', 'Feminino', 'Outro'),
    email VARCHAR(100),
    telefone VARCHAR(15),
    endereco VARCHAR(255),
    cep VARCHAR(10),
    formacao_academica VARCHAR(255),
    area_ensino VARCHAR(100),
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    senha VARCHAR(100) NOT NULL
);

CREATE TABLE secretaria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    telefone VARCHAR(15),
    email VARCHAR(100),
    endereco VARCHAR(150),
    cep VARCHAR(15),
    status ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    senha VARCHAR(100) NOT NULL
);

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(12) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    tipo ENUM('aluno', 'professor', 'secretaria') NOT NULL,
    status ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo'
);

ALTER TABLE aluno ADD COLUMN user_id INT;
ALTER TABLE aluno ADD CONSTRAINT fk_aluno_user_id FOREIGN KEY (user_id) REFERENCES usuario(id);

ALTER TABLE professor ADD COLUMN user_id INT;
ALTER TABLE professor ADD CONSTRAINT fk_professor_user_id FOREIGN KEY (user_id) REFERENCES usuario(id);

ALTER TABLE secretaria ADD COLUMN user_id INT;
ALTER TABLE secretaria ADD CONSTRAINT fk_secretaria_user_id FOREIGN KEY (user_id) REFERENCES usuario(id);