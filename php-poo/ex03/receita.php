<?php
include_once("includes/classes/Receita.php");
//receita(idReceita, nome, descricao, idCategoria, foto)

$bd = new Database();
$receita = new Receita($bd);

if (isset($_GET['idReceita'])) {
    $idReceita = $_GET['idReceita'];

    $receitaModel = new Receita($bd);
    $receitaDados = $receitaModel->buscar($idReceita);

    $idReceita = $receitaDados['idReceita'];
    $nome = $receitaDados['nome'];
    $descricao = $receitaDados['descricao'];
    $idCategoria = $receitaDados['idCategoria'];
    $foto = $receitaDados['foto'];    

    // echo $idCategoria; //teste tem tela

} else {
    $idReceita = 0;
    $nome = "";
    $descricao = "";    
    $idCategoria = "";
    $foto = "";   
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $receitaDados = [
        'idReceita' => $_POST['idReceita'],
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'idCategoria' => $_POST['idCategoria'],
        'foto' => $_POST['foto'],       
    ];

    echo $_POST['idReceita'];

    if ($_POST['idReceita'] == 0) {

        if ($receita->inserir($receitaDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($receita->atualizar($receitaDados)) {
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
    <title>Cadastro de Receita - POO</title>
</head>

<body>
    <!-- //receita(idReceita, nome, descricao, idCategoria, foto) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de Receitas</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idReceita ?>" name="idReceita">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $nome ?>">
                    </div>                
                    <div class="col-md-6">
                        <label for="">Descricao</label>
                        <input type="text" class="form-control" name="descricao" value="<?php echo $descricao ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="">idCategoria</label>
                        <input type="text" class="form-control" name="idCategoria" value="<?php echo $idCategoria ?>">
                    </div>              
                    <div class="col-md-4">
                        <label for="">Foto</label>
                        <input type="text" class="form-control" id="foto" name="foto" value="<?php echo $foto ?>">
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
        var elemento = document.getElementById("idCategoria");
        var maskOption = {
            mask: '00'
        }

        var mask = IMask(elemento, maskOption);
    </script>

</body>

</html>