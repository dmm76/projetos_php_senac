<?php
include_once("includes/classes/Categoria.php");
//categoria(idCategoria, nome, descricao)

$bd = new Database();
$categoria = new Categoria($bd);

if (isset($_GET['idCategoria'])) {
    $idCategoria = $_GET['idCategoria'];

    $categoriaModel = new Categoria($bd);
    $categoriaDados = $categoriaModel->buscar($idCategoria);

    $idCategoria = $categoriaDados['idCategoria'];
    $nome = $categoriaDados['nome'];
    $descricao = $categoriaDados['descricao']; 

    // echo $editora; //teste tem tela

} else {
    $idCategoria = 0;
    $nome = "";
    $editora = "";
    $descricao = "";    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $categoriaDados = [
        'idCategoria' => $_POST['idCategoria'],
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao'],       
    ];

    echo $_POST['idCategoria'];

    if ($_POST['idCategoria'] == 0) {

        if ($categoria->inserir($categoriaDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($categoria->atualizar($categoriaDados)) {
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
    <title>Cadastro de Categoria - POO</title>
</head>

<body>
    <!-- //categoria(idCategoria, nome, descricao) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de Categorias</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idCategoria ?>" name="idCategoria">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $nome ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">descricao</label>
                        <input type="text" class="form-control" name="descricao" value="<?php echo $descricao ?>">
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