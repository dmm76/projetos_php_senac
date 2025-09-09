<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title ?? 'Dashboard') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h1 class="h4 mb-4">Dashboard</h1>

  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted">Produtos</div>
          <div class="h4 mb-0"><?= (int)($metrics['produtos'] ?? 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted">Estoque baixo</div>
          <div class="h4 mb-0"><?= (int)($metrics['estoque_baixo'] ?? 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <h2 class="h5 mb-3">Atalhos</h2>
  <div class="d-flex flex-wrap gap-2">
    <a class="btn btn-primary" href="<?= $links['produtos'] ?? '#' ?>">Produtos</a>
    <a class="btn btn-outline-secondary" href="<?= $links['categorias'] ?? '#' ?>">Categorias</a>
    <a class="btn btn-outline-secondary" href="<?= $links['marcas'] ?? '#' ?>">Marcas</a>
    <a class="btn btn-outline-secondary" href="<?= $links['unidades'] ?? '#' ?>">Unidades</a>
  </div>
</div>
</body>
</html>
