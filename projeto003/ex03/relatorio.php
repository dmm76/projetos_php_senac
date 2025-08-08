<?php
include_once("includes/conexao.php");

// Preenche opções de descricaoes, idCategorias e anos de publicação:
$descricaoes = mysqli_query($conexao, "SELECT DISTINCT descricao FROM receita ORDER BY descricao");
$idCategorias = mysqli_query($conexao, "SELECT DISTINCT idCategoria FROM receita ORDER BY idCategoria");
$anos = mysqli_query($conexao, "SELECT DISTINCT anoPublicacao FROM receita ORDER BY anoPublicacao DESC");

// Filtros
$where = [];

if (!empty($_GET['nome'])) {
    $nome = mysqli_real_escape_string($conexao, $_GET['nome']);
    $where[] = "nome LIKE '%$nome%'";
}

if (!empty($_GET['descricao'])) {
    $descricao = mysqli_real_escape_string($conexao, $_GET['descricao']);
    $where[] = "descricao = '$descricao'";
}

if (!empty($_GET['idCategoria'])) {
    $idCategoria = mysqli_real_escape_string($conexao, $_GET['idCategoria']);
    $where[] = "idCategoria = '$idCategoria'";
}


$filtro = '';
if (count($where) > 0) {
    $filtro = 'WHERE ' . implode(' AND ', $where);
}

$sql = "SELECT receita.idReceita, receita.nome as nomeReceita, receita.descricao, categoria.nome as nomeCategoria 
        FROM receita 
        INNER JOIN categoria ON receita.idCategoria = categoria.idCategoria";
$resultado = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("includes/menu.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">Relatório de receitas</h2>

    <form method="GET" action="">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label>Título</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>descricao</label>
                <select name="descricao" class="form-select">
                    <option value="">Todos</option>
                    <?php while ($a = mysqli_fetch_assoc($descricaoes)) : ?>
                        <option value="<?= htmlspecialchars($a['descricao']) ?>" <?= (($_GET['descricao'] ?? '') == $a['descricao']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['descricao']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>idCategoria</label>
                <select name="idCategoria" class="form-select">
                    <option value="">Todas</option>
                    <?php while ($e = mysqli_fetch_assoc($idCategorias)) : ?>
                        <option value="<?= htmlspecialchars($e['idCategoria']) ?>" <?= (($_GET['idCategoria'] ?? '') == $e['idCategoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['idCategoria']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>            
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>nome</th>
                        <th>descricao</th>
                        <th>idCategoria</th>                       
                        <th>Capa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultado) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                            <tr>
                                <td><?= $row['idReceita'] ?></td>
                                <td><?= htmlspecialchars($row['nomeReceita']) ?></td>
                                <td><?= htmlspecialchars($row['descricao']) ?></td>
                                <td><?= htmlspecialchars($row['nomeCategoria']) ?></td>                               
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhum receita encontrado com os filtros selecionados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
