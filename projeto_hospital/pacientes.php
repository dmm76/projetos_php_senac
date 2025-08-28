<?php
include_once("includes/conexao.php");
include_once("includes/classes/Paciente.php");
include_once("includes/validacao.php");

$bd = new Database();
$paciente = new Paciente($bd);
$pacientes = $paciente->listar();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idPaciente' => $_POST['idPaciente'],
        'cpf' => $_POST['cpf'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'endereco' => $_POST['endereco'],
    ];
    if ($paciente->inserir($data)) {
        header("Location: pacientes.php?msg=Deu certo");
    } else {
        header("Location: pacientes.php?msg=Deu erro!");
    }
}
if (isset($_GET['idPaciente'])) {

    $idPaciente = $_GET['idPaciente'];
    $PacienteModel = new Paciente($bd);
    $PacienteDados = $PacienteModel->buscar($idPaciente);
    // $idPaciente = $PacienteDados['idPaciente'];
    $cpf = $PacienteDados['cpf'];
    $nome = $PacienteDados['nome'];
    $email = $PacienteDados['email'];
    $telefone = $PacienteDados['telefone'];
    $endereco = $PacienteDados['endereco'];

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
    <title>Cadastro Pacientes</title>
</head>

<body>
    <!-- pacientes(idpacientes, cadastro, cpf, nome, email, telefone, endereco) -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="row">
            <form action="" method="POST">
                <input type="hidden" name="idPaciente" value='<?php echo $idPaciente ?>'>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value='<?php echo $nome ?>' placeholder="Digite o nome completo">
                    </div>
                    <div class="col-md-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value='<?php echo $endereco ?>' placeholder="Digite o nome completo">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value='<?php echo $cpf ?>' placeholder="Digite o cpf completo">
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value='<?php echo $email ?>' placeholder="Digite o email">
                    </div>
                    <div class="col-md-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value='<?php echo $telefone ?>' placeholder="Digite seu telefone">
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>


            </form>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Cadastro</th>
                    <th class="text-center">CPF</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Telefone</th>
                    <th class="text-center">Endereço</th>
                    <th class="text-center">Ações</th>
                </tr>
                <?php
                foreach ($pacientes as $paciente) {
                    echo '
                            <tr>
                                <td class="text-center">' . $paciente['idPaciente'] . '</td>
                                <td class="text-center">' . $paciente['cadastro'] . '</td>    
                                <td class="text-center">' . $paciente['cpf'] . '</td>    
                                <td class="text-center">' . $paciente['nome'] . '</td>
                                <td class="text-center">' . $paciente['email'] . '</td>
                                <td class="text-center">' . $paciente['telefone'] . '</td>
                                <td class="text-center">' . $paciente['endereco'] . '</td>
                                <td class="text-center">
                                    <a class="btn btn-warning btn-sm" href="?idPaciente=' . $paciente['idPaciente'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" onclick="return confirm(\'Deseja realmente excluir?\');"
                                    href="excluirPaciente.php?idPaciente=' . $paciente['idPaciente'] . '">Excluir</a>
                                </td>
                            </tr>       
                        ';
                }
                ?>
            </table>
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
    <script src="https://unpkg.com/imask"></script>
    <script>
        var elemento = document.getElementById("telefone");
        var maskOption = {
            mask: '(00)0 0000-0000'
        }

        var mask = IMask(elemento, maskOption);
    </script>



</body>

</html>