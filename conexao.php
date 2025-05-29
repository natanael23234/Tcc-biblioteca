<?php
$host = "localhost";
$user = "root"; // Seu usuário do MySQL
$password = ""; // Sua senha do MySQL
$dbname = "biblioteca"; // Nome do banco

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
