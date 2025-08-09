<?php
include_once("includes/conexao.php");

// =======================
// Filtros
// =======================
$where = [];

if (!empty($_GET['nome'])) {
    $nome = mysqli_real_escape_string($conexao, $_GET['nome']);
    $where[] = "r.nome LIKE '%$nome%'";
}

if (!empty($_GET['idCategoria'])) {
    $idCategoria = (int)$_GET['idCategoria'];
    $where[] = "r.idCategoria = $idCategoria";
}

$filtro = '';
if (!empty($where)) {
    $filtro = 'WHERE ' . implode(' AND ', $where);
}

// =======================
// Carregar opções de categoria pro filtro
// =======================
$categorias = mysqli_query($conexao, "SELECT idCategoria, nome FROM categoria ORDER BY nome");

// =======================
// 1) Buscar receitas (com categoria e foto) já filtradas
// =======================
$sqlReceitas = "
    SELECT
        r.idReceita,
        r.nome       AS nomeReceita,
        r.foto,
        c.nome       AS nomeCategoria
    FROM receita r
    INNER JOIN categoria c ON c.idCategoria = r.idCategoria
    $filtro
    ORDER BY r.nome ASC
";
$resReceitas = mysqli_query($conexao, $sqlReceitas);

// Montar lista de IDs para buscar produtos em lote (evitar N+1)
$ids = [];
$receitas = [];
if ($resReceitas && mysqli_num_rows($resReceitas) > 0) {
    while ($r = mysqli_fetch_assoc($resReceitas)) {
        $receitas[] = $r;
        $ids[] = (int)$r['idReceita'];
    }
}

// =======================
// 2) Buscar produtos de TODAS as receitas filtradas de uma vez
// =======================
$produtosPorReceita = []; // [idReceita] => [ {nomeProduto, quantidade}, ... ]
if (!empty($ids)) {
    $idsStr = implode(',', $ids);
    $sqlItens = "
        SELECT
            rp.idReceita,
            p.nome      AS nomeProduto,
            rp.quantidade
        FROM receita_produto rp
        INNER JOIN produto p ON p.idProduto = rp.idProduto
        WHERE rp.idReceita IN ($idsStr)
        ORDER BY p.nome
    ";
    $resItens = mysqli_query($conexao, $sqlItens);
    while ($it = mysqli_fetch_assoc($resItens)) {
        $rid = (int)$it['idReceita'];
        if (!isset($produtosPorReceita[$rid])) {
            $produtosPorReceita[$rid] = [];
        }
        $produtosPorReceita[$rid][] = [
            'nomeProduto' => $it['nomeProduto'],
            'quantidade'  => $it['quantidade']
        ];
    }
}

// Helper pra imagem
function caminhoFoto($foto)
{
    if ($foto && file_exists($foto)) return $foto;
    // Placeholder local opcional:
    if (file_exists('img/placeholder_receita.jpg')) return 'img/placeholder_receita.jpg';
    // Ou um fallback genérico (sem depender da internet):
    return ''; // retorna vazio e tratamos no HTML
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .recipe-card-img {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: .5rem;
            background: #f3f3f3;
        }

        .recipe-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .recipe-meta {
            display: flex;
            flex-direction: column;
        }

        .muted {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="container my-4">
        <h2 class="mb-4">Relatório de Receitas</h2>

        <!-- Filtros -->
        <form method="GET" action="" class="mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Nome da Receita</label>
                    <input type="text" name="nome" class="form-control"
                        value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>" placeholder="Ex.: Bolo de Cenoura">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Categoria</label>
                    <select name="idCategoria" class="form-select">
                        <option value="">Todas</option>
                        <?php while ($c = mysqli_fetch_assoc($categorias)) : ?>
                            <option value="<?= (int)$c['idCategoria'] ?>"
                                <?= (isset($_GET['idCategoria']) && $_GET['idCategoria'] !== '' && (int)$_GET['idCategoria'] === (int)$c['idCategoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
                <div class="col-md-2">
                    <a href="relatorio_receitas.php" class="btn btn-secondary w-100">Limpar</a>
                </div>
            </div>
        </form>

        <!-- Lista em acordeon -->
        <?php if (!empty($receitas)): ?>
            <div class="accordion" id="accordionReceitas">
                <?php foreach ($receitas as $idx => $r): ?>
                    <?php
                    $rid   = (int)$r['idReceita'];
                    $foto  = caminhoFoto($r['foto']);
                    $tituloId   = "heading-{$rid}";
                    $collapseId = "collapse-{$rid}";
                    ?>
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header" id="<?= $tituloId ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                                <div class="recipe-header">
                                    <?php if ($foto): ?>
                                        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto da Receita" class="recipe-card-img">
                                    <?php else: ?>
                                        <div class="recipe-card-img d-flex align-items-center justify-content-center">
                                            <span class="muted">Sem foto</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="recipe-meta">
                                        <strong><?= htmlspecialchars($r['nomeReceita']) ?></strong>
                                        <span class="muted">Categoria: <?= htmlspecialchars($r['nomeCategoria']) ?></span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $tituloId ?>"
                            data-bs-parent="#accordionReceitas">
                            <div class="accordion-body">
                                <?php
                                $itens = $produtosPorReceita[$rid] ?? [];
                                if (empty($itens)):
                                ?>
                                    <div class="text-muted">Esta receita ainda não possui produtos vinculados.</div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 70%;">Produto</th>
                                                    <th class="text-center" style="width: 30%;">Quantidade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($itens as $it): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($it['nomeProduto']) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($it['quantidade']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Nenhuma receita encontrada com os filtros selecionados.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>