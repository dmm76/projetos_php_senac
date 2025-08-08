<?php
include_once("includes/conexao.php");

$idLivro = 0;
$titulo = "";
$autor = "";
$editora = "";
$anoPublicacao = "";
$descricao = "";
$capa = "";


if ($_POST) {

    $idLivro = $_POST['idLivro'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editora = $_POST['editora'];
    $anoPublicacao = $_POST['anoPublicacao'];
    $descricao = $_POST['descricao'];
    $capa = $_POST['capa'];

    if ($idLivro == 0) {
        // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo titulo
        $sql = "SELECT * FROM livro WHERE titulo = '$titulo'";
        echo $sql; // Verifica se a consulta está correta
        $resultado = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
            header("Location: livro.php?tipoMsg=erro&msg=Já existe um livro com esse titulo!");
            exit();
        } else {
            //prepara a variavel para a insercao no banco de dados
            $sql = "insert into livro (titulo, autor, editora, anoPublicacao, descricao, capa) values ('$titulo', '$autor', '$editora', '$anoPublicacao', '$descricao', '$capa')";

            //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
            if (mysqli_query($conexao, $sql)) {
                header("Location: livro.php?tipoMsg=sucesso&msg=O livro <strong>$titulo</strong> foi incluído com sucesso!");
            } else {
                header("Location: livro.php?tipoMsg=erro&msg=Erro ao inserir o livro! Erro: " . mysqli_error($conexao));
            }
        }
    } else {
        $sql = "UPDATE livro SET titulo = '$titulo', autor = '$autor', editora = '$editora', anoPublicacao = '$anoPublicacao', descricao = '$descricao', capa = '$capa' WHERE idLivro = '{$idLivro}'";
        if (mysqli_query($conexao, $sql)) {
            header("Location: livro.php?tipoMsg=sucesso&msg=O livro <b>$titulo</b> foi atualizado com sucesso!");
        } else {
            header("Location: livro.php?tipoMsg=erro&msg=Erro ao atualizar cadastro!");
        }
    }
}

if (isset($_GET['idLivro'])) {

    $idLivro = $_GET['idLivro'];

    if (isset($_GET['acao'])) {
        $sql = "DELETE FROM livro WHERE idLivro = '{$idLivro}'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: livro.php?tipoMsg=sucesso&msg=O livro <b>$titulo</b> foi excluido com sucesso!");
        } else {
            header("Location: livro.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }



    // Valida se o valor é numérico
    if (!is_numeric($idLivro)) {
        die("ID de livro inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM livro WHERE idLivro = ?");
    mysqli_stmt_bind_param($stmt, "i", $idLivro);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM livros WHERE idLivro = '{$idLivro}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idLivro = $row['idLivro'];
    $titulo = $row['titulo'];
    $autor = $row['autor'];
    $editora = $row['editora'];
    $anoPublicacao = $row['anoPublicacao'];
    $descricao = $row['descricao'];
    $capa = $row['capa'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <title>Cadastro de livros</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <input type="hidden" name="idLivro" value="<?php echo $idLivro; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="titulolivro" class="form-label mt-2">Título: </label>
                            <input type="text" value="<?php echo $titulo ?>" name="titulo" class="form-control" placeholder="Informe o titulo do livro">
                        </div>
                        <div class="col-md-6">
                            <label for="autorlivro" class="form-label mt-2">Autor: </label>
                            <input type="autor" value="<?php echo $autor ?>" name="autor" class="form-control" placeholder="Informe o autor do livro">
                        </div>
                        <div class="col-md-6">
                            <label for="tituloeditoralivro" class="form-label mt-2">Editora: </label>
                            <input type="text" value="<?php echo $editora ?>" name="editora" class="form-control" placeholder="Informe a editora">
                        </div>
                        <div class="col-md-6">
                            <label for="tituloanoPublicacaolivro" class="form-label mt-2">Ano de Publicação: </label>
                            <input type="year" value="<?php echo $anoPublicacao ?>" name="anoPublicacao" class="form-control" placeholder="Informe o ano da publicação (19700)">
                        </div>
                        <div class="col-md-6">
                            <label for="descricaoLivro" class="form-label mt-2">Descricao: </label>
                            <input type="text" value="<?php echo $descricao ?>" name="descricao" class="form-control" placeholder="Descrição do livro">
                        </div>
                        <div class="col-md-6">
                            <label for="capaLivro" class="form-label mt-2">Capa do Livro: </label>
                            <input type="text" value="<?php echo $capa ?>" name="capa" class="form-control" placeholder="insira a capa">
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
                        <th>ID livro</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Editora</th>
                        <th>Ano Publicacao</th>
                        <th>Descrição</th>
                        <th>Capa</th>
                        <th>Ações</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM livro ORDER BY titulo ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    if($resultado != null){
                        while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idLivro'] . "</td>
                            <td>" . $row['titulo'] . "</td>
                            <td>" . $row['autor'] . "</td>
                            <td>" . $row['editora'] . "</td>
                            <td>" . $row['anoPublicacao'] . "</td>
                            <td>" . $row['descricao'] . "</td>
                            <td>" . $row['capa'] . "</td>
                             <td>
                                <a href='?idLivro=" . $row['idLivro'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='?idLivro=" . $row['idLivro'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>  
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