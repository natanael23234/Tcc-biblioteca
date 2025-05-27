<!-- navbar.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .navbar-custom {
        background-color: #A3D8F4; /* Azul claro personalizado */
    }
    .navbar-custom .navbar-brand, .navbar-custom .nav-link {
        color: black; /* Cor preta nas letras */
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="professores_dashboard.php">Biblioteca</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_livro.php">Cadastro de Livros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_professor.php">Cadastro de Professores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_aluno.php">Cadastro de alunos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tabela_emprestimos.php">tabela de emprestimo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cadastro_emprestimo.php">Cadastro de Empréstimos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
