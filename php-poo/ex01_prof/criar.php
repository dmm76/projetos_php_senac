<?php
include_once("includes/classes/Aluno.php");

$bd = new Database();
$aluno = new Aluno($bd);

if (isset($_GET['idAluno'])) {
    $idAluno = $_GET['idAluno'];

    $alunoModel = new Aluno($bd);
    $alunoDados = $alunoModel->buscar($idAluno);

    $idAluno = $alunoDados['idAluno'];
    $nome = $alunoDados['nome'];
    $email = $alunoDados['email'];
    $telefone = $alunoDados['telefone'];

    // echo $telefone; //teste tem tela

} else {
    $idAluno = 0;
    $nome = "";
    $telefone = "";
    $email = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = [
        'idAluno' => $_POST['idAluno'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone']
    ];

    echo $_POST['idAluno'];

    if ($_POST['idAluno'] == 0) {

        if ($aluno->inserir($data)) {
            //aqui que volta para index.php quando a inserção da certo
            header("Location: index.php?deu certo");
        }
    } else {
        if ($aluno->atualizar($data)) {
            header("Location: index.php?deu certo em atualizar");
        }
    }
}
?>

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
    <div class="container card text-bg-light">
        <h3 class="mt-3 ms-3">Cadastro de Alunos</h3>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
            <form method="POST" action="">
                <input type="" value="<?php echo $idAluno ?>" name="idAluno">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Nome</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo $nome ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">E-mail</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $email ?>">
                    </div>
                    <div class="col-md-6">
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