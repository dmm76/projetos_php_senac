<?php

include_once("includes/classes/Paciente.php");

$bd = new Database();
$paciente = new Paciente($bd);

if(isset($_GET['idPaciente'])){
    $idPaciente = $_GET['idPaciente'];

    echo $idPaciente;

    if($paciente->deletar($idPaciente)){
        header("Location: pacientes.php?Usuário excluído com sucesso");       
    }else{
        header("Location: pacientes.php?Erro ao excluir paciente");  
    }
}
?>