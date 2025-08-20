<?php

include_once("includes/classes/Atendimento.php");

$bd = new Database();
$atendimento = new Atendimento($bd);

if(isset($_GET['idAtendimento'])){
    $idAtendimento = $_GET['idAtendimento'];

    echo $idAtendimento;

    if($atendimento->deletar($idAtendimento)){
        header("Location: atendimentos.php?Atendimento excluído com sucesso");       
    }
}
?>