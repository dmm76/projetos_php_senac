<?php
include_once("includes/classes/Tarefa.php");
//tarefa(descricao, dataInicial, dataFinal, status, idUsuario)

$bd = new Database();
$tarefa = new tarefa($bd);

if (isset($_GET['idTarefa'])) {
    $idTarefa = $_GET['idTarefa'];

    $tarefaModel = new tarefa($bd);
    $tarefaDados = $tarefaModel->buscar($idTarefa);

    $idTarefa = $tarefaDados['idTarefa'];
    $descricao = $tarefaDados['descricao'];
    $dataInicial = $tarefaDados['dataInicial'];
    $dataFinal = $tarefaDados['dataFinal'];
    $status = $tarefaDados['status']; 
    $idUsuario = $tarefaDados['idUsuario']; 

    // echo $dataFinal; //teste tem tela

} else {
    $idTarefa = 0;
    $descricao = "";
    $dataFinal = "";
    $dataInicial = "";
    $status = "";
    $idUsuario = 0;

   
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $tarefaDados = [
        'idTarefa' => $_POST['idTarefa'],
        'descricao' => $_POST['descricao'],
        'dataInicial' => $_POST['dataInicial'],
        'dataFinal' => $_POST['dataFinal'],
        'status' => $_POST['status'],
        'idUsuario' => $_POST['idUsuario']    
    ];

    echo $_POST['idTarefa'];

    if ($_POST['idTarefa'] == 0) {

        if ($tarefa->inserir($tarefaDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($tarefa->atualizar($tarefaDados)) {
            header("Location: index.php?deu certo em atualizar");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pr-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro de tarefa - POO</title>
</head>

<body>
   <!--tarefa(descricao, dataInicial, dataFinal, status, idUsuario) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de tarefas</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idTarefa ?>" name="idTarefa">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Descricao</label>
                        <input type="text" class="form-control" name="descricao" value="<?php echo $descricao ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">Data Inicial</label>
                        <input type="date" class="form-control" name="dataInicial" value="<?php echo $dataInicial ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="">Data Final</label>
                        <input type="date" class="form-control" name="dataFinal" value="<?php echo $dataFinal ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Status</label>
                        <input type="text" class="form-control" name="status" value="<?php echo $status ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">Id Usuário</label>
                        <input type="text" class="form-control" name="idUsuario" value="<?php echo $idUsuario ?>">
                    </div>                    
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/imask"></script>
    <script>
        var elemento = document.getElementById("dataFinal");
        var maskOption = {
            mask: '(00)0 0000-0000'
        }

        var mask = IMask(elemento, maskOption);
    </script>

</body>

</html>