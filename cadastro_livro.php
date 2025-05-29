<?php
session_start();
// include('protege.php'); // Descomente para proteger a página se necessário
include('config.php');  // Conexão com banco de dados via PDO

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_livro = $_POST['nome_livro'];
    $nome_autor = $_POST['nome_autor'];
    $isbn = $_POST['isbn'];

    try {
        // Insere os dados no banco
        $query = "INSERT INTO livros (titulo, autor, isbn) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $nome_livro);
        $stmt->bindParam(2, $nome_autor);
        $stmt->bindParam(3, $isbn);

        if ($stmt->execute()) {
            $sucesso = "Livro cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar o livro.";
        }
    } catch (PDOException $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livros</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar externa -->
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-3">Cadastro de Livros</h1>

        <!-- Botão de Voltar -->
        <a href="javascript:history.back()" class="btn btn-secondary mb-4">← Voltar</a>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <!-- Campo de busca (com API do Google Books) -->
        <div class="mb-3">
            <label for="search" class="form-label">Buscar Livro</label>
            <input type="text" id="search" class="form-control" placeholder="Digite o título ou ISBN (mínimo 3 letras)" autocomplete="off">
        </div>
        <div id="search-results" class="row"></div>

        <!-- Modal manual para cadastro direto -->
        <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cadastroModalLabel">Cadastrar Livro Manualmente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="cadastro_livro.php" method="POST">
                            <div class="mb-3">
                                <label for="nome_livro" class="form-label">Título do Livro</label>
                                <input type="text" name="nome_livro" id="nome_livro" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nome_autor" class="form-label">Autor(es)</label>
                                <input type="text" name="nome_autor" id="nome_autor" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" name="isbn" id="isbn" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Busca com Google Books API -->
    <script>
        $('#search').on('input', function () {
            const searchQuery = $(this).val();

            if (searchQuery.length >= 3) {
                $.ajax({
                    url: `https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(searchQuery)}`,
                    method: 'GET',
                    success: function (response) {
                        $('#search-results').empty();

                        if (response.totalItems > 0) {
                            response.items.slice(0, 10).forEach(book => {
                                const info = book.volumeInfo;
                                const title = info.title || 'Sem título';
                                const authors = info.authors ? info.authors.join(', ') : 'Autor desconhecido';
                                const isbn13 = (info.industryIdentifiers || []).find(id => id.type === 'ISBN_13')?.identifier || 'Sem ISBN';
                                const description = info.description || 'Sem descrição disponível';
                                const categories = info.categories ? info.categories.join(', ') : 'Sem categorias';
                                const imageLink = info.imageLinks?.thumbnail || 'https://via.placeholder.com/128x190.png?text=Sem+Capa';

                                const card = `
                                    <div class="col-12 col-md-4 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            <img src="${imageLink}" class="card-img-top" alt="Capa do livro">
                                            <div class="card-body">
                                                <h5 class="card-title">${title}</h5>
                                                <p><strong>Autor:</strong> ${authors}</p>
                                                <p><strong>ISBN:</strong> ${isbn13}</p>
                                                <p><strong>Categorias:</strong> ${categories}</p>
                                                <form method="POST" action="cadastro_livro.php">
                                                    <input type="hidden" name="nome_livro" value="${title}">
                                                    <input type="hidden" name="nome_autor" value="${authors}">
                                                    <input type="hidden" name="isbn" value="${isbn13}">
                                                    <button type="submit" class="btn btn-success w-100">Cadastrar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#search-results').append(card);
                            });
                        } else {
                            $('#search-results').html('<p class="text-muted">Nenhum livro encontrado.</p>');
                        }
                    }
                });
            } else {
                $('#search-results').empty();
            }
        });
    </script>
</body>
</html>
