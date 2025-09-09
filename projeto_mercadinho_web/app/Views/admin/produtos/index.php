<!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Produtos') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="h4 mb-3">Produtos</h1>

<?php if ($m=\App\Core\Flash::get('success')): ?><div class="alert alert-success"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<?php if ($m=\App\Core\Flash::get('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($m) ?></div><?php endif; ?>

<a class="btn btn-primary mb-3" href="<?= \App\Core\Url::to('/admin/produtos/criar') ?>">Novo Produto</a>

<table class="table table-striped align-middle">
  <thead><tr>
    <th>ID</th><th>Nome</th><th>Categoria</th><th>Marca</th><th>Unid.</th><th>Preço</th><th>Estoq.</th><th>Ativo</th><th class="text-end">Ações</th>
  </tr></thead>
  <tbody>
  <?php foreach (($produtos ?? []) as $p): ?>

    <tr>
      <td><?= (int)$p['id'] ?></td>
      <td><?= htmlspecialchars($p['nome']) ?></td>
      <td><?= htmlspecialchars($p['categoria'] ?? '-') ?></td>
      <td><?= htmlspecialchars($p['marca'] ?? '-') ?></td>
      <td><?= htmlspecialchars($p['unidade']) ?></td>
      <td>R$ <?= number_format((float)($p['preco_atual'] ?? 0), 2, ',', '.') ?></td>
      <td><?= number_format((float)($p['estoque_qtd'] ?? 0), 3, ',', '.') ?></td>
      <td><?= ((int)$p['ativo']) ? 'Sim' : 'Não' ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-secondary" href="<?= \App\Core\Url::to('/admin/produtos/editar').'?id='.(int)$p['id'] ?>">Editar</a>
        <form method="post" action="<?= \App\Core\Url::to('/admin/produtos/excluir') ?>" style="display:inline" onsubmit="return confirm('Excluir?')">
          <?= \App\Core\Csrf::input() ?>
          <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <button class="btn btn-sm btn-outline-danger">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div></body></html>
