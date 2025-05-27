<?php 
include('navbar.php'); // Inclui a barra de navegação

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $cpf = preg_replace("/[^0-9]/", "", $_POST["cpf"]);
    $email = trim($_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    if (!preg_match("/^[0-9]{11}$/", $cpf)) {
        $mensagem = "<p class='mensagem erro'>CPF inválido! Use apenas números.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "<p class='mensagem erro'>E-mail inválido!</p>";
    } else {
        $sql_check = "SELECT id FROM professores WHERE cpf = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $cpf, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $mensagem = "<p class='mensagem erro'>Erro: CPF ou e-mail já cadastrados!</p>";
        } else {
            $sql = "INSERT INTO professores (nome, cpf, email, senha) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $cpf, $email, $senha);

            if ($stmt->execute()) {
                $mensagem = "<p class='mensagem sucesso'>Cadastro realizado com sucesso! <a href='index.php'>Fazer login</a></p>";
            } else {
                $mensagem = "<p class='mensagem erro'>Erro ao cadastrar. Tente novamente.</p>";
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Professor</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            margin-top: 120px; /* espaço para navbar */
            text-align: center;
        }

        h2 {
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn {
            background: blue;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background: darkblue;
        }

        .mensagem {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }

        .sucesso {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .erro {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn-voltar {
            margin-top: 15px;
            background: gray;
        }

        .btn-voltar:hover {
            background: #555;
        }

        /* Navbar fixa (caso não esteja no seu navbar.php) */
        nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastro de Professor</h2>
    <?php echo $mensagem; ?>
    <form method="POST" autocomplete="off">
    <input type="text" name="nome" id="nome" placeholder="Nome Completo" required autocomplete="off">
    <input type="text" name="cpf" id="cpf" placeholder="CPF (Apenas números)" required autocomplete="off" inputmode="numeric">
    <input type="email" name="email" id="email" placeholder="E-mail" required autocomplete="off">
    <input type="password" name="senha" id="senha" placeholder="Senha" required autocomplete="new-password">
    <button type="submit" class="btn">Cadastrar</button>
</form>

    <button class="btn btn-voltar" onclick="history.back()">← Voltar</button>
</div>

</body>
</html>
