<?php
use App\Core\Url;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Csrf;

/** @var array{id:int,nome:string,email:string,perfil:string,ativo:int}|null $user */
$user = $user ?? Auth::user();
$nome  = htmlspecialchars($user['nome'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title><?= htmlspecialchars($title ?? 'Meus Dados') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= Url::to('/assets/site/css/style.css') ?>"/>
  <style>.sidebar-sticky{position:sticky;top:1rem}</style>
</head>
<body>
<div class="d-flex flex-column wrapper">

  <?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

  <main class="flex-fill">
    <div class="container py-3">
      <div class="row g-3">
        <div class="col-12 col-lg-3">
        <?php require dirname(__DIR__) . '/partials/conta-sidebar.php'; ?>
        </div>

        <div class="col-12 col-lg-9">
          <h1 class="h4 mb-3">Meus Dados</h1>

          <?php if ($m = Flash::get('success')): ?>
            <div class="alert alert-success"><?= htmlspecialchars($m) ?></div>
          <?php endif; ?>
          <?php if ($m = Flash::get('error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
          <?php endif; ?>

          <div class="row g-3">
            <div class="col-12 col-lg-6">
              <div class="card shadow-sm">
                <div class="card-header bg-white"><strong>Informações de Perfil</strong></div>
                <div class="card-body">
                  <form method="post" action="<?= Url::to('/conta/dados') ?>">
                    <?= Csrf::input() ?>
                    <div class="mb-3">
                      <label class="form-label">Nome</label>
                      <input type="text" name="nome" class="form-control" value="<?= $nome ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">E-mail</label>
                      <input type="email" class="form-control" value="<?= $email ?>" disabled>
                      <div class="form-text">Para alterar o e-mail, contate o suporte.</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Telefone (opcional)</label>
                      <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($telefone ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                      <button class="btn btn-primary" type="submit" name="acao" value="atualizar_perfil">Salvar alterações</button>
                      <a class="btn btn-outline-secondary" href="<?= Url::to('/conta') ?>">Cancelar</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="card shadow-sm">
                <div class="card-header bg-white"><strong>Alterar Senha</strong></div>
                <div class="card-body">
                  <form method="post" action="<?= Url::to('/conta/dados') ?>">
                    <?= Csrf::input() ?>
                    <div class="mb-3">
                      <label class="form-label">Senha atual</label>
                      <input type="password" name="senha_atual" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Nova senha</label>
                      <input type="password" name="senha_nova" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Confirmar nova senha</label>
                      <input type="password" name="senha_confirma" class="form-control" required>
                    </div>
                    <button class="btn btn-outline-primary" type="submit" name="acao" value="alterar_senha">Atualizar senha</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= Url::to('/assets/site/js/script.js') ?>"></script>
</body>
</html>
