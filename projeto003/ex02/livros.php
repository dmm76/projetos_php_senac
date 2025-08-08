<!-- descricao da turma, dataInicial, dataFinal, bloco, sala -->

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="dataFinalnymous">

    <title>Livros Cadastrados</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">

        <!-- Retorno de includes/mensagem -->
        <?php include_once("includes/mensagens.php"); ?>
        <div class="card mt-3 mb-2">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Id Livro</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Editora</th>
                        <th>Ano de Publicação</th>
                        <th>Descrição</th>
                        <th>Capa</th>
                        <th>Ações</th>

                    </tr>

                    <?php
                    $sql = "SELECT * FROM livro ORDER BY idLivro ASC";
                    $resultado = mysqli_query($conexao, $sql);

                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "
                        <tr>
                            <td>" . $row['idLivro'] . "</td>
                            <td>" . $row['titulo'] . "</td>
                            <td>" . $row['editora'] . "</td>
                            <td>" . date('d/m/Y', strtotime($row['anoPublicacao'])) . "</td>                            
                            <td>" . $row['descricao'] . "</td>
                            <td>" . $row['capa'] . "</td>
                             <td>
                                <a href='livro.php?idLivro=" . $row['idLivro'] . "' class='btn btn-primary btn-sm'>Editar</a>
                                 <a href='?idLivro=" . $row['idLivro'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>
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