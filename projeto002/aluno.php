<?php
include_once("includes/conexao.php");

$idAluno = 0;
$nome = "";
$email = "";
$mae = "";
$pai = "";


if ($_POST) {

    $idAluno = $_POST['idAluno'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mae = $_POST['mae'];
    $pai = $_POST['pai'];

    if ($idAluno == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM alunos WHERE nome = '$nome'";
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: aluno.php?tipoMsg=erro&msg=Já existe um aluno com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into alunos (nome, email, mae, pai) values ('$nome', '$email', '$mae', '$pai')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: aluno.php?tipoMsg=sucesso&msg=O estudando <strong>$nome</strong> foi incluído com sucesso!");
            } else {
                header("Location: aluno.php?tipoMsg=erro&msg=Erro ao inserir o aluno! Erro: " . mysqli_error($conexao));
            }
        }
    }else{
        $sql = "UPDATE alunos SET nome = '$nome', email = '$email', mae = '$mae', pai = '$pai' WHERE idAluno = '{$idAluno}'";
        if(mysqli_query($conexao, $sql)){
            header("Location: aluno.php?tipoMsg=sucesso&msg=O estudando <b>$nome</b> foi atualizado com sucesso!");
        }else{
            header("Location: aluno.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }

}

if (isset($_GET['idAluno'])) {

    $idAluno = $_GET['idAluno'];

    // Valida se o valor é numérico
    if (!is_numeric($idAluno)) {
        die("ID de aluno inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM alunos WHERE idAluno = ?");
    mysqli_stmt_bind_param($stmt, "i", $idAluno);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM alunos WHERE idAluno = '{$idAluno}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idAluno = $row['idAluno'];
    $nome = $row['nome'];
    $email = $row['email'];
    $mae = $row['mae'];
    $pai = $row['pai'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de Alunos</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idAluno" value="<?php echo $idAluno; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomeAluno" class="form-label mt-2">Nome: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome do aluno">
                        </div>
                        <div class="col-md-6">
                            <label for="emailAluno" class="form-label mt-2">Email: </label>
                            <input type="email" value="<?php echo $email ?>" name="email" class="form-control" placeholder="Informe o e-mail do aluno">
                        </div>
                        <div class="col-md-6">
                            <label for="nomeMaeAluno" class="form-label mt-2">Nome da Mãe: </label>
                            <input type="text" value="<?php echo $mae ?>" name="mae" class="form-control" placeholder="Informe o nome da mãe">
                        </div>
                        <div class="col-md-6">
                            <label for="nomePaiAluno" class="form-label mt-2">Nome do Pai: </label>
                            <input type="text" value="<?php echo $pai ?>" name="pai" class="form-control" placeholder="Informe o nome do pai">
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
                        <th>ID Aluno</th>
                        <th>Aluno</th>
                        <th>E-MAIL</th>
                        <th>Nome da Mãe</th>
                        <th>Nome do Pai</th>
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM alunos ORDER BY nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idAluno'] . "</td>
                            <td>" . $row['nome'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['mae'] . "</td>
                            <td>" . $row['pai'] . "</td>
                             <td>
                                <a href='?idAluno=" . $row['idAluno'] . "' class='btn btn-primary btn-sm'>Editar</a> 
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