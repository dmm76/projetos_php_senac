<?php
include_once("includes/conexao.php");
if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mae = $_POST['mae'];
    $pai = $_POST['pai'];

    // Vamos fazer a verificacao antes de inserir se ja existe um usuario com o mesmo nome
    $sql = "SELECT * FROM alunos WHERE nome LIKE '%$nome'";
    $resultado = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($resultado) > 0) { //Busca a quantidade de linhas do SELECT
        header("Location: aluno.php?tipoMsg=erro&msg=Já existe um aluno com esse nome!");
        exit();
    } else {
        //prepara a variavel para a insercao no banco de dados
        $sql = "insert into alunos (nome, email, mae, pai) values ('$nome', '$email', '$mae', '$pai')";

        //Verificamos se a insercao no banco de dados ocorreu correntamente ou nao
        if (mysqli_query($conexao, $sql)) {
            header("Location: aluno.php?tipoMsg=sucesso&msg=O estudando <strong>$nome</strong> foi incluído com sucesso!");
        } else {
            header("Location: aluno.php?tipoMsg=erro&msg=Erro ao inserir o aluno! Erro: " . mysqli_error($conexao));
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

    <title>Cadastro de Alunos</title>
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="" class="row g-3 needs-validation p-3" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nomeAluno" class="form-label mt-2">Nome: </label>
                            <input type="text" name="nome" class="form-control" placeholder="Informe o nome do aluno">
                        </div>
                        <div class="col-md-6">
                            <label for="emailAluno" class="form-label mt-2">Email: </label>
                            <input type="email" name="email" class="form-control" placeholder="Informe o e-mail do aluno">
                        </div>
                        <div class="col-md-6">
                            <label for="nomeMaeAluno" class="form-label mt-2">Nome da Mãe: </label>
                            <input type="text" name="mae" class="form-control" placeholder="Informe o nome da mãe">
                        </div>
                        <div class="col-md-6">
                            <label for="nomePaiAluno" class="form-label mt-2">Nome do Pai: </label>
                            <input type="text" name="pai" class="form-control" placeholder="Informe o nome do pai">
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Retorno de includes/mensagem -->
        <?php include_once("includes/mensagens.php"); ?>
    </div>
</body>

</html>