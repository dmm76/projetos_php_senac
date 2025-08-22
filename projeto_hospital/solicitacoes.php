<?php
include_once("includes/conexao.php");
include_once("includes/classes/Solicitacao.php");

//solicitacoes(idSolicitacoes, cadastro, idSolicitante, idPaciente, idMedico)

$bd = new Database();
$solicitacao = new Solicitacao($bd);
$solicitacoes = $solicitacao->listar();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idSolicitacoes' => $_POST['idSolicitacoes'],
        'idSolicitante' => $_POST['idSolicitante'],
        'idPaciente' => $_POST['idPaciente'],
        'idMedico' => $_POST['idMedico'],
    ];
    if ($solicitacao->inserir($data)) {
        header("Location: solicitacoes.php?msg=Deu certo");
    } else {
        header("Location: solicitacoes.php?msg=Deu erro!");
    }
}
if (isset($_GET['idSolicitacoes'])) {
    $idSolicitacoes = $_GET['idSolicitacoes'];

    $SolicitacaoModel = new Solicitacao($bd);
    $SolicitacaoDados = $SolicitacaoModel->buscar($idSolicitacoes);

    $idSolicitacoes = $SolicitacaoDados['idSolicitacoes'];
    $idSolicitante = $SolicitacaoDados['idSolicitante'];
    $idPaciente = $SolicitacaoDados['idPaciente'];
    $idMedico = $SolicitacaoDados['idMedico'];  

    //echo $nome; //teste tem tela

} else {
    $idSolicitacoes = 0;    
    $idSolicitante = "";
    $idPaciente = "";
    $idMedico = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro Solicitacaos</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="row">
            <form action="" method="POST">
                <input type="" name="idSolicitacoes">
                <di class="row">
                    <div class="mb-3 col-sm-4" >
                        <label for="idSolicitante" class="form-label">id Solicitante</label>
                        <input type="number" class="form-control" id="idSolicitante" name="idSolicitante" placeholder="Digite o idSolicitante">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idPaciente" class="form-label">id Solicitacao</label>
                        <input type="number" class="form-control" id="idPaciente" name="idPaciente" placeholder="Digite o idPaciente">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idMedico" class="form-label">Id Médico</label>
                        <input type="number" class="form-control" id="idMedico" name="idMedico" placeholder="Digite o idMedico">
                    </div>
                </di>

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th>Id</th>
                    <th>Id Solicitante</th>
                    <th>Id Solicitação</th>
                    <th>Id Médico</th>                    
                    <th>Ações</th>
                </tr>
                <?php
                foreach ($solicitacoes as $solicitacao) {
                    echo '
                            <tr>
                                <td>' . $solicitacao['idSolicitacoes'] . '</td>
                                <td>' . $solicitacao['idSolicitante'] . '</td>
                                <td>' . $solicitacao['idPaciente'] . '</td>    
                                <td>' . $solicitacao['idMedico'] . '</td>                                    
                                <td>
                                    <a href="?idSolicitacoes=' . $solicitacao['idSolicitacoes'] . '">Editar</a>
                                    <a onclick="return confirm(\'Deseja realmente excluir?\');"
                                    href="excluirsolicitacoes.php?idSolicitacoes=' . $solicitacao['idSolicitacoes'] . '">Excluir</a>
                                </td>
                            </tr>       
                        ';
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>