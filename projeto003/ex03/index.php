<?php
include_once("includes/conexao.php");

/* ====== Métricas ====== */
$totReceitas   = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) AS t FROM receita"))['t'] ?? 0;
$totProdutos   = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) AS t FROM produto"))['t'] ?? 0;
$totCategorias = mysqli_fetch_assoc(mysqli_query($conexao, "SELECT COUNT(*) AS t FROM categoria"))['t'] ?? 0;

/* ====== Listas recentes ====== */
$sqlUltimasReceitas = "
  SELECT r.idReceita,
         r.nome AS nomeReceita,
         c.nome AS nomeCategoria,
         (SELECT COUNT(*) FROM receita_produto rp WHERE rp.idReceita = r.idReceita) AS itens
  FROM receita r
  INNER JOIN categoria c ON c.idCategoria = r.idCategoria
  ORDER BY r.idReceita DESC
  LIMIT 5
";
$ultimasReceitas = mysqli_query($conexao, $sqlUltimasReceitas);

$ultimosProdutos = mysqli_query($conexao, "
  SELECT idProduto, nome
  FROM produto
  ORDER BY idProduto DESC
  LIMIT 5
");

$ultimasCategorias = mysqli_query($conexao, "
  SELECT idCategoria, nome
  FROM categoria
  ORDER BY idCategoria DESC
  LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel | Receitas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos custom -->
  <link href="./includes/style.css" rel="stylesheet">
  <!-- (Opcional) Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .stat-card .icon {
      font-size: 1.75rem;
      opacity: .6;
    }

    .table-sm td,
    .table-sm th {
      padding: .5rem .75rem;
    }
  </style>
</head>

<body>
  <?php include_once("includes/menu.php"); ?>

  <main class="container my-4">
    <!-- Header / Ações rápidas -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
      <div>
        <h1 class="h3 mb-1">Bem-vindo ao Painel</h1>
        <div class="text-muted">Gerencie receitas, produtos e categorias com rapidez.</div>
      </div>
      <div class="d-flex gap-2">
        <a href="receitas.php" class="btn btn-primary"><i class="bi bi-journal-check me-1"></i> Receitas</a>
        <a href="produto.php#form" class="btn btn-outline-primary"><i class="bi bi-basket me-1"></i> Novo
          Produto</a>
        <a href="categoria.php#form" class="btn btn-outline-secondary"><i class="bi bi-folder-plus me-1"></i>
          Nova Categoria</a>
      </div>
    </div>

    <!-- Métricas -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-4">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted small">Receitas</div>
              <div class="h4 mb-0"><?= (int)$totReceitas ?></div>
            </div>
            <i class="bi bi-journal-text icon"></i>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="receitas.php" class="small text-decoration-none">Ver todas →</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted small">Produtos</div>
              <div class="h4 mb-0"><?= (int)$totProdutos ?></div>
            </div>
            <i class="bi bi-basket2 icon"></i>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="produto.php" class="small text-decoration-none">Ver todos →</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="card stat-card h-100">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted small">Categorias</div>
              <div class="h4 mb-0"><?= (int)$totCategorias ?></div>
            </div>
            <i class="bi bi-tags icon"></i>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="categoria.php" class="small text-decoration-none">Ver todas →</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Listas recentes -->
    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Últimas receitas</span>
            <a href="receitas.php" class="small text-decoration-none">Ver mais</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm table-striped mb-0 align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Receita</th>
                    <th>Categoria</th>
                    <th class="text-center">Itens</th>
                    <th class="text-end">Ação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($ultimasReceitas && mysqli_num_rows($ultimasReceitas) > 0): ?>
                    <?php while ($r = mysqli_fetch_assoc($ultimasReceitas)): ?>
                      <tr>
                        <td><strong><?= htmlspecialchars($r['nomeReceita']) ?></strong></td>
                        <td class="text-muted"><?= htmlspecialchars($r['nomeCategoria']) ?></td>
                        <td class="text-center"><?= (int)$r['itens'] ?></td>
                        <td class="text-end">
                          <a class="btn btn-sm btn-outline-primary"
                            href="receitas.php?idReceita=<?= (int)$r['idReceita'] ?>">Abrir</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted p-4">Nenhuma receita cadastrada.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Últimos produtos</span>
            <a href="produto.php" class="small text-decoration-none">Ver mais</a>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              <?php if ($ultimosProdutos && mysqli_num_rows($ultimosProdutos) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($ultimosProdutos)): ?>
                  <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                    href="produto.php?idProduto=<?= (int)$p['idProduto'] ?>#form">
                    <span><?= htmlspecialchars($p['nome']) ?></span>
                    <span class="badge text-bg-light"><?= (int)$p['idProduto'] ?></span>
                  </a>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="list-group-item text-muted">Nenhum produto cadastrado.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Últimas categorias</span>
            <a href="categoria.php" class="small text-decoration-none">Ver mais</a>
          </div>
          <div class="card-body p-0">
            <div class="list-group list-group-flush">
              <?php if ($ultimasCategorias && mysqli_num_rows($ultimasCategorias) > 0): ?>
                <?php while ($c = mysqli_fetch_assoc($ultimasCategorias)): ?>
                  <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                    href="categoria.php?idCategoria=<?= (int)$c['idCategoria'] ?>#form">
                    <span><?= htmlspecialchars($c['nome']) ?></span>
                    <span class="badge text-bg-light"><?= (int)$c['idCategoria'] ?></span>
                  </a>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="list-group-item text-muted">Nenhuma categoria cadastrada.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /row -->
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>