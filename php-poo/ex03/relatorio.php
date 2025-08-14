<?php
include_once("includes/classes/Receita.php");
//receita(idReceita, nome, descricao, idCategoria, foto)
$bd = new Database();
$receita = new Receita($bd);

// Preenche opções de nomees, descricaos e anos de publicação:
$nomes = mysqli_query($bd->conexao, "SELECT DISTINCT nome FROM receita ORDER BY nome");
$descricoes = mysqli_query($bd->conexao, "SELECT DISTINCT descricao FROM receita ORDER BY descricao");


// Filtros
// ------- Monta filtros de forma segura -------
$conds  = [];
$params = [];
$types  = '';


if (!empty($_GET['nome'])) {
    $conds[]  = "nome = ?";
    $params[] = $_GET['nome'];
    $types   .= 's';
}
if (!empty($_GET['descricao'])) {
    $conds[]  = "descricao = ?";
    $params[] = $_GET['descricao'];
    $types   .= 's';
}

$sqlBase = "SELECT * FROM receita";
$sql     = $sqlBase . (count($conds) ? " WHERE " . implode(" AND ", $conds) : "") . " ORDER BY nome ASC";

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
                    <label>Nome</label>
                    <select name="nome" class="form-select">
                        <option value="">Todos</option>
                        <?php while ($a = mysqli_fetch_assoc($nomes)) : ?>
                            <option value="<?= htmlspecialchars($a['nome']) ?>" <?= (($_GET['nome'] ?? '') == $a['nome']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Descricao</label>
                    <select name="descricao" class="form-select">
                        <option value="">Todas</option>
                        <?php while ($e = mysqli_fetch_assoc($descricoes)) : ?>
                            <option value="<?= htmlspecialchars($e['descricao']) ?>" <?= (($_GET['descricao'] ?? '') == $e['descricao']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['descricao']) ?>
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
                            <th>Nome</th>
                            <th>Descrição</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($row = $resultado->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row['idReceita'] ?></td>                                    
                                    <td><?= htmlspecialchars($row['nome']) ?></td>
                                    <td><?= htmlspecialchars($row['descricao']) ?></td>                                   
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum receita encontrada com os filtros selecionados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>