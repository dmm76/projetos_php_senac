<?php

include_once("includes/classes/Usuario.php");
//include_once("includes/validacao.php");
$bd = new Database();
$usuario = new Usuario($bd);

if(isset($_GET['idUsuario'])){
    $idUsuario = $_GET['idUsuario'];

    echo $idUsuario;

    if($usuario->deletar($idUsuario)){
        header("Location: usuarios.php?Usuário excluído com sucesso");       
    }
}
?>