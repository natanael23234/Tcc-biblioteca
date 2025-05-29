<?php
// Configurações do banco de dados
$host = getenv('DB_HOST') ?: '127.0.0.1'; // Endereço do banco de dados, padrão para localhost
$db = 'test'; // Nome do banco de dados
$user = getenv('DB_USER') ?: 'root'; // Usuário do banco de dados
$pass = getenv('DB_PASS') ?: ''; // Senha do banco de dados

try {
    // Conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Define o modo de busca padrão
} catch (PDOException $e) {
    // Tratamento de erro de conexão
    die("Erro de conexão: " . $e->getMessage());
}
?>