<?php
// Iniciar a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário não está logado
if (!isset($_SESSION['professor_id'])) {
    // Redirecionar para a página de login se o usuário não estiver logado
    header('Location: index.php');
    exit();
}

// Aqui você pode adicionar o restante do seu código que deve ser executado apenas se o usuário estiver logado
?>