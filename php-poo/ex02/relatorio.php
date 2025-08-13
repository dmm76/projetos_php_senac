<?php
include_once("includes/classes/Livro.php");

$bd = new Database();
$livro = new Livro($bd);

// Preenche opções de autores, editoras e anos de publicação:
$autores = mysqli_query($bd->conexao, "SELECT DISTINCT autor FROM livro ORDER BY autor");
$editoras = mysqli_query($bd->conexao, "SELECT DISTINCT editora FROM livro ORDER BY editora");
$anos = mysqli_query($bd->conexao, "SELECT DISTINCT anoPublicacao FROM livro ORDER BY anoPublicacao DESC");


// Filtros
// ------- Monta filtros de forma segura -------
$conds  = [];
$params = [];
$types  = '';

if (!empty($_GET['titulo'])) {
    $conds[]  = "titulo LIKE ?";
    $params[] = '%' . $_GET['titulo'] . '%';
    $types   .= 's';
}
if (!empty($_GET['autor'])) {
    $conds[]  = "autor = ?";
    $params[] = $_GET['autor'];
    $types   .= 's';
}
if (!empty($_GET['editora'])) {
    $conds[]  = "editora = ?";
    $params[] = $_GET['editora'];
    $types   .= 's';
}
if (!empty($_GET['anoPublicacao'])) {
    $conds[]  = "anoPublicacao = ?";
    $params[] = (int)$_GET['anoPublicacao'];
    $types   .= 'i';
}

$sqlBase = "SELECT * FROM livro";
$sql     = $sqlBase . (count($conds) ? " WHERE " . implode(" AND ", $conds) : "") . " ORDER BY titulo ASC";

// ------- Executa com prepared statement -------
$stmt = $bd->conexao->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar consulta: " . $bd->conexao->error);
}

if (!empty($params)) {
    // PHP 5.6+: pode usar "..." para desempacotar
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result(); // requer mysqlnd
if ($resultado === false) {
    // Fallback simples (se seu PHP não tiver mysqlnd, avise que é necessário ou faça bind_result manual)
    die("Ative o mysqlnd ou adapte para bind_result.");
}

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
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($row = $resultado->fetch_assoc()) : ?>
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