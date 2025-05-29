<?php
session_start();

// Remover todas as variáveis da sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header("Location: index.php");
exit();
?>
