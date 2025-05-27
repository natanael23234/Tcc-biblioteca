<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION["id"])) {
    header("Location: index.php"); // Redireciona para o login se não estiver logado
    exit(); // Encerra o script
}

// Dados de conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Cria conexão com o banco
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Carrega informações do professor logado
$id = $_SESSION["id"];
$sql = "SELECT nome, email FROM professores WHERE id = ?";
$stmt = $conn->prepare($sql); // Prepara a consulta
$stmt->bind_param("i", $id); // Passa o ID como parâmetro
$stmt->execute(); // Executa a consulta
$stmt->bind_result($nome, $email); // Associa os resultados às variáveis
$stmt->fetch(); // Pega os valores retornados
$stmt->close(); // Fecha o statement
$conn->close(); // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> <!-- Suporte a acentos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsivo -->
    <title>Dashboard - Biblioteca Cury</title> <!-- Título da aba -->

    <style>
        /* Estilos gerais da página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho azul com texto centralizado */
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Caixa central branca com sombra */
        .container {
            margin: 30px auto;
            width: 80%;
            max-width: 900px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        /* Título da seção */
        h2 {
            color: #333;
            text-align: center;
        }

        /* Agrupamento dos botões */
        .form-group {
            margin-bottom: 20px;
            text-align: center;
        }

        /* Estilo dos botões */
        .form-group button {
            width: 250px;
            padding: 15px;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #007bff;
            color: white;
            margin: 10px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Efeito hover nos botões */
        .form-group button:hover {
            background-color: #0056b3;
        }

        /* Estilo dos links */
        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Cabeçalho com saudação -->
<div class="header">
    <h1>Bem-vindo a Biblioteca</h1>
    <p>Olá, <?php echo htmlspecialchars($nome); ?> | E-mail: <?php echo htmlspecialchars($email); ?></p>
</div>

<!-- Caixa com opções do painel -->
<div class="container">
    <h2>Selecione uma Opção</h2>

    <!-- Botão para acessar a biblioteca -->
    <div class="form-group">
        <a href="cadastro_livro.php">
            <button>Ir para a Biblioteca</button>
        </a>
    </div>

    <!-- Botão para sair e voltar ao login -->
    <div class="form-group">
        <a href="logout.php">
            <button>voltar ao login</button>
        </a>
    </div>

</body>
</html>
