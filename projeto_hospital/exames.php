<?php
include_once("includes/conexao.php");
include_once("includes/classes/Exame.php");


//solic_exames(idExame, idSolicitacao, cod, descricao, status, obs)

$bd = new Database();
$exames = new Exame($bd);
//$solicitacoes = $solicitacoes->listar();

if (isset($_GET['idAtendimento'])) {

    $idAtendimento = $_GET['idAtendimento'];
   

    //echo $nome; //teste tem tela

} else {
    $idPaciente = 0;
    $cpf = 0;
    $nome = "";
    $email = "";
    $telefone = "";
    $endereco = "";
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
                <div class="row mb-3">
                    <div class="col-md-4">
                    <label for="idSolicitante" class="form-label">id Solicitante</label>
                    <input type="number" min =0 class="form-control" id="idSolicitante" name="idSolicitante" placeholder="Digite o idSolicitante">
                </div>
                <div class="col-md-4">
                    <label for="idPaciente" class="form-label">id Paciente</label>
                    <input type="number" min=0 class="form-control" id="idPaciente" name="idPaciente" placeholder="Digite o idPaciente">
                </div>
                <div class="col-md-4">
                    <label for="idAtendimento" class="form-label">Id Atendimento</label>
                    <input type="number" min=0 class="form-control" id="idAtendimento" name="idAtendimento" placeholder="Digite o id do Atendimento">
                </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>

</body>

</html>