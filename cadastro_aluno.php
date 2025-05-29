<?php 
include('navbar.php'); 
include('config.php'); // conexão PDO

// Processa o formulário quando enviado
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpa e pega os dados do POST
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $turma = trim($_POST['turma'] ?? ''); // ajuste aqui o nome do campo, ex: turma ou serie

    // Valida campos
    if ($nome === '' || $email === '' || $turma === '') {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos corretamente.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "<div class='alert alert-warning'>E-mail inválido.</div>";
    } else {
        // Tenta inserir no banco
        try {
            $sql = "INSERT INTO alunos (nome, email, turma) VALUES (:nome, :email, :turma)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':turma', $turma);

            if ($stmt->execute()) {
                $mensagem = "<div class='alert alert-success'>Aluno cadastrado com sucesso!</div>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao cadastrar aluno.</div>";
            }
        } catch (PDOException $e) {
            $mensagem = "<div class='alert alert-danger'>Erro: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Cadastro de Alunos</h1>

    <!-- Mensagem de status -->
    <?= $mensagem ?>

    <!-- Formulário -->
    <form action="cadastro_aluno.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Aluno</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="turma" class="form-label">Turma</label>
            <input type="text" class="form-control" id="turma" name="turma" value="<?= htmlspecialchars($_POST['turma'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar Aluno</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
    
</div>

</body>
</html>
