<?php

include_once("includes/classes/Receita.php");

$bd = new Database();
$receita = new Receita($bd);

if(isset($_GET['idReceita'])){
    $idReceita = $_GET['idReceita'];

    echo $idReceita;

    if($receita->deletar($idReceita)){
        header("Location: index.php?Excluido com sucesso");
    }
}
?>