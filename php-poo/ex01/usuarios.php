<!-- nome da turma, email, telefone, bloco, sala -->

<?php
include_once("includes/conexao.php");

$idUsuario = 0;
$nome = "";
$email = "";
$telefone = "";

if ($_POST) {

    $idUsuario = $_POST['idUsuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];    

    if ($idUsuario == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM usuario WHERE nome = '$nome'";
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: usuarios.php?tipoMsg=erro&msg=Já existe um usuario com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into usuario (nome, email, telefone) values ('$nome', '$email', '$telefone')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: usuarios.php?tipoMsg=sucesso&msg=O usuário <strong>$nome</strong> foi incluída com sucesso!");
            } else {
                header("Location: usuarios.php?tipoMsg=erro&msg=Erro ao inserir usuário! Erro: " . mysqli_error($conexao));
            }
        }
    }else{
        $sql = "UPDATE usuario SET nome = '$nome', email = '$email', telefone = '$telefone' WHERE idUsuario = '{$idUsuario}'";
        if(mysqli_query($conexao, $sql)){
            header("Location: usuarios.php?tipoMsg=sucesso&msg=A turma <b>$nome</b> foi atualizada com sucesso!");
        }else{
            header("Location: usuarios.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }

}

if (isset($_GET['idUsuario'])) {

    $idUsuario = $_GET['idUsuario'];

     if(isset($_GET['acao'])){
        $sql = "DELETE FROM usuario WHERE idUsuario = '{$idUsuario}'";

        if(mysqli_query($conexao, $sql)){
             header("Location: usuarios.php?tipoMsg=sucesso&msg=A Turma <b>$nome</b> foi excluido com sucesso!");
        }else{
             header("Location: usuarios.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }

    // Valida se o valor é numérico
    if (!is_numeric($idUsuario)) {
        die("ID da turma inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM usuario WHERE idUsuario = ?");
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM alunos WHERE idAluno = '{$idAluno}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idUsuario = $row['idUsuario'];
    $nome = $row['nome'];
    $email = $row['email'];
    $telefone = $row['telefone'];   
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="telefonenymous">

    <title>Cadastro de usuario</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nomeTurma" class="form-label mt-2">Nome: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome do usuário">
                        </div>
                        <div class="col-md-4">
                            <label for="emailTurma" class="form-label mt-2">email: </label>
                            <input type="email" value="<?php echo $email ?>" name="email" class="form-control" placeholder="Informe o email do usuário">
                        </div>
                        <div class="col-md-4">
                            <label for="telefoneTurma" class="form-label mt-2">telefone: </label>
                            <input type="text" value="<?php echo $telefone ?>" name="telefone" class="form-control" placeholder="Informe o telefone">
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
                        <th>email</th>
                        <th>telefone</th>                        
                        <th>Ações</th>
                        
                    </tr>

                    <?php
                    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idUsuario'] . "</td>
                            <td>" . $row['nome'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['telefone'] . "</td>
                             <td>
                                <a href='?idUsuario=" . $row['idUsuario'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                 <a href='?idUsuario=" . $row['idUsuario'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>
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