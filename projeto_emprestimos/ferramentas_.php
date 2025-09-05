<?php
include_once("includes/classes/ferramenta.php");

$bd = new Database();
$ferramenta = new Ferramenta($bd);
$ferramentaBD = new Ferramenta($bd);
$ferramentaNomes = $ferramenta->listar();
$ferramentasStatus = $ferramenta->listarStatus();

if (isset($_GET['idferramenta'])) {
    $idferramenta = $_GET['idferramenta'];
    $dados = $ferramentaBD->buscaID($idferramenta);

    $nome = $dados['nome'];
    $descricao = $dados['descricao'];
    $status = $dados['status'];
    $estado = $dados['estado'];
} else {
    $idferramenta = 0;
    $nome = "";
    $descricao = "";
    $status = "";
    $estado = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'idferramenta' => $_POST['idferramenta'],
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'status' => $_POST['status'],
        'estado' => $_POST['estado'],

    ];

    if ($ferramenta->inserir($data)) {
        header("Location: ferramentas.php?msg=Deu certo!");
    } else {
        header("Location: ferramentas.php?msg=Deu ERRO!");
    }
}

$ferramentas = $ferramenta->listar();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
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
                        <input type="" name="idferramenta" value="<?php echo $idferramenta ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" value="<?php echo $nome ?>"
                                    name="nome" placeholder="Digite o nome completo">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="descricao" value="<?php echo $descricao ?>"
                                    name="descricao" placeholder="Digite o descricao">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">status</label>
                                <input type="text" class="form-control" id="status" value="<?php echo $status ?>"
                                    name="status" placeholder="Digite bloco e numero do status">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="estado" class="form-label">Estado de Conservação</label>
                                <input type="text" class="form-control" id="estado" value="<?php echo $estado ?>"
                                    name="estado" placeholder="Digite o estado da ferramenta">
                            </div>

                            <div class="col-md-2 mt-2">
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
                            <th>status</th>
                            <th>Ações</th>
                        </tr>
                        <?php
                        foreach ($ferramentas as $ferramenta) {
                            echo '
									<tr>
										<td>' . $ferramenta['id'] . '</td>
										<td>' . $ferramenta['nome'] . '</td>
										<td>' . $ferramenta['descricao'] . '</td>
										<td>' . $ferramenta['status'] . '</td>
										<td>
											<a href="?idferramenta=' . $ferramenta['id'] . '">Editar</a>
											<a onclick="return confirm(\'Deseja realmente excluir?\');" href="excluir.ferramenta.php?idferramenta=' . $ferramenta['id'] . '">Excluir</a>
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