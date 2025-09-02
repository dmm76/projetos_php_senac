<?php
	
	include_once("includes/classes/Usuario.php");

	// if (!isset($_SESSION['idUsuario'])) {
	// 	header("Location: login.php?Você precisa estar logado!");
	// 	exit();
	// }

	$bd = new Database();
	$usuario = new Usuario($bd);

	if (isset($_GET['idUsuario'])) {
		$idUsuario = $_GET['idUsuario'];

		if ($usuario->deletar($idUsuario)) {
			header("Location: usuarios.php?Excluído com sucesso!");
		}
	}


?>