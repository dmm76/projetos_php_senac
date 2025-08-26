<?php
include_once("includes/conexao.php");
include_once("includes/classes/Atendimento.php");


//atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idAtendimento, idMedico, status, obsTriagem, obsAtendimento)

$bd = new Database();
$atendimento = new Atendimento($bd);
$atendimentos = $atendimento->listar();
$pacientes = $atendimento->listarPacientes();
$medicos = $atendimento->listarMedicos();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $status = 'agendado';

    if (!isset($_POST['obsTriagem'])) {
        $obsTriagem = '';
    } else {
        $obsTriagem = $_POST['obsTriagem'];
        $status = 'triado';
    }

    if (!isset($_POST['obsAtendimento'])) {
        $obsAtendimento = '';
    } else {
        $obsAtendimento = $_POST['obsAtendimento'];
        $status = 'finalizado';
    }

    $data = [
        'idAtendimento' => $_POST['idAtendimento'],
        'dataInicio' => $_POST['dataInicio'],
        'dataFim' => $_POST['dataFim'],
        'idPaciente' => $_POST['idPaciente'],
        'idAtendimento' => $_POST['idAtendimento'],
        'idMedico' => $_POST['idMedico'],
        'status' => $status,
        'obsTriagem' => $obsTriagem,
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
                <input type="hidden" name="idAtendimento" value='<?php echo $idAtendimento ?>'>
                <div class="row ">
                    <div class="row">
                        <div class="mb-3 col-sm-3">
                            <label for="idAtendimento" class="form-label">Identificação Atendimento</label>
                            <input type="number" class="form-control" id="idAtendimento" name="idAtendimento" value='<?php echo $idAtendimento ?>' placeholder="Digite id Atendimento" disabled>
                        </div>
                        <div class="mb-3 col-sm-3">
                            <label for="dataInicio" class="form-label">Data Inicio</label>
                            <input type="date" class="form-control" id="dataInicio" name="dataInicio" value='<?php echo $dataInicio ?>' placeholder="Digite a data Inicio">
                        </div>
                        <div class="mb-3 col-sm-3">
                            <label for="Paciente" class="form-label">Identificação Paciente</label>
                            <select name="idPaciente" id="idPaciente" class="form-select">
                                <option value="">Selecione um Paciente</option>
                                </option>
                                <?php
                                foreach ($pacientes as $paciente) {
                                    if ($idPaciente == $paciente['idPaciente']) {
                                        $sel = 'selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option ' . $sel . ' value="' . $paciente['idPaciente'] . '">' . $paciente['nome'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                        <div class="mb-3 col-sm-3">
                            <label for="idMedico" class="form-label">Identificação Médico</label>
                            <select name="idMedico" id="idMedico" class="form-select">
                                <option value="">Selecione um Médico</option>
                                <?php
                                foreach ($medicos as $medico) {
                                    if ($idMedico == $medico['idUsuario']) {
                                        $sel = 'selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option ' . $sel . ' value="' . $medico['idUsuario'] . '">' . $medico['nome'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>                        
                    </div>

                    <!-- <div class="mb-3 col-sm-3">
                        <label for="dataFim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="dataFim" name="dataFim" value='<?php echo $dataFim ?>' placeholder="Digite a data Fim">
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
                    </div> -->
                </div>
                <div class="row">
                    <?php
                    if (isset($_GET['acao'])) {
                        if ($_GET['acao'] == 'preatendimento') {
                            echo '
                            <div class="mb-3 col-sm-6">
                            <label for="obsTriagem" class="form-label">OBS Triagem</label>
                            <textarea class="form-control" id="obsTriagem" name="obsTriagem" rows="8"  placeholder="Observação Triagem" rows="3">' . $obsTriagem . '</textarea>
                            </div>
                        ';
                        }
                    }
                    if (isset($_GET['acao'])) {
                        if ($_GET['acao'] == 'atendimento') {
                            echo '
                            <div class="mb-3 col-sm-6">
                            <label for="obsTriagem" class="form-label">OBS Triagem</label>
                            <textarea class="form-control" disabled id="obsTriagem" rows="8" placeholder="Observação Triagem" rows="3">' . $obsTriagem . '</textarea>
                            </div>
                        ';
                        }
                        if ($_GET['acao'] == 'atendimento') {
                            echo '
                            <div class="mb-3 col-sm-6">
                            <label for="obsTriagem" class="form-label">OBS Atendimentos</label>
                            <textarea class="form-control" id="obsAtendimento" name="obsAtendimento" rows="8"  placeholder="Observação Atendimento" rows="3">' . $obsAtendimento . '</textarea>
                            </div>
                        ';
                        }
                    }
                    ?>                    
                </div>
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
            </form>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th class="text-center">Id Atendimento</th>
                    <th class="text-center">Dia</th>
                    <th class="text-center">Hora</th>
                    <th class="text-center">id Paciente</th>
                    <th class="text-center">id Médico</th>
                    <th class="text-center">Ações</th>
                </tr>
                <?php
                foreach ($atendimentos as $atendimento) {
                    echo '
                            <tr>
                                <td class="text-center">' . $atendimento['idAtendimento'] . '</td>                                 
                                <td class="text-center">' . $atendimento['data'] . '</td>    
                                <td class="text-center">' . $atendimento['hora'] . '</td>                                
                                <td class="text-center">' . $atendimento['nomePaciente'] . '</td>                                
                                <td class="text-center">' . $atendimento['nomeMedico'] . '</td>                         

                                <td class="text-center">
                                    <a class="btn btn-warning btn-sm" href="?idAtendimento=' . $atendimento['idAtendimento'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" onclick="return confirm(\'Deseja realmente excluir?\');"
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