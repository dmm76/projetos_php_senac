<?php
include_once("includes/conexao.php");
$msg = "";

// ----------------------------------------------------
// MODO: lista x detalhe
// ----------------------------------------------------
$idReceita = isset($_GET['idReceita']) ? (int)$_GET['idReceita'] : 0;
$modoLista = ($idReceita <= 0);

// ----------------------------------------------------
// LISTA DE RECEITAS (com busca) – quando não há idReceita
// ----------------------------------------------------
if ($modoLista) {
    // filtros
    $where = [];
    if (!empty($_GET['q'])) {
        $q = mysqli_real_escape_string($conexao, $_GET['q']);
        $where[] = "r.nome LIKE '%$q%'";
    }
    if (!empty($_GET['idCategoria'])) {
        $idCat = (int)$_GET['idCategoria'];
        $where[] = "r.idCategoria = $idCat";
    }
    $filtro = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // categorias para o select
    $resCategorias = mysqli_query($conexao, "SELECT idCategoria, nome FROM categoria ORDER BY nome");

    // receitas
    $sqlLista = "
        SELECT r.idReceita, r.nome, r.foto, c.nome AS nomeCategoria
        FROM receita r
        INNER JOIN categoria c ON c.idCategoria = r.idCategoria
        $filtro
        ORDER BY r.nome
    ";
    $resLista = mysqli_query($conexao, $sqlLista);
} else {
    // ----------------------------------------------------
    // DETALHE DA RECEITA – quando há idReceita
    // ----------------------------------------------------

    // busca dados
    $sqlReceita = "SELECT * FROM receita WHERE idReceita = $idReceita";
    $resReceita = mysqli_query($conexao, $sqlReceita);
    $receita = mysqli_fetch_assoc($resReceita);

    if (!$receita) {
        echo "<div class='alert alert-danger mt-4'>Receita não encontrada!</div>";
        exit;
    }

    // excluir item
    if (isset($_GET['acao'], $_GET['idProduto']) && $_GET['acao'] === 'excluir') {
        $idProdutoExcluir = (int)$_GET['idProduto'];
        $stmtDel = mysqli_prepare($conexao, "DELETE FROM receita_produto WHERE idReceita = ? AND idProduto = ?");
        mysqli_stmt_bind_param($stmtDel, "ii", $idReceita, $idProdutoExcluir);
        mysqli_stmt_execute($stmtDel);
        header("Location: receitas.php?idReceita=$idReceita");
        exit;
    }

    // upload de foto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_foto'])) {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($ext, $permitidas)) {
                $nomeFoto = "receita_" . $idReceita . "_" . time() . "." . $ext;
                $caminho = "img/$nomeFoto";
                if (!is_dir('img')) mkdir('img', 0777, true);
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
                    $caminhoSql = mysqli_real_escape_string($conexao, $caminho);
                    mysqli_query($conexao, "UPDATE receita SET foto = '$caminhoSql' WHERE idReceita = $idReceita");
                    $receita['foto'] = $caminho;
                    $msg = "<div class='alert alert-success mt-2'>Foto atualizada com sucesso!</div>";
                } else {
                    $msg = "<div class='alert alert-danger mt-2'>Erro ao salvar a foto.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger mt-2'>Tipo de arquivo não permitido. Use JPG, JPEG, PNG ou GIF.</div>";
            }
        } else {
            $msg = "<div class='alert alert-warning mt-2'>Selecione uma foto para enviar.</div>";
        }
    }

    // inserir produto
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProduto'], $_POST['quantidade']) && !isset($_POST['atualizar_foto'])) {
        $idProdutoPost = (int)$_POST['idProduto'];
        $quantidade = trim($_POST['quantidade']);

        $verifica = mysqli_prepare($conexao, "SELECT 1 FROM receita_produto WHERE idReceita = ? AND idProduto = ?");
        mysqli_stmt_bind_param($verifica, "ii", $idReceita, $idProdutoPost);
        mysqli_stmt_execute($verifica);
        $tem = mysqli_stmt_get_result($verifica);

        if (mysqli_num_rows($tem) == 0) {
            $sqlInsert = "INSERT INTO receita_produto (idReceita, idProduto, quantidade) VALUES (?, ?, ?)";
            $stmtIns = mysqli_prepare($conexao, $sqlInsert);
            mysqli_stmt_bind_param($stmtIns, "iis", $idReceita, $idProdutoPost, $quantidade);
            mysqli_stmt_execute($stmtIns);
        }
        header("Location: receitas.php?idReceita=$idReceita");
        exit;
    }

    // itens da receita
    $sqlProdutosReceita = "
        SELECT rp.idReceita, rp.idProduto, rp.quantidade, p.nome AS nomeProduto 
        FROM receita_produto rp
        INNER JOIN produto p ON rp.idProduto = p.idProduto
        WHERE rp.idReceita = $idReceita
        ORDER BY p.nome
    ";
    $resProdutosReceita = mysqli_query($conexao, $sqlProdutosReceita);
    $itens = [];
    if ($resProdutosReceita) {
        while ($row = mysqli_fetch_assoc($resProdutosReceita)) {
            $itens[] = $row;
        }
    }
    $totalItens = count($itens);

    // produtos para select
    $sqlProdutos = "SELECT idProduto, nome FROM produto ORDER BY nome";
    $resProdutos = mysqli_query($conexao, $sqlProdutos);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?= $modoLista ? 'Receitas' : 'Produtos da Receita' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <style>
        .recipe-card-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: .5rem;
            background: #f3f3f3;
        }

        .thumb {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: .5rem;
            background: #f3f3f3;
        }

        .muted {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="container mt-4">
        <?php if (!empty($msg)) echo $msg; ?>

        <?php if ($modoLista): ?>
            <h2 class="mb-3">Receitas</h2>

            <!-- Filtros -->
            <form class="row g-3 align-items-end mb-3" method="get">
                <div class="col-md-5">
                    <label class="form-label">Buscar por nome</label>
                    <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                        placeholder="Ex.: Bolo de Cenoura">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoria</label>
                    <select name="idCategoria" class="form-select">
                        <option value="">Todas</option>
                        <?php if ($resCategorias): while ($c = mysqli_fetch_assoc($resCategorias)): ?>
                                <option value="<?= (int)$c['idCategoria'] ?>"
                                    <?= (!empty($_GET['idCategoria']) && (int)$_GET['idCategoria'] === (int)$c['idCategoria'] ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($c['nome']) ?>
                                </option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filtrar</button>
                    <a class="btn btn-secondary w-100" href="receitas.php">Limpar</a>
                </div>
            </form>

            <!-- Lista -->
            <div class="row g-3">
                <?php if ($resLista && mysqli_num_rows($resLista) > 0): ?>
                    <?php while ($r = mysqli_fetch_assoc($resLista)): ?>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div>
                                        <?php if (!empty($r['foto']) && file_exists($r['foto'])): ?>
                                            <img src="<?= htmlspecialchars($r['foto']) ?>" class="thumb" alt="Capa">
                                        <?php else: ?>
                                            <div class="thumb d-flex align-items-center justify-content-center">
                                                <span class="muted">Sem foto</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong class="d-block"><?= htmlspecialchars($r['nome']) ?></strong>
                                        <small class="text-muted">Categoria: <?= htmlspecialchars($r['nomeCategoria']) ?></small>
                                    </div>
                                    <div>
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="receitas.php?idReceita=<?= (int)$r['idReceita'] ?>">
                                            Abrir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">Nenhuma receita encontrada.</div>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <h2 class="mb-3">Ingredientes da Receita</h2>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div>
                            <?php if (!empty($receita['foto']) && file_exists($receita['foto'])): ?>
                                <img src="<?= htmlspecialchars($receita['foto']) ?>" alt="Foto da Receita"
                                    class="recipe-card-img">
                            <?php else: ?>
                                <div class="recipe-card-img d-flex align-items-center justify-content-center">
                                    <span class="muted">Sem foto</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="d-block"><?= htmlspecialchars($receita['nome']) ?></strong>
                            <small class="d-block text-muted mb-2"><?= htmlspecialchars($receita['descricao']) ?></small>

                            <form method="post" enctype="multipart/form-data" class="d-flex gap-2">
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                                <input type="hidden" name="atualizar_foto" value="1">
                                <button type="submit" class="btn btn-secondary">Enviar nova foto</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM ADICIONAR PRODUTO -->
            <form method="post" class="row g-3 align-items-end mb-4">
                <div class="col-md-6">
                    <label class="form-label">Produto</label>
                    <select name="idProduto" class="form-select" required>
                        <option value="">Selecione o produto</option>
                        <?php if ($resProdutos): while ($prod = mysqli_fetch_assoc($resProdutos)): ?>
                                <option value="<?= (int)$prod['idProduto'] ?>"><?= htmlspecialchars($prod['nome']) ?></option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantidade</label>
                    <input type="text" name="quantidade" class="form-control" required
                        placeholder="Ex.: 2 xícaras, 300 g, 1 colher...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                </div>
            </form>

            <!-- ACORDEON DE PRODUTOS DA RECEITA -->
            <div class="accordion" id="accordionProdutos">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingItens">
                        <button class="accordion-button <?= $totalItens ? 'collapsed' : '' ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseItens" aria-expanded="false"
                            aria-controls="collapseItens">
                            Produtos da Receita
                            <span class="badge bg-secondary ms-2"><?= $totalItens ?></span>
                        </button>
                    </h2>
                    <div id="collapseItens" class="accordion-collapse collapse <?= $totalItens ? '' : 'show' ?>"
                        aria-labelledby="headingItens" data-bs-parent="#accordionProdutos">
                        <div class="accordion-body">
                            <?php if ($totalItens === 0): ?>
                                <div class="text-muted">Nenhum produto adicionado ainda.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:70%;">Produto</th>
                                                <th class="text-center" style="width:20%;">Quantidade</th>
                                                <th class="text-center" style="width:10%;">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($itens as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['nomeProduto']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($row['quantidade']) ?></td>
                                                    <td class="text-center">
                                                        <a href="receitas.php?idReceita=<?= $idReceita ?>&idProduto=<?= (int)$row['idProduto'] ?>&acao=excluir"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Remover este produto da receita?');">
                                                            Excluir
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <a href="receitas.php" class="btn btn-secondary mt-3">Voltar à lista</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>