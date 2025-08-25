<?php
include_once("includes/conexao.php");
include_once("includes/classes/Usuario.php");


//usuario(idUsuario, cadastro, nome, email, senha, nivel)

$bd = new Database();
$usuario = new Usuario($bd);
$usuarios = $usuario->listar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idUsuario' => $_POST['idUsuario'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        'nivel' => $_POST['nivel'],
    ];
    if ($usuario->inserir($data)) {
        header("Location: usuarios.php?msg=Deu certo");
    } else {
        header("Location: usuarios.php?msg=Deu erro!");
    }
}

if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    $UsuarioModel = new Usuario($bd);
    $UsuarioDados = $UsuarioModel->buscar($idUsuario);

    $idUsuario = $UsuarioDados['idUsuario'];
    $nome = $UsuarioDados['nome'];
    $email = $UsuarioDados['email'];
    $senha = $UsuarioDados['senha'];
    $nivel = $UsuarioDados['nivel'];

    //echo $nome; //teste tem tela

} else {
    $idUsuario = 0;
    $nome = "";
    $email = "";
    $senha = "";
    $nivel = "";
}

?>
<!-- //usuario(idUsuario, cadastro, nome, email, senha, nivel) -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro Usuarios</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="row">
            <form action="" method="POST">
                <input type="hidden" name="idUsuario" value='<?php echo $idUsuario ?>'>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value='<?php echo $nome ?>' placeholder="Digite o nome completo">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value='<?php echo $email ?>' placeholder="Digite o email">
                    </div>
                    <div class="col-md-2">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha">
                    </div>
                    <div class="col-md-2">
                        <label for="nivel" class="form-label">Nível</label>
                        <select name="nivel" id="nivel" class="form-select">Nível
                            <option value="">Selecione um nível</option>
                            <option <?php if ($nivel == 'recepcao') {
                                        echo 'selected';
                                    } ?> value="recepcao">Recepção</option>
                            <option <?php if ($nivel == 'enfermeiro') {
                                        echo 'selected';
                                    } ?> value="enfermeiro">Enfermeiro</option>
                            <option <?php if ($nivel == 'medico') {
                                        echo 'selected';
                                    } ?> value="medico">Médico</option>
                            <option <?php if ($nivel == 'adm') {
                                        echo 'selected';
                                    } ?> value="adm">Administrador</option>
                        </select>
                    </div>
                </div>
                                    
                <button type="submit" class="btn btn-primary mb-3">Enviar</button>
            </form>
        </div>

        <div class="row">
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">E-mail</th>
                    <th class="text-center">Nível</th>
                    <th class="text-center">Ações</th>
                </tr>
                <?php
                foreach ($usuarios as $usuario) {
                    echo '
                            <tr>
                                <td class="text-center">' . $usuario['idUsuario'] . '</td>
                                <td class="text-center">' . $usuario['nome'] . '</td>    
                                <td class="text-center">' . $usuario['email'] . '</td>    
                                <td class="text-center">' . $usuario['nivel'] . '</td>
                                <td class="text-center">
                                    <a class="btn btn-warning btn-sm" href="?idUsuario=' . $usuario['idUsuario'] . '">Editar</a>
                                    <a  class="btn btn-danger btn-sm"onclick="return confirm(\'Deseja realmente excluir?\');"
                                    href="excluirUsuario.php?idUsuario=' . $usuario['idUsuario'] . '">Excluir</a>                                         
                                </td>
                            </tr>       
                        ';
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>