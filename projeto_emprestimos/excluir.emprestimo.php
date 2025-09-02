<?php
	
	include_once("includes/classes/Emprestimo.php");

	// if (!isset($_SESSION['idUsuario'])) {
	// 	header("Location: login.php?Você precisa estar logado!");
	// 	exit();
	// }

	$bd = new Database();
	$emprestimo = new Emprestimo($bd);

	if (isset($_GET['idEmprestimo'])) {
		$idEmprestimo = $_GET['idEmprestimo'];

		if ($emprestimo->deletar($idEmprestimo)) {
			header("Location: emprestimos.php?Excluído com sucesso!");
		}
	}


?>