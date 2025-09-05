<?php

include_once("includes/classes/Usuario.php");
// include_once("includes/menu.php");

// if (!isset($_SESSION['idUsuario'])) {
// 	header("Location: login.php?Você precisa estar logado!");
// 	exit();
// }

$bd = new Database();
$usuario = new Usuario($bd);
$usuarioBD = new Usuario($bd);

if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];
    $dados = $usuarioBD->buscaID($idUsuario);
    
    $nome = $dados['nome'];
    $email = $dados['email'];
    $apartamento = $dados['apartamento'];
} else {
    $idUsuario = 0;
    $nome = "";
    $email = "";
    $apartamento = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idUsuario' => $_POST['idUsuario'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        'apartamento' => $_POST['apartamento'],
    ];

    if ($usuario->inserir($data)) {
        header("Location: usuarios.php?msg=Deu certo!");
    } else {
        header("Location: usuarios.php?msg=Deu ERRO!");
    }
}

$usuarios = $usuario->listar();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Usuários</title>
</head>

<body>
   <!-- <?php include_once('includes/menu.php'); ?> -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Usuários</h3>
                <div class="row">
                    <form action="" method="POST">
                        <input type="" name="idUsuario" value="<?php echo $idUsuario ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" value="<?php echo $nome ?>" name="nome" placeholder="Digite o nome completo">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $email ?>" name="email" placeholder="Digite o email">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a senha">
                             </div>

                            <div class="col-md-2 mb-3">
                                <label for="apartamento" class="form-label">Apartamento</label>
                                <input type="text" class="form-control" id="apartamento" value="<?php echo $apartamento ?>" name="apartamento" placeholder="Digite bloco e numero do apartamento">
                            </div>
                            <div class="col-md-2 mt-4">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>                            
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Apartamento</th>
                            <th>Ações</th>
                        </tr>
                        <?php
                        foreach ($usuarios as $usuario) {
                            echo '
									<tr>
										<td>' . $usuario['id'] . '</td>
										<td>' . $usuario['nome'] . '</td>
										<td>' . $usuario['email'] . '</td>
										<td>' . $usuario['apartamento'] . '</td>
										<td>
											<a href="?idUsuario=' . $usuario['id'] . '">Editar</a>
											<a onclick="return confirm(\'Deseja realmente excluir?\');" href="excluir.usuario.php?idUsuario=' . $usuario['id'] . '">Excluir</a>
										</td>
									</tr>';
                        }

                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>