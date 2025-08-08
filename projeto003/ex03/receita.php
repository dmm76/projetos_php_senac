<?php
include_once("includes/conexao.php");

$idReceita = 0;
$nome = "";
$descricao = "";
$idCategoria = "";
$nomeCategoria = "";

if ($_POST) {

    $idReceita = $_POST['idReceita'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $idCategoria = $_POST['idCategoria'];

    if ($idReceita == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
        $sql = "SELECT * FROM receita WHERE nome = '$nome'";
        echo $sql; // Verifica se a consulta está correta
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: receita.php?tipoMsg=erro&msg=Já existe um receita com esse nome!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into receita (nome, descricao, idCategoria) values ('$nome', '$descricao', '$idCategoria')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: receita.php?tipoMsg=sucesso&msg=O receita <strong>$nome</strong> foi incluído com sucesso!");
            } else {
                header("Location: receita.php?tipoMsg=erro&msg=Erro ao inserir o receita! Erro: " . mysqli_error($conexao));
            }
        }
    } else {
        $sql = "UPDATE receita SET nome = '$nome', descricao = '$descricao', idCategoria = '$idCategoria' WHERE idReceita = '{$idReceita}'";
        if (mysqli_query($conexao, $sql)) {
            header("Location: receita.php?tipoMsg=sucesso&msg=O receita <b>$nome</b> foi atualizado com sucesso!");
        } else {
            header("Location: receita.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }
}

if (isset($_GET['idReceita'])) {

    $idReceita = $_GET['idReceita'];

    if (isset($_GET['acao'])) {
        $sql = "DELETE FROM receita WHERE idReceita = '{$idReceita}'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: receita.php?tipoMsg=sucesso&msg=O receita <b>$nome</b> foi excluido com sucesso!");
        } else {
            header("Location: receita.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }



    // Valida se o valor é numérico
    if (!is_numeric($idReceita)) {
        die("ID de receita inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM receita WHERE idReceita = ?");
    mysqli_stmt_bind_param($stmt, "i", $idReceita);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM receitas WHERE idReceita = '{$idReceita}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idReceita = $row['idReceita'];
    $nome = $row['nome'];
    $descricao = $row['descricao'];
    $idCategoria = $row['idCategoria'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de receitas</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idReceita" value="<?php echo $idReceita; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomereceita" class="form-label mt-2">Título: </label>
                            <input type="text" value="<?php echo $nome ?>" name="nome" class="form-control" placeholder="Informe o nome do receita">
                        </div>
                        <div class="col-md-6">
                            <label for="descricaoreceita" class="form-label mt-2">descricao: </label>
                            <input type="descricao" value="<?php echo $descricao ?>" name="descricao" class="form-control" placeholder="Informe o descricao do receita">
                        </div>
                        <div class="col-md-6">
                            <label for="nomeidCategoriareceita" class="form-label mt-2">idCategoria: </label>
                            <input type="text" value="<?php echo $idCategoria ?>" name="idCategoria" class="form-control" placeholder="Informe a idCategoria">
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
                        <th>ID receita</th>
                        <th>Título</th>
                        <th>descricao</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT receita.idReceita, receita.nome as nomeReceita, receita.descricao, categoria.nome as nomeCategoria 
                            FROM receita 
                             INNER JOIN categoria ON receita.idCategoria = categoria.idCategoria 
                            ORDER BY receita.nome ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    if ($resultado != null) {
                        while ($row = mysqli_fetch_assoc($resultado)) {
                            echo "
                        <tr>
                            <td>" . $row['idReceita'] . "</td>
                            <td>" . $row['nomeReceita'] . "</td>
                            <td>" . $row['descricao'] . "</td>
                            <td>" . $row['nomeCategoria'] . "</td>                            
                             <td>
                                <a href='?idReceita=" . $row['idReceita'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='?idReceita=" . $row['idReceita'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>  
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