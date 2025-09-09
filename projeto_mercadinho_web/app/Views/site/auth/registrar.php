<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title ?? 'Registrar') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:520px">
  <h1 class="h4 mb-3">Criar conta</h1>
  <?php if ($m = \App\Core\Flash::get('error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= \App\Core\Url::to('/registrar') ?>">
    <?= \App\Core\Csrf::input() ?>
    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input class="form-control" type="text" name="nome" required>
    </div>
    <div class="mb-3">
      <label class="form-label">E-mail</label>
      <input class="form-control" type="email" name="email" required>
    </div>
    <div class="row g-2">
      <div class="col-6">
        <label class="form-label">Senha</label>
        <input class="form-control" type="password" name="password" required>
      </div>
      <div class="col-6">
        <label class="form-label">Confirmar</label>
        <input class="form-control" type="password" name="password2" required>
      </div>
    </div>
    <button class="btn btn-success w-100 mt-3">Registrar</button>
  </form>
  <p class="mt-3 mb-0"><a href="<?= \App\Core\Url::to('/login') ?>">Já tenho conta</a></p>
</div>
</body>
</html>
