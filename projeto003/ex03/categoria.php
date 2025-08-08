<?php
include_once("includes/conexao.php");

$idCategoria = 0;
$nome = "";
$descricao = "";

if ($_POST) {

    $idCategoria = $_POST['idCategoria'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];   

    if ($idCategoria == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM categoria WHERE nome = '$nome'";
        echo $sql; // Verifica se a consulta está correta
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: categoria.php?tipoMsg=erro&msg=Já existe um categoria com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into categoria (nome, descricao) values ('$nome', '$descricao')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: categoria.php?tipoMsg=sucesso&msg=O categoria <strong>$nome</strong> foi incluído com sucesso!");
            } else {
                header("Location: categoria.php?tipoMsg=erro&msg=Erro ao inserir o categoria! Erro: " . mysqli_error($conexao));
            }
        }
    } else {
        $sql = "UPDATE categoria SET nome = '$nome', descricao = '$descricao' WHERE idCategoria = '{$idCategoria}'";
        if (mysqli_query($conexao, $sql)) {
            header("Location: categoria.php?tipoMsg=sucesso&msg=O categoria <b>$nome</b> foi atualizado com sucesso!");
        } else {
            header("Location: categoria.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }
}

if (isset($_GET['idCategoria'])) {

    $idCategoria = $_GET['idCategoria'];

    if (isset($_GET['acao'])) {
        $sql = "DELETE FROM categoria WHERE idCategoria = '{$idCategoria}'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: categoria.php?tipoMsg=sucesso&msg=O categoria <b>$nome</b> foi excluido com sucesso!");
        } else {
            header("Location: categoria.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }



    // Valida se o valor é numérico
    if (!is_numeric($idCategoria)) {
        die("ID de categoria inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM categoria WHERE idCategoria = ?");
    mysqli_stmt_bind_param($stmt, "i", $idCategoria);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM categorias WHERE idCategoria = '{$idCategoria}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idCategoria = $row['idCategoria'];
    $nome = $row['nome'];
    $descricao = $row['descricao'];    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de categorias</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomecategoria" class="form-label mt-2">Título: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome do categoria">
                        </div>
                        <div class="col-md-6">
                            <label for="descricaocategoria" class="form-label mt-2">descricao: </label>
                            <input type="descricao" value="<?php echo $descricao ?>" name="descricao" class="form-control" placeholder="Informe o descricao do categoria">
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
                        <th>ID categoria</th>
                        <th>Título</th>
                        <th>descricao</th>                       
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM categoria ORDER BY nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    if($resultado != null){
                        while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idCategoria'] . "</td>
                            <td>" . $row['nome'] . "</td>
                            <td>" . $row['descricao'] . "</td>                            
                             <td>
                                <a href='?idCategoria=" . $row['idCategoria'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='?idCategoria=" . $row['idCategoria'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>  
                            </td>                          

                        </tr>
                        ";
                    }
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>