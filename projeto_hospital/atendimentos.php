<?php
include_once("includes/conexao.php");
include_once("includes/classes/Atendimento.php");


//atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idAtendimento, idMedico, status, obsTriagem, obsAtendimento)

$bd = new Database();
$atendimento = new Atendimento($bd);
$atendimentos = $atendimento->listar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idAtendimento' => $_POST['idAtendimento'],
        'dataInicio' => $_POST['dataInicio'],
        'dataFim' => $_POST['dataFim'],
        'idPaciente' => $_POST['idPaciente'],
        'idAtendimento' => $_POST['idAtendimento'],
        'idMedico' => $_POST['idMedico'],
        'status' => $_POST['status'],
        'obsTriagem' => $_POST['obsTriagem'],
        'obsAtendimento' => $_POST['obsAtendimento'],
    ];
    if ($atendimento->inserir($data)) {
        header("Location: atendimentos.php?msg=Deu certo");
    } else {
        header("Location: atendimentos.php?msg=Deu erro!");
    }
}

if (isset($_GET['idAtendimento'])) {
    $idAtendimento = $_GET['idAtendimento'];

    $AtendimentoModel = new Atendimento($bd);
    $AtendimentoDados = $AtendimentoModel->buscar($idAtendimento);

    $idAtendimento = $AtendimentoDados['idAtendimento'];
    $cadastro = $AtendimentoDados['cadastro'];
    $dia = $AtendimentoDados['data'];
    $dataInicio = $AtendimentoDados['dataInicio'];
    $dataFim = $AtendimentoDados['dataFim'];
    $idPaciente = $AtendimentoDados['idPaciente'];
    $idAtendimento = $AtendimentoDados['idAtendimento'];
    $idMedico = $AtendimentoDados['idMedico'];
    $status = $AtendimentoDados['status'];
    $obsTriagem = $AtendimentoDados['obsTriagem'];
    $obsAtendimento = $AtendimentoDados['obsAtendimento'];
    //echo $dataInicio; //teste tem tela

} else {
    $idAtendimento = 0;
    $cadastro = "";
    $dia = "";
    $dataInicio = "";
    $dataFim = "";
    $status = "";
    $idPaciente = 0;
    $idAtendimento = 0;
    $idMedico = 0;
    $status = "";
    $obsTriagem = "";
    $obsAtendimento = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro Atendimentos</title>
</head>
<!-- atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idAtendimento, idMedico, status, obsTriagem, obsAtendimento) -->

<body>
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="row">
            <form action="" method="POST">
                <input type="" name="idAtendimento" value='<?php echo $idAtendimento ?>'>
                <div class="row ">
                    <div class="mb-3 col-sm-3">
                        <label for="dataInicio" class="form-label">Data Inicio</label>
                        <input type="date" class="form-control" id="dataInicio" name="dataInicio" value='<?php echo $dataInicio ?>' placeholder="Digite o dataInicio completo">
                    </div>
                    <div class="mb-3 col-sm-3">
                        <label for="dataFim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="dataFim" name="dataFim" value='<?php echo $dataFim ?>' placeholder="Digite o dataFim">
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">Status
                            <option value="">Selecione um nível</option>
                            <option <?php if ($status == 'agendado') {
                                        echo 'selected';
                                    } ?> value="agendado">Agendado</option>
                            <option <?php if ($status == 'recepcionado') {
                                        echo 'selected';
                                    } ?> value="recepcionado">Recepcionado</option>
                            <option <?php if ($status == 'triado') {
                                        echo 'selected';
                                    } ?> value="triado">Triado</option>
                            <option <?php if ($status == 'finalizado') {
                                        echo 'selected';
                                    } ?> value="finalizado">Finalizado</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-sm-4">
                        <label for="idPaciente" class="form-label">IdPaciente</label>
                        <input type="number" class="form-control" id="idPaciente" name="idPaciente" value='<?php echo $idPaciente ?>' placeholder="Digite a Id Paciente">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idAtendimento" class="form-label">Id Atendimento</label>
                        <input type="number" class="form-control" id="idAtendimento" name="idAtendimento" value='<?php echo $idAtendimento ?>' placeholder="Digite id Atendimento">
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="idMedico" class="form-label">Id Médico</label>
                        <input type="number" class="form-control" id="idMedico" name="idMedico" value='<?php echo $idMedico ?>' placeholder="Digite id Médico">
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3 col-sm-6">
                        <label for="obsTriagem" class="form-label">OBS Triagem</label>
                        <textarea class="form-control" id="obsTriagem" name="obsTriagem" placeholder="Observação Triagem" rows="3"><?php echo $obsTriagem ?></textarea>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="obsAtendimento" class="form-label">OBS Atendimento</label>
                        <textarea class="form-control" id="obsAtendimento" name="obsAtendimento" placeholder="Observação Atendimento" rows="3"><?php echo $obsAtendimento ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
            </form>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th>Id Atendimento</th>
                    <th>Cadastro</th>
                    <th>Dia</th>
                    <th>Hora</th>
                    <th>data Inicio</th>
                    <th>data Fim</th>
                    <th>id Paciente</th>
                    <th>id Atendimento</th>
                    <th>id Médico</th>
                    <th>Status</th>
                    <th>Obs Triagem</th>
                    <th>Obs Atendimento</th>
                    <th>Ações</th>
                </tr>
                <?php
                foreach ($atendimentos as $atendimento) {
                    echo '
                            <tr>
                                <td>' . $atendimento['idAtendimento'] . '</td>
                                <td>' . $atendimento['cadastro'] . '</td>    
                                <td>' . $atendimento['data'] . '</td>    
                                <td>' . $atendimento['hora'] . '</td>
                                <td>' . $atendimento['dataInicio'] . '</td>
                                <td>' . $atendimento['dataFim'] . '</td>
                                <td>' . $atendimento['idPaciente'] . '</td>
                                <td>' . $atendimento['idAtendimento'] . '</td>
                                <td>' . $atendimento['idMedico'] . '</td>
                                <td>' . $atendimento['status'] . '</td>
                                <td>' . $atendimento['obsTriagem'] . '</td>
                                <td>' . $atendimento['obsAtendimento'] . '</td>

                                <td>
                                    <a href="?idAtendimento=' . $atendimento['idAtendimento'] . '">Editar</a>
                                    <a onclick="return confirm(\'Deseja realmente excluir?\');"
                                    href="excluirAtendimento.php?idAtendimento=' . $atendimento['idAtendimento'] . '">Excluir</a>
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