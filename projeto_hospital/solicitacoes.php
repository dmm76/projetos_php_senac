<?php
include_once("includes/conexao.php");
include_once("includes/classes/Solicitacao.php");
include_once("includes/validacao.php");
//solicitacoes(idSolicitacoes, cadastro, idSolicitante, idPaciente, idMedico)

$bd = new Database();
$solicitacao = new Solicitacao($bd);
$solicitacoes = $solicitacao->listar();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idSolicitacoes' => $_POST['idSolicitacoes'],
        'idSolicitante' => $_POST['idSolicitante'],
        'idPaciente' => $_POST['idPaciente'],
        'idAtendimento' => $_POST['idAtendimento'],
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
    $idAtendimento = $SolicitacaoDados['idAtendimento'];

    //echo $nome; //teste tem tela

} else {
    $idSolicitacoes = 0;
    $idSolicitante = "";
    $idPaciente = "";
    $idAtendimento = "";
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
                <input type="" name="idSolicitacoes" value='<?php echo $idSolicitacoes ?>'>
                <div class="row mt-3">
                    <div class="mb-3 col-sm-4">
                        <label for="idSolicitante" class="form-label ">id Solicitante</label>
                        <input type="number" class="form-control" id="idSolicitante" min=0 name="idSolicitante" value='<?php echo $idSolicitante ?>' placeholder="Digite o idSolicitante">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idPaciente" class="form-label">id Paciente</label>
                        <input type="number" class="form-control" id="idPaciente" min=0 name="idPaciente" value='<?php echo $idPaciente ?>' placeholder="Digite o idPaciente">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idAtendimento" class="form-label">Id Atendimento</label>
                        <input type="number" class="form-control" id="idAtendimento" min=0 name="idMedico" value='<?php echo $idAtendimento ?>' placeholder="Digite o id Atendimento">
                    </div>
                </div>

                <button  type="submit" class="btn btn-primary mb-3">Enviar</button>
            </form>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Id Solicitante</th>
                    <th class="text-center">Id Paciente</th>
                    <th class="text-center">Id Atendimento</th>
                    <th class="text-center">Ações</th>
                </tr>
                <?php
                foreach ($solicitacoes as $solicitacao) {
                    echo '
                            <tr>
                                <td class="text-center">' . $solicitacao['idSolicitacoes'] . '</td>
                                <td class="text-center">' . $solicitacao['idSolicitante'] . '</td>
                                <td class="text-center">' . $solicitacao['idPaciente'] . '</td>    
                                <td class="text-center">' . $solicitacao['idAtendimento'] . '</td>                                    
                                <td class="text-center">
                                    <a class="btn btn-warning btn-sm" href="?idSolicitacoes=' . $solicitacao['idSolicitacoes'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" onclick="return confirm(\'Deseja realmente excluir?\');"
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