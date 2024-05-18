Tabelas SQL

CREATE TABLE aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    matricula VARCHAR(12) NOT NULL UNIQUE
);

CREATE TABLE professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    matricula VARCHAR(12) NOT NULL UNIQUE
);

==================================================

