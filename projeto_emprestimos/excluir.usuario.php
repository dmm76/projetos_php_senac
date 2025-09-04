<?php

include_once("includes/classes/Usuario.php");
require_once 'includes/auth.php';
require_once 'includes/acl.php';
requireLogin();
requireRole(['admin']);

$bd = new Database();
$usuario = new Usuario($bd);

if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    echo $idUsuario;

    if ($usuario->deletar($idUsuario)) {
        header("Location: usuarios.php?Usuário excluído com sucesso");
    }
}
