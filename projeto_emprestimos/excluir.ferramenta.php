<?php
	
	include_once("includes/classes/Ferramenta.php");

	// if (!isset($_SESSION['idUsuario'])) {
	// 	header("Location: login.php?Você precisa estar logado!");
	// 	exit();
	// }

	$bd = new Database();
	$ferramenta = new Ferramenta($bd);

	if (isset($_GET['idferramenta'])) {
		$idFerramenta = $_GET['idferramenta'];

		if ($ferramenta->deletar($idFerramenta)) {
			header("Location: ferramentas.php?Excluído com sucesso!");
		}
	}


?>