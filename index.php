<?php
// Dados para conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Cria conexão usando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializa mensagem de erro ou sucesso
$mensagem = "";

// Verifica se foi enviado o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $cpf = $_POST["cpf"]; // Pega o CPF digitado
    $senha = $_POST["senha"]; // Pega a senha digitada

    // Remove pontos e traços do CPF (deixa só números)
    $cpf = preg_replace("/[^0-9]/", "", $cpf);

    // Consulta o professor com o CPF informado
    $sql = "SELECT id, nome, senha FROM professores WHERE cpf = ?";
    $stmt = $conn->prepare($sql); // Prepara a query
    $stmt->bind_param("s", $cpf); // Define o parâmetro
    $stmt->execute(); // Executa a consulta
    $stmt->store_result(); // Armazena o resultado
    $stmt->bind_result($id, $nome, $senha_hash); // Associa resultados às variáveis
    
    if ($stmt->num_rows > 0) { // Se encontrou alguém com esse CPF
        $stmt->fetch(); // Pega os dados do professor
        if (password_verify($senha, $senha_hash)) { // Verifica se a senha bate
            session_start(); // Inicia a sessão
            $_SESSION["id"] = $id; // Salva ID do professor
            $_SESSION["nome"] = $nome; // Salva nome do professor
            header("Location: professores_dashboard.php"); // Redireciona após login
            exit(); // Encerra o script
        } else {
            $mensagem = "<p class='erro'>Senha incorreta!</p>"; // Senha errada
        }
    } else {
        $mensagem = "<p class='erro'>Usuário não encontrado!</p>"; // CPF não cadastrado
    }
    $stmt->close(); // Fecha o statement
}
$conn->close(); // Fecha a conexão com o banco
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> <!-- Suporte a acentos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsivo -->
    <title>Login Professores - Biblioteca Cury</title> <!-- Título da aba -->

    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo geral da página */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #007bff, #00d4ff); /* Fundo em gradiente */
            text-align: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* Título principal */
        .titulo {
            font-size: 32px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Caixa branca do formulário */
        .container {
            background: white;
            padding: 40px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        /* Título do formulário */
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Agrupamento dos campos */
        .input-group {
            margin-bottom: 20px;
        }

        /* Rótulos dos inputs */
        .input-group label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        /* Campos de entrada */
        .input-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
        }

        /* Foco nos inputs */
        .input-group input:focus {
            border-color: #007bff;
        }

        /* Botão de login */
        .btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Efeito hover no botão */
        .btn:hover {
            background: #0056b3;
        }

        /* Mensagem de erro */
        .mensagem {
            margin-top: 15px;
        }

        .erro {
            color: red;
            font-weight: bold;
        }

        /* Links de recuperação/cadastro */
        .links {
            margin-top: 20px;
        }

        .links a {
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
            display: block;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Logo (caso tenha uma imagem futura) */
        .logo {
            width: 80px;
            margin-bottom: 20px;
        }

        /* Responsividade para celular */
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<!-- Título principal da página -->
<div class="titulo">📚 Biblioteca Cury</div>

<!-- Caixa do formulário -->
<div class="container">
    <h2>Login Professores</h2>

    <!-- Exibe mensagens de erro ou aviso -->
    <?php echo $mensagem; ?>

    <!-- Formulário de login -->
    <form method="POST">
        <div class="input-group">
            <label for="cpf">CPF:</label>
            <!-- Campo de CPF com validação de formato -->
            <input type="text" id="cpf" name="cpf" required placeholder="Digite seu CPF"
                   pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                   title="Digite um CPF válido (ex: 123.456.789-00)">
        </div>

        <div class="input-group">
            <label for="senha">Senha:</label>
            <!-- Campo de senha -->
            <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
        </div>

        <!-- Botão de login -->
        <input type="submit" name="login" class="btn" value="Entrar">
    </form>

    <!-- Links de ajuda e cadastro -->
    <div class="links">
        <a href="esqueci_senha.php">Esqueci a senha</a>
        <a href="cadastro_professores.php"><strong>Ainda não tenho cadastro</strong></a>
    </div>
</div>

</body>
</html>
