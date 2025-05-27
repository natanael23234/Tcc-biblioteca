<?php
include('config.php');
include('navbar.php');

// Excluir empréstimo se solicitado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $idExcluir = intval($_POST['excluir_id']);
    $stmtExcluir = $pdo->prepare("DELETE FROM emprestimos WHERE id = ?");
    $stmtExcluir->execute([$idExcluir]);
}

// Buscar empréstimos
$sql = "
SELECT 
    e.id,
    e.data_emprestimo AS data_retirada,
    e.data_devolucao AS data_devolucao,
    a.nome AS nome_aluno,
    l.titulo AS nome_livro
FROM emprestimos e
JOIN alunos a ON e.aluno_id = a.id
JOIN livros l ON e.livro_id = l.id
ORDER BY e.data_emprestimo DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

date_default_timezone_set('America/Sao_Paulo');
$data_hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .atrasado {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Empréstimos</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Livro</th>
                <th>Data de Empréstimo</th>
                <th>Data de Devolução</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emprestimos as $emprestimo): 
                $atrasado = ($emprestimo['data_devolucao'] < $data_hoje);
            ?>
                <tr>
                    <td class="<?= $atrasado ? 'atrasado' : '' ?>">
                        <?= htmlspecialchars($emprestimo['nome_aluno']) ?>
                    </td>
                    <td><?= htmlspecialchars($emprestimo['nome_livro']) ?></td>
                    <td><?= date('d/m/Y', strtotime($emprestimo['data_retirada'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($emprestimo['data_devolucao'])) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este empréstimo?');">
                            <input type="hidden" name="excluir_id" value="<?= $emprestimo['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botão de Voltar -->
    <button onclick="history.back()" class="btn btn-secondary mt-3">Voltar</button>
</div>
</body>
</html>
