<?php
include_once("includes/conexao.php");
include_once("includes/classes/Solicitacao.php");

//solicitacoes(idSolicitacoes, cadastro, idSolicitante, idPaciente, idMedico)

$bd = new Database();
$solicitacao = new Solicitacao($bd);
$solicitacoes = $solicitacao->listar();
if($_SERVER['REQUEST_METHOD']=='POST'){
    $data = [
        'idSolicitante' => $_POST['idSolicitante'],
        'idPaciente' => $_POST['idPaciente'],
        'idMedico' => $_POST['idMedico'],        
    ];
    if($solicitacao->inserir($data)){
        header("Location: solicitacoes.php?msg=Deu certo");
    }else{
        header("Location: solicitacoes.php?msg=Deu erro!");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro Usuarios</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="row">
            <form action="" method="POST">
                <input type="hidden" name="idUsuario">                
                <div class="mb-3">
                    <label for="idSolicitante" class="form-label">id Solicitante</label>
                    <input type="text" class="form-control" id="idSolicitante" name="idSolicitante" placeholder="Digite o idSolicitante">
                </div>
                <div class="mb-3">
                    <label for="idPaciente" class="form-label">id Paciente</label>
                    <input type="password" class="form-control" id="idPaciente" name="idPaciente" placeholder="Digite o idPaciente">
                </div>
                <div class="mb-3">
                    <label for="idMedico" class="form-label">Id Médico</label>
                    <input type="text" class="form-control" id="idMedico" name="idMedico" placeholder="Digite o idMedico">
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>

</body>

</html>