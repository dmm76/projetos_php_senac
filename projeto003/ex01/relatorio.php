<?php
include_once("includes/conexao.php");

// Início da lógica de filtros
$where = [];

if (!empty($_GET['descricao'])) {
    $descricao = mysqli_real_escape_string($conexao, $_GET['descricao']);
    $where[] = "t.descricao LIKE '%$descricao%'";
}

if (!empty($_GET['dataInicial'])) {
    $dataInicial = $_GET['dataInicial'];
    $where[] = "t.dataInicial >= '$dataInicial'";
}

if (!empty($_GET['dataFinal'])) {
    $dataFinal = $_GET['dataFinal'];
    $where[] = "t.dataFinal <= '$dataFinal'";
}

if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conexao, $_GET['status']);
    $where[] = "t.status = '$status'";
}

if (!empty($_GET['idUsuario'])) {
    $idUsuario = (int)$_GET['idUsuario'];
    $where[] = "t.idUsuario = $idUsuario";
}

$filtro = '';
if (count($where) > 0) {
    $filtro = 'WHERE ' . implode(' AND ', $where);
}

$sql = "
    SELECT t.*, u.nome 
    FROM tarefa t 
    INNER JOIN usuario u ON t.idUsuario = u.idUsuario 
    $filtro 
    ORDER BY t.dataInicial DESC
";

$resultado = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once("includes/menu.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">Relatório de Tarefas</h2>

    <form method="GET" action="">
        <div class="row">
            <div class="col-md-4">
                <label>Descrição</label>
                <input type="text" name="descricao" class="form-control" value="<?= $_GET['descricao'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label>Data Inicial (a partir de)</label>
                <input type="date" name="dataInicial" class="form-control" value="<?= $_GET['dataInicial'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label>Data Final (até)</label>
                <input type="date" name="dataFinal" class="form-control" value="<?= $_GET['dataFinal'] ?? '' ?>">
            </div>
            <div class="col-md-3 mt-2">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="iniciado" <?= ($_GET['status'] ?? '') == 'iniciado' ? 'selected' : '' ?>>Iniciado</option>
                    <option value="em andamento" <?= ($_GET['status'] ?? '') == 'em andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="concluido" <?= ($_GET['status'] ?? '') == 'concluido' ? 'selected' : '' ?>>Concluído</option>
                </select>
            </div>
            <div class="col-md-3 mt-2">
                <label>Usuário</label>
                <select name="idUsuario" class="form-select">
                    <option value="">Todos</option>
                    <?php
                    $usuarios = mysqli_query($conexao, "SELECT * FROM usuario ORDER BY nome");
                    while ($user = mysqli_fetch_assoc($usuarios)) {
                        $selected = ($_GET['idUsuario'] ?? '') == $user['idUsuario'] ? 'selected' : '';
                        echo "<option value='{$user['idUsuario']}' $selected>{$user['nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mt-4">
                <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Status</th>
                        <th>Usuário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultado) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultado)) : ?>
                            <tr>
                                <td><?= $row['idTarefa'] ?></td>
                                <td><?= htmlspecialchars($row['descricao']) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['dataInicial'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['dataFinal'])) ?></td>
                                <td><?= $row['status'] ?></td>
                                <td><?= $row['nome'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma tarefa encontrada com os filtros selecionados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
