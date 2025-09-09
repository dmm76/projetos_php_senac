<!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Marcas') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="h4 mb-3">Marcas</h1>
<?php if ($m=\App\Core\Flash::get('success')): ?><div class="alert alert-success"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<?php if ($m=\App\Core\Flash::get('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<a class="btn btn-primary mb-3" href="<?= \App\Core\Url::to('/admin/marcas/criar') ?>">Nova Marca</a>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Nome</th><th class="text-end">Ações</th></tr></thead><tbody>
<?php foreach (($marcas ?? []) as $m): ?>

<tr>
  <td><?= (int)$m->id ?></td>
  <td><?= htmlspecialchars($m->nome) ?></td>
  <td class="text-end">
    <a class="btn btn-sm btn-secondary" href="<?= \App\Core\Url::to('/admin/marcas/editar').'?id='.(int)$m->id ?>">Editar</a>
    <form method="post" action="<?= \App\Core\Url::to('/admin/marcas/excluir') ?>" style="display:inline" onsubmit="return confirm('Excluir?')">
      <?= \App\Core\Csrf::input() ?><input type="hidden" name="id" value="<?= (int)$m->id ?>">
      <button class="btn btn-sm btn-outline-danger">Excluir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody></table>
</div></body></html>
