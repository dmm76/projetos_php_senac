<?php
include_once("includes/classes/Ferramenta.php");

require_once 'includes/auth.php';
require_once 'includes/acl.php';
requireLogin();
requireRole(['admin']);

$bd = new Database();
$ferramenta = new Ferramenta($bd);

if (isset($_GET['idferramenta'])) {
	$idFerramenta = $_GET['idferramenta'];

	if ($ferramenta->deletar($idFerramenta)) {
		header("Location: ferramentas.php?Excluído com sucesso!");
	}
}
