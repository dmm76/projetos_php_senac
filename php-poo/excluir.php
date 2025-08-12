<?php

include_once("includes/classes/Aluno.php");

$bd = new Database();
$aluno = new Aluno($bd);

if(isset($_GET['idAluno'])){
    $idAluno = $_GET['idAluno'];

    echo $idAluno;

    if($aluno->deletar($idAluno)){
        header("Location: index.php?Excluido com sucesso");
    }
}
?>