<?php
include_once("includes/conexao.php");
include_once("includes/classes/Usuario.php");


//receita(idReceita, nome, descricao, idCategoria, foto)

$bd = new Database();
$usuario = new Usuario($bd);
$usuario = $usuario->listar();
?>

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
                <input type="hidden" name="idUsuario">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo">
                </div>
                <div class="mb-3">
                    <label for="nivel" class="form-label">Nome</label>
                    <select name="nivel" id="nivel" class="form-select">Nível
                        <option value="recepcao">Recepção</option>
                        <option value="enfermeiro">Enfermeiro</option>
                        <option value="medico">Médico</option>
                        <option value="adm">Administrador</option>
                    </select>

                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>

</body>

</html>