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
    $email = trim($_POST["email"]);

    // Verificar se o e-mail existe no banco de dados
    $sql = "SELECT id FROM professores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Gerar código de recuperação (6 dígitos)
        $codigo = rand(100000, 999999);
        $_SESSION["codigo_recuperacao"] = $codigo;
        $_SESSION["email_recuperacao"] = $email;

        // Aqui você pode integrar com um serviço de e-mail (como PHPMailer) para enviar o código
        // Por enquanto, apenas exibe na tela para testes
        $mensagem = "<p class='sucesso'>Código enviado! Use <strong>$codigo</strong> para redefinir sua senha.</p>";
    } else {
        $mensagem = "<p class='erro'>E-mail não encontrado!</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
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
    <h2>Recuperação de Senha</h2>
    <?php echo $mensagem; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Digite seu e-mail cadastrado" required>
        <button type="submit" class="btn">Enviar Código</button>
    </form>
    <a href="index.php" class="link">Voltar ao Login</a>
</div>

</body>
</html>
