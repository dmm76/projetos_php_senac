<?php
include_once("includes/conexao.php");
include_once("includes/classes/Atendimento.php");
//atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idAtendimento, idMedico, status, obsTriagem, obsAtendimento)
$bd = new Database();
$atendimento = new Atendimento($bd);

if (isset($_GET['status'])) {
    $idAtendimento = $_GET['idAtendimento'];
    $status = $_GET['status'];

    if ($atendimento->alterarStatus($status, $idAtendimento)) {
        header("Location: ?msg=Status marcado como $status");
    }
}

if (isset($_GET['dataInicio'])) { //isset = se tem setado _GET dataInicio no cabeçalho da pagina
    $dataInicio = $_GET['dataInicio'];
    $dataFim = $_GET['dataFim'];
    $nome = $_GET['nome'];
    $cpf = $_GET['cpf'];
} else {
    $dataInicio = date('Y-m-d');
    $dataFim = date('Y-m-d');
    $nome = '';
    $cpf = '';
}

$status = 'triado';
$atendimentos = $atendimento->listarAtendimentos($dataInicio, $dataFim, $nome, $cpf, $status);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Consulta</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h3>Sistema de Consulta</h3>
                <form action="">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="">Data da Consulta:</label>
                            <input type="date" name="dataInicio" value="<?php echo $dataInicio ?>" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="">Data da Consulta:</label>
                            <input type="date" name="dataFim" value="<?php echo $dataFim ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="">Nome do Paciente:</label>
                            <input type="text" name="nome" value="<?php echo $nome ?>" class="form-control" placeholder="Digite o nome do paciente">
                        </div>
                        <div class="col-md-3">
                            <label for="">CPF:</label>
                            <input type="text" name="cpf" id="cpf" value="<?php echo $cpf ?>" class="form-control" placeholder="Digite o cpf ....">
                        </div>
                        <div class="col-md-2 mt-4">
                            <button class="btn btn-primary">Pesquisar</button>
                        </div>
                    </div>

            </div>
            </form>
            <h5 class="card-title ms-3">Atendimentos do dia</h5>
            <div class="row ms-3 me-3">
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                        <th class="text-center">Id Atendimento</th>
                        <th class="text-center">Paciente</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Hora</th>
                        <th class="text-center">Médico</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                    <?php
                    foreach ($atendimentos as $atendimento) {
                        $acoes = '<a class="btn btn-warning btn-sm" href="atendimentos.php?acao=atendimento&idAtendimento=' . $atendimento['idAtendimento'] . '">Fazer Atendimento</a>';

                        if ($atendimento['status'] == 'triado') {
                            $status = '<a href="#" class="btn btn-success btn-sm">Triado</a>';
                        }
                        echo '
                            <tr>
                                <td class="text-center">' . $atendimento['idAtendimento'] . '</td>
                                <td class="text-center">' . $atendimento['nomePaciente'] . '</td>                                 
                                <td class="text-center">' . $atendimento['data'] . '</td>    
                                <td class="text-center">' . $atendimento['hora'] . '</td> 
                                <td class="text-center">' . $atendimento['nomeMedico'] . '</td>
                                <td class="text-center">' . $status . '</td>
                                <td class="text-center">
                                    ' . $acoes . '                              
                                </td>
                            </tr>       
                        ';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/imask"></script>
    <script>
        var elemento = document.getElementById("cpf");
        var maskOption = {
            mask: '000.000.000-00'
        }

        var mask = IMask(elemento, maskOption);
    </script>
</body>

</html>