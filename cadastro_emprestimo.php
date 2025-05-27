<?php include('navbar.php'); // Inclui a barra de navegação ?>

<!-- Inclusão do Bootstrap e Select2 para estilo e selects avançados -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<div class="container mt-5">
    <h1>Cadastro de Empréstimos</h1>

    <!-- Formulário para cadastro de empréstimo -->
    <form action="cadastro_emprestimo.php" method="POST">
        <!-- Seleção de Aluno -->
        <div class="mb-3">
            <label for="aluno_id" class="form-label">Aluno</label>
            <select class="form-control select2" id="aluno_id" name="aluno_id" required>
                <?php
                include('config.php'); // Inclui conexão PDO, espera-se que $pdo esteja definido

                try {
                    $result = $pdo->query("SELECT * FROM alunos");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option disabled>Erro: " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Seleção de Professor -->
        <div class="mb-3">
            <label for="professor_id" class="form-label">Professor</label>
            <select class="form-control select2" id="professor_id" name="professor_id" required>
                <?php
                try {
                    $result = $pdo->query("SELECT * FROM professores");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option disabled>Erro: " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Seleção de Livro -->
        <div class="mb-3">
            <label for="livro_id" class="form-label">Livro</label>
            <select class="form-control select2" id="livro_id" name="livro_id" required>
                <?php
                try {
                    $result = $pdo->query("SELECT * FROM livros");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        // Campo 'titulo' usado no select
                        echo "<option value='{$row['id']}'>" . htmlspecialchars($row['titulo']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option disabled>Erro: " . htmlspecialchars($e->getMessage()) . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Data de Empréstimo -->
        <div class="mb-3">
            <label for="data_emprestimo" class="form-label">Data de Empréstimo</label>
            <input type="date" class="form-control" id="data_emprestimo" name="data_emprestimo" required>
        </div>

        <!-- Data de Devolução -->
        <div class="mb-3">
            <label for="data_devolucao" class="form-label">Data de Devolução</label>
            <input type="date" class="form-control" id="data_devolucao" name="data_devolucao" required>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Empréstimo</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $aluno_id = filter_input(INPUT_POST, 'aluno_id', FILTER_VALIDATE_INT);
        $professor_id = filter_input(INPUT_POST, 'professor_id', FILTER_VALIDATE_INT);
        $livro_id = filter_input(INPUT_POST, 'livro_id', FILTER_VALIDATE_INT);
        $data_emprestimo = $_POST['data_emprestimo'];
        $data_devolucao = $_POST['data_devolucao'];

        if ($aluno_id && $professor_id && $livro_id && $data_emprestimo && $data_devolucao) {
            try {
                $sql = "INSERT INTO emprestimos (aluno_id, professor_id, livro_id, data_emprestimo, data_devolucao) 
                        VALUES (:aluno_id, :professor_id, :livro_id, :data_emprestimo, :data_devolucao)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':aluno_id', $aluno_id);
                $stmt->bindParam(':professor_id', $professor_id);
                $stmt->bindParam(':livro_id', $livro_id);
                $stmt->bindParam(':data_emprestimo', $data_emprestimo);
                $stmt->bindParam(':data_devolucao', $data_devolucao);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success mt-3'>Empréstimo cadastrado com sucesso!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Erro ao cadastrar empréstimo.</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger mt-3'>Erro: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning mt-3'>Por favor, preencha todos os campos corretamente.</div>";
        }
    }
    ?>
</div>

<!-- Ativa o Select2 para os selects -->
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
