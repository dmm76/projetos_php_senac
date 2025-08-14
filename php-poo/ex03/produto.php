<?php
include_once("includes/classes/Produto.php");
//produto(idProduto, nome)

$bd = new Database();
$produto = new Produto($bd);

if (isset($_GET['idProduto'])) {
    $idProduto = $_GET['idProduto'];

    $produtoModel = new Produto($bd);
    $produtoDados = $produtoModel->buscar($idProduto);

    $idProduto = $produtoDados['idProduto'];
    $nome = $produtoDados['nome'];   

    // echo $editora; //teste tem tela

} else {
    $idProduto = 0;
    $nome = "";   
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $produtoDados = [
        'idProduto' => $_POST['idProduto'],
        'nome' => $_POST['nome'],        
    ];

    echo $_POST['idProduto'];

    if ($_POST['idProduto'] == 0) {

        if ($produto->inserir($produtoDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($produto->atualizar($produtoDados)) {
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
    <title>Cadastro de Produto - POO</title>
</head>

<body>
    <!-- produto(idProduto, nome) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de Produtos</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idProduto ?>" name="idProduto">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Nome do Produto</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $nome ?>">
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