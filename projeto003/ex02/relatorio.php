<?php
include_once("includes/conexao.php");

// Preenche opções de autores, editoras e anos de publicação:
$autores = mysqli_query($conexao, "SELECT DISTINCT autor FROM livro ORDER BY autor");
$editoras = mysqli_query($conexao, "SELECT DISTINCT editora FROM livro ORDER BY editora");
$anos = mysqli_query($conexao, "SELECT DISTINCT anoPublicacao FROM livro ORDER BY anoPublicacao DESC");

// Filtros
$where = [];

if (!empty($_GET['titulo'])) {
    $titulo = mysqli_real_escape_string($conexao, $_GET['titulo']);
    $where[] = "titulo LIKE '%$titulo%'";
}

if (!empty($_GET['autor'])) {
    $autor = mysqli_real_escape_string($conexao, $_GET['autor']);
    $where[] = "autor = '$autor'";
}

if (!empty($_GET['editora'])) {
    $editora = mysqli_real_escape_string($conexao, $_GET['editora']);
    $where[] = "editora = '$editora'";
}

if (!empty($_GET['anoPublicacao'])) {
    $ano = mysqli_real_escape_string($conexao, $_GET['anoPublicacao']);
    $where[] = "anoPublicacao = '$ano'";
}

$filtro = '';
if (count($where) > 0) {
    $filtro = 'WHERE ' . implode(' AND ', $where);
}

$sql = "SELECT * FROM livro $filtro ORDER BY titulo ASC";
$resultado = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("includes/menu.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">Relatório de Livros</h2>

    <form method="GET" action="">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($_GET['titulo'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>Autor</label>
                <select name="autor" class="form-select">
                    <option value="">Todos</option>
                    <?php while ($a = mysqli_fetch_assoc($autores)) : ?>
                        <option value="<?= htmlspecialchars($a['autor']) ?>" <?= (($_GET['autor'] ?? '') == $a['autor']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['autor']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Editora</label>
                <select name="editora" class="form-select">
                    <option value="">Todas</option>
                    <?php while ($e = mysqli_fetch_assoc($editoras)) : ?>
                        <option value="<?= htmlspecialchars($e['editora']) ?>" <?= (($_GET['editora'] ?? '') == $e['editora']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['editora']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Ano de Publicação</label>
                <select name="anoPublicacao" class="form-select">
                    <option value="">Todos</option>
                    <?php while ($y = mysqli_fetch_assoc($anos)) : ?>
                        <option value="<?= htmlspecialchars($y['anoPublicacao']) ?>" <?= (($_GET['anoPublicacao'] ?? '') == $y['anoPublicacao']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($y['anoPublicacao']) ?>
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
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Editora</th>
                        <th>Ano</th>
                        <th>Descrição</th>
                        <th>Capa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultado) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                            <tr>
                                <td><?= $row['idLivro'] ?></td>
                                <td><?= htmlspecialchars($row['titulo']) ?></td>
                                <td><?= htmlspecialchars($row['autor']) ?></td>
                                <td><?= htmlspecialchars($row['editora']) ?></td>
                                <td><?= $row['anoPublicacao'] ?></td>
                                <td><?= htmlspecialchars($row['descricao']) ?></td>
                                <td><?= htmlspecialchars($row['capa']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhum livro encontrado com os filtros selecionados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
