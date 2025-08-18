<?php
include_once("includes/conexao.php");
include_once("includes/classes/Usuario.php");


//receita(idReceita, nome, descricao, idCategoria, foto)

$bd = new Database();
$usuario = new Usuario($bd);
$usuarios = $usuario->listar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro de Receitas - POO</title>
</head>

<body>
    
    <?php include_once("includes/menu.php"); ?>
    

</body>

</html>