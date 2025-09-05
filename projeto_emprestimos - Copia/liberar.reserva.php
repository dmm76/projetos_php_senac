<?php
require_once 'includes/conexao.php';
require_once 'includes/classes/Reserva.php';
require_once 'includes/auth.php';
require_once 'includes/acl.php';

requireLogin();
requireRole(['admin']); // só admin libera

$bd = new Database();
$reserva = new Reserva($bd);

$idReserva = (int)($_GET['idReserva'] ?? 0);

if ($idReserva > 0 && $reserva->liberarParaEmprestimo($idReserva)) {
  header("Location: emprestimos.php?msg=Reserva liberada com sucesso!");
  exit;
} else {
  header("Location: emprestimos.php?msg=Erro ao liberar reserva!");
  exit;
}
