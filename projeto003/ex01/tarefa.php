<!-- descricao da turma, dataInicial, dataFinal, bloco, sala -->

<?php
include_once("includes/conexao.php");

$idTarefa = 0;
$descricao = "";
$dataInicial = "";
$dataFinal = "";
$status = "iniciado";
$idUsuario = 0;

if ($_POST) {

    $idTarefa = $_POST['idTarefa'];
    $descricao = $_POST['descricao'];
    $dataInicial = $_POST['dataInicial'];
    $dataFinal = $_POST['dataFinal'];
    $status = $_POST['status'];
    $idUsuario = $_POST['idUsuario'];

    if ($idTarefa == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um tarefa com o mesmo descricao
        $sql = "SELECT * FROM tarefa WHERE descricao = '$descricao'";
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: tarefa.php?tipoMsg=erro&msg=Já existe um tarefa com essa descricao!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into tarefa (descricao, dataInicial, dataFinal, status, idUsuario) values ('$descricao', '$dataInicial', '$dataFinal', '$status', '$idUsuario')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: tarefa.php?tipoMsg=sucesso&msg=A tarefa <strong>$descricao</strong> foi incluída com sucesso!");
            } else {
                header("Location: tarefa.php?tipoMsg=erro&msg=Erro ao inserir tarefa! Erro: " . mysqli_error($conexao));
            }
        }
    }else{
        $sql = "UPDATE tarefa SET descricao = '$descricao', dataInicial = '$dataInicial', dataFinal = '$dataFinal', status = '$status' WHERE idTarefa = '{$idTarefa}'";
        if(mysqli_query($conexao, $sql)){
            header("Location: tarefa.php?tipoMsg=sucesso&msg=A tarefa <b>$descricao</b> foi atualizada com sucesso!");
        }else{
            header("Location: tarefa.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }

}

if (isset($_GET['idTarefa'])) {

    $idTarefa = $_GET['idTarefa'];

     if(isset($_GET['acao'])){
        $sql = "DELETE FROM tarefa WHERE idTarefa = '{$idTarefa}'";

        if(mysqli_query($conexao, $sql)){
             header("Location: tarefas.php?tipoMsg=sucesso&msg=A Turma <b>$descricao</b> foi excluido com sucesso!");
        }else{
             header("Location: tarefas.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }

    // Valida se o valor é numérico
    if (!is_numeric($idTarefa)) {
        die("ID da turma inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM tarefa WHERE idTarefa = ?");
    mysqli_stmt_bind_param($stmt, "i", $idTarefa);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM alunos WHERE idAluno = '{$idAluno}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idTarefa = $row['idTarefa'];
    $descricao = $row['descricao'];
    $dataInicial = $row['dataInicial'];
    $dataFinal = $row['dataFinal'];
    $status = $row['status'];
    $idUsuario = $row['idUsuario'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="dataFinalnymous">

    <title>Cadastro de tarefa</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idTarefa" value="<?php echo $idTarefa; ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="descricaoTurma" class="form-label mt-2">descricao: </label>
                            <input type="text" value="<?php echo $descricao ?>" name="descricao" class="form-control" placeholder="Informe o descricao da tarefa">
                        </div>
                        <div class="col-md-4">
                            <label for="idUsuario" class="form-label mt-2">Id Usuario: </label>
                            <input type="text" value="<?php echo $idUsuario ?>" name="idUsuario" class="form-control" placeholder="Usuario">
                        </div>
                        <div class="col-md-4">
                            <label for="dataFinalTurma" class="form-label mt-2">status: </label>
                            <input type="text" value="<?php echo $status ?>" name="status" class="form-control" placeholder="Status atual da tarefa">
                        </div>  
                        <div class="col-md-6">
                            <label for="dataInicialTurma" class="form-label mt-2">dataInicial: </label>
                            <input type="date" value="<?php echo $dataInicial ?>" name="dataInicial" class="form-control" placeholder="Informe a data inicial">
                        </div>
                        <div class="col-md-6">
                            <label for="dataFinalTurma" class="form-label mt-2">dataFinal: </label>
                            <input type="date" value="<?php echo $dataFinal ?>" name="dataFinal" class="form-control" placeholder="Informe a data final">
                        </div>
                        
                                                  
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Retorno de includes/mensagem -->
        <?php include_once("includes/mensagens.php"); ?>
        <div class="card mt-3 mb-2">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Id Tarefa</th>
                        <th>descricao</th>
                        <th>dataInicial</th>
                        <th>dataFinal</th>
                        <th>status</th>
                        <th>idUsuario</th>                          
                        <th>Ações</th>
                        
                    </tr>

                    <?php
                    $sql = "SELECT * FROM tarefa inner join usuario on tarefa.idUsuario=usuario.idUsuario ORDER BY descricao ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idTarefa'] . "</td>
                            <td>" . $row['descricao'] . "</td>
                            <td>" . date('d/m/Y', strtotime($row['dataInicial'])) . "</td>
                            <td>" . date('d/m/Y', strtotime($row['dataFinal'])) . "</td>
                            <td>" . $row['status'] . "</td>
                            <td>" . $row['nome'] . "</td>
                             <td>
                                <a href='?idTarefa=" . $row['idTarefa'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                 <a href='?idTarefa=" . $row['idTarefa'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>
                                </td>
                            

                        </tr>
                        ";
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>