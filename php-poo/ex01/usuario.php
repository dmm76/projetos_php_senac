<?php
include_once("includes/classes/Usuario.php");
//usuario(nome, email, telefone)

$bd = new Database();
$usuario = new usuario($bd);

if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    $usuarioModel = new usuario($bd);
    $usuarioDados = $usuarioModel->buscar($idUsuario);

    $idUsuario = $usuarioDados['idUsuario'];
    $nome = $usuarioDados['nome'];
    $email = $usuarioDados['email'];
    $telefone = $usuarioDados['telefone'];   

    // echo $telefone; //teste tem tela

} else {
    $idUsuario = 0;
    $nome = "";
    $telefone = "";
    $email = "";
   
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuarioDados = [
        'idUsuario' => $_POST['idUsuario'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],       
    ];

    echo $_POST['idUsuario'];

    if ($_POST['idUsuario'] == 0) {

        if ($usuario->inserir($usuarioDados)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($usuario->atualizar($usuarioDados)) {
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
    <title>Cadastro de usuario - POO</title>
</head>

<body>
    <!-- usuario(nome, email, telefone) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de usuarios</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?php echo $idUsuario ?>" name="idUsuario">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $nome ?>">
                    </div>                
                    <div class="col-md-4">
                        <label for="">Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $email ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $telefone ?>">
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
        var elemento = document.getElementById("telefone");
        var maskOption = {
            mask: '(00)0 0000-0000'
        }

        var mask = IMask(elemento, maskOption);
    </script>

</body>

</html>