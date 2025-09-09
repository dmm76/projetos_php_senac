<!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Unidades') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body><div class="container py-4">
<h1 class="h4 mb-3">Unidades</h1>
<?php if ($m=\App\Core\Flash::get('success')): ?><div class="alert alert-success"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<?php if ($m=\App\Core\Flash::get('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($m) ?></div><?php endif; ?>
<a class="btn btn-primary mb-3" href="<?= \App\Core\Url::to('/admin/unidades/criar') ?>">Nova Unidade</a>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Sigla</th><th>Descrição</th><th class="text-end">Ações</th></tr></thead><tbody>
<?php foreach (($unidades ?? []) as $u): ?>

<tr>
  <td><?= (int)$u->id ?></td>
  <td><?= htmlspecialchars($u->sigla) ?></td>
  <td><?= htmlspecialchars($u->descricao ?? '') ?></td>
  <td class="text-end">
    <a class="btn btn-sm btn-secondary" href="<?= \App\Core\Url::to('/admin/unidades/editar').'?id='.(int)$u->id ?>">Editar</a>
    <form method="post" action="<?= \App\Core\Url::to('/admin/unidades/excluir') ?>" style="display:inline" onsubmit="return confirm('Excluir?')">
      <?= \App\Core\Csrf::input() ?><input type="hidden" name="id" value="<?= (int)$u->id ?>">
      <button class="btn btn-sm btn-outline-danger">Excluir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody></table>
</div></body></html>
