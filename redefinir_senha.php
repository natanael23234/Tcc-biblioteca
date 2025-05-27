<?php
session_start();
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
    $codigo = $_POST["codigo"];
    $nova_senha = password_hash($_POST["nova_senha"], PASSWORD_DEFAULT);

    if (isset($_SESSION["codigo_recuperacao"]) && $_SESSION["codigo_recuperacao"] == $codigo) {
        $email = $_SESSION["email_recuperacao"];

        $sql = "UPDATE professores SET senha = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nova_senha, $email);

        if ($stmt->execute()) {
            $mensagem = "<p class='sucesso'>Senha redefinida com sucesso! <a href='index.php'>Fazer login</a></p>";
            unset($_SESSION["codigo_recuperacao"]);
            unset($_SESSION["email_recuperacao"]);
        } else {
            $mensagem = "<p class='erro'>Erro ao redefinir senha.</p>";
        }
        $stmt->close();
    } else {
        $mensagem = "<p class='erro'>Código incorreto!</p>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2); width: 400px; text-align: center; }
        h2 { color: #333; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        .btn { background: blue; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; width: 100%; }
        .btn:hover { background: darkblue; }
        .mensagem { padding: 10px; border-radius: 5px; margin-top: 10px; font-size: 14px; }
        .sucesso { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .erro { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .link { display: block; margin-top: 10px; color: blue; text-decoration: none; }
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Redefinir Senha</h2> 
    <?php echo $mensagem; ?>
    <form method="POST">
        <input type="text" name="codigo" placeholder="Digite o código recebido" required>
        <input type="password" name="nova_senha" placeholder="Nova Senha" required>
        <button type="submit" class="btn">Redefinir Senha</button>
    </form>
    <a href="index
200 