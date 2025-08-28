<?php
session_start();

if (empty($_SESSION['user'])) {
  header('Location: login.php'); exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="pt-br">
<head><meta charset="utf-8"><title>Área do Usuário</title></head>
<body>
  <h2>Olá, <?= htmlspecialchars($user['email']) ?> (nível: <?= htmlspecialchars($user['nivel']) ?>)</h2>
  <p><a href="sair.php">Sair</a></p>
</body>
</html>
