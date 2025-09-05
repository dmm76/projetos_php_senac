<?php
include_once("includes/classes/Emprestimo.php");

require_once 'includes/auth.php';
require_once 'includes/acl.php';
requireLogin();
requireRole(['admin']);

$bd = new Database();
$emprestimo = new Emprestimo($bd);

if (isset($_GET['idEmprestimo'])) {
	$idEmprestimo = $_GET['idEmprestimo'];

	if ($emprestimo->deletar($idEmprestimo)) {
		header("Location: emprestimos.php?Excluído com sucesso!");
	}
}
