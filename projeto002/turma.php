<!-- nome da turma, semestre, ano, bloco, sala -->

<?php
include_once("includes/conexao.php");

$idTurma = 0;
$nome = "";
$semestre = "";
$ano = "";
$bloco = "";
$sala = "";


if ($_POST) {

    $idTurma = $_POST['idTurma'];
    $nome = $_POST['nome'];
    $semestre = $_POST['semestre'];
    $ano = $_POST['ano'];
    $bloco = $_POST['bloco'];
    $sala = $_POST['sala'];

    if ($idTurma == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM turmas WHERE nome = '$nome'";
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: turma.php?tipoMsg=erro&msg=Já existe uma turma com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into turmas (nome, semestre, ano, bloco, sala) values ('$nome', '$semestre', '$ano', '$bloco', '$sala')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: turma.php?tipoMsg=sucesso&msg=A Turma <strong>$nome</strong> foi incluída com sucesso!");
            } else {
                header("Location: turma.php?tipoMsg=erro&msg=Erro ao inserir o Turma! Erro: " . mysqli_error($conexao));
            }
        }
    }else{
        $sql = "UPDATE turmas SET nome = '$nome', semestre = '$semestre', ano = '$ano', bloco = '$bloco', sala = '$sala' WHERE idTurma = '{$idTurma}'";
        if(mysqli_query($conexao, $sql)){
            header("Location: turma.php?tipoMsg=sucesso&msg=A turma <b>$nome</b> foi atualizada com sucesso!");
        }else{
            header("Location: turma.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }

}

if (isset($_GET['idTurma'])) {

    $idTurma = $_GET['idTurma'];

    // Valida se o valor é numérico
    if (!is_numeric($idTurma)) {
        die("ID da turma inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM turmas WHERE idTurma = ?");
    mysqli_stmt_bind_param($stmt, "i", $idTurma);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM alunos WHERE idAluno = '{$idAluno}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idTurma = $row['idTurma'];
    $nome = $row['nome'];
    $semestre = $row['semestre'];
    $ano = $row['ano'];
    $bloco = $row['bloco'];
    $sala = $row['sala'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de Turmas</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idTurma" value="<?php echo $idTurma; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomeTurma" class="form-label mt-2">Nome: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome da Turma">
                        </div>
                        <div class="col-md-6">
                            <label for="semestreTurma" class="form-label mt-2">Semestre: </label>
                            <input type="text" value="<?php echo $semestre ?>" name="semestre" class="form-control" placeholder="Informe o semestre do aluno (1 ou 2)">
                        </div>
                        <div class="col-md-4">
                            <label for="anoTurma" class="form-label mt-2">Ano: </label>
                            <input type="text" value="<?php echo $ano ?>" name="ano" class="form-control" placeholder="Informe o ano (ex: 2025)">
                        </div>
                        <div class="col-md-4">
                            <label for="blocoTurma" class="form-label mt-2">Bloco: </label>
                            <input type="text" value="<?php echo $bloco ?>" name="bloco" class="form-control" placeholder="Informe o bloco da turma">
                        </div>
                        <div class="col-md-4">
                            <label for="salaTurma" class="form-label mt-2">Sala: </label>
                            <input type="text" value="<?php echo $sala ?>" name="sala" class="form-control" placeholder="Informe a sala da turma">
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
                        <th>ID Turma</th>
                        <th>Nome</th>
                        <th>Semestre</th>
                        <th>Ano</th>
                        <th>Bloco</th>
                        <th>Sala</th>
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM turmas ORDER BY nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idTurma'] . "</td>
                            <td>" . $row['nome'] . "</td>
                            <td>" . $row['semestre'] . "</td>
                            <td>" . $row['ano'] . "</td>
                            <td>" . $row['bloco'] . "</td>
                            <td>" . $row['sala'] . "</td>
                             <td>
                                <a href='?idTurma=" . $row['idTurma'] . "' class='btn btn-primary btn-sm'>Editar</a> 
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