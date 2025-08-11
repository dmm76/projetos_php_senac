<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro de Aluno - POO</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card">
        <h3 class="mt-3 ms-3">Cadastro de Alunos</h3>
        <div class="card-body">
            <div class="row md-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="criar.php" class="btn btn-outline-primary btn-sm">+ Novo Aluno</a>
                </div>
            </div>
            <table class="table table-bordered table-sm"><tr>
                <th>Id Aluno</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>            

            </table>
        </div>
    </div>
    
</body>

</html>