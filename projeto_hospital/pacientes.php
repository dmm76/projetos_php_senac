<?php
include_once("includes/conexao.php");
include_once("includes/classes/Paciente.php");


//pacientes(idpacientes, cadastro, cpf, nome, email, telefone, endereco)

$bd = new Database();
$paciente = new Paciente($bd);
//$pacientes = $pacientes->listar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro Pacientes</title>
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
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite o cpf completo">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email">
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Digite seu telefone">
                </div>
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite o nome completo">
                </div>                
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/imask"></script>
    <script>
        var elemento = document.getElementById("cpf");
        var maskOption = {
            mask: '000.000.000-00'
        }

        var mask = IMask(elemento, maskOption);
    </script>
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