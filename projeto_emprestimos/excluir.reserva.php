<?php

include_once("includes/classes/Reserva.php");

require_once 'includes/auth.php';
require_once 'includes/acl.php';
requireLogin();
requireRole(['admin']);

$bd = new Database();
$reserva = new reserva($bd);

if (isset($_GET['idReserva'])) {
	$idreserva = $_GET['idReserva'];

	if ($reserva->deletar($idreserva)) {
		header("Location: emprestimos.php?Excluído com sucesso!");
	}
}
