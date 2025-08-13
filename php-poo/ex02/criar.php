<?php
include_once("includes/classes/Livro.php");
//(titulo, autor, editora, anoPublicacao, descricao, capa)

$bd = new Database();
$livro = new Livro($bd);

if (isset($_GET['idLivro'])) {
    $idLivro = $_GET['idLivro'];

    $livroModel = new Livro($bd);
    $livroDados = $livroModel->buscar($idLivro);

    $idLivro = $livroDados['idLivro'];
    $titulo = $livroDados['titulo'];
    $autor = $livroDados['autor'];
    $editora = $livroDados['editora'];
    $anoPublicacao = $livroDados['anoPublicacao'];
    $descricao = $livroDados['descricao'];
    $capa = $livroDados['capa'];

    // echo $editora; //teste tem tela

} else {
    $idLivro = 0;
    $titulo = "";
    $editora = "";
    $autor = "";
    $editora = "";
    $anoPublicacao = "";
    $descricao = "";
    $capa = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $livroDados = [
        'idLivro' => $_POST['idLivro'],
        'titulo' => $_POST['titulo'],
        'autor' => $_POST['autor'],
        'editora' => $_POST['editora'],
        'anoPublicacao' => $_POST['anoPublicacao'],
        'descricao' => $_POST['descricao'],
        'capa' => $_POST['capa'],
    ];

    echo $_POST['idLivro'];

    if ($_POST['idLivro'] == 0) {

        if ($livro->inserir($livroDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($livro->atualizar($livroDados)) {
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
    <title>Cadastro de Livro - POO</title>
</head>

<body>
    <!-- (titulo, autor, editora, anoPublicacao, descricao, capa) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de Livros</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idLivro ?>" name="idLivro">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Título</label>
                        <input type="text" class="form-control" name="titulo" value="<?php echo $titulo ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">Autor</label>
                        <input type="text" class="form-control" name="autor" value="<?php echo $autor ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="">Editora</label>
                        <input type="text" class="form-control" name="editora" value="<?php echo $editora ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Ano Publicacao</label>
                        <input type="text" class="form-control" id="anoPublicacao" name="anoPublicacao" value="<?php echo $anoPublicacao ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">descricao</label>
                        <input type="text" class="form-control" name="descricao" value="<?php echo $descricao ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="">Capa</label>
                        <input type="text" class="form-control" name="capa" value="<?php echo $capa ?>">
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
        var elemento = document.getElementById("anoPublicacao");
        var maskOption = {
            mask: '0000'
        }

        var mask = IMask(elemento, maskOption);
    </script>

</body>

</html>