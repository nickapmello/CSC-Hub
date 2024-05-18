<?php
$host = 'localhost';
$dbname = 'cschub'; 
$user = 'root';          
$password = '';           

// Criando a conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
