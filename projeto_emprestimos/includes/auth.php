<?php
// Inicie a sessão aqui (ou use um bootstrap comum do sistema)
function requireLogin(): void
{
  // AGORA checa as chaves que você usa no login.php
  if (empty($_SESSION['id_usuario'])) {
    echo $_SESSION['id_usuario'];
    // opcional: preserve a URL alvo para redirecionar depois do login
    $next = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
    header("Location: login.php?msg=Faça login para continuar&next={$next}");
    exit;
  }
}
