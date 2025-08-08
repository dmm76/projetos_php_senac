<?php
include_once("includes/conexao.php");

$idProduto = 0;
$nome = "";



if ($_POST) {

    $idProduto = $_POST['idProduto'];
    $nome = $_POST['nome'];
    

    if ($idProduto == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM produto WHERE nome = '$nome'";
        echo $sql; // Verifica se a consulta está correta
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: produto.php?tipoMsg=erro&msg=Já existe um produto com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into produto (nome) values ('$nome')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: produto.php?tipoMsg=sucesso&msg=O produto <strong>$nome</strong> foi incluído com sucesso!");
            } else {
                header("Location: produto.php?tipoMsg=erro&msg=Erro ao inserir o produto! Erro: " . mysqli_error($conexao));
            }
        }
    } else {
        $sql = "UPDATE produto SET nome = '$nome' WHERE idProduto = '{$idProduto}'";
        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=O produto <b>$nome</b> foi atualizado com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }
}

if (isset($_GET['idProduto'])) {

    $idProduto = $_GET['idProduto'];

    if (isset($_GET['acao'])) {
        $sql = "DELETE FROM produto WHERE idProduto = '{$idProduto}'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=O produto <b>$nome</b> foi excluido com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }



    // Valida se o valor é numérico
    if (!is_numeric($idProduto)) {
        die("ID de produto inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM produto WHERE idProduto = ?");
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM produtos WHERE idProduto = '{$idProduto}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idProduto = $row['idProduto'];
    $nome = $row['nome'];
   
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de produtos</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idProduto" value="<?php echo $idProduto; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomeproduto" class="form-label mt-2">Nome do Produto: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome do produto">
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
                        <th>ID produto</th>
                        <th>Nome</th>                        
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM produto ORDER BY nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    if($resultado != null){
                        while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idProduto'] . "</td>
                            <td>" . $row['nome'] . "</td>                            
                             <td>
                                <a href='?idProduto=" . $row['idProduto'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='?idProduto=" . $row['idProduto'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>  
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