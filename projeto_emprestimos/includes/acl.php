<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/** Retorna o nível atual (admin|comum) normalizado */
function currentRole(): string {
  return strtolower($_SESSION['nivel'] ?? 'comum');
}

function hasRole(string $role): bool {
  return currentRole() === strtolower($role);
}

/** Exige que o usuário tenha um dos papéis informados */
function requireRole(array $roles): void {
  if (empty($_SESSION['id_usuario'])) {
    $next = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
    header("Location: login.php?msg=Faça login para continuar&next={$next}");
    exit;
  }
  $nivel = currentRole();
  $roles = array_map('strtolower', $roles);
  if (!in_array($nivel, $roles, true)) {
    header("Location: 403.php"); // crie sua página de acesso negado
    exit;
  }
}
