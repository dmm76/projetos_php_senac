<?php
use App\Core\Url;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger border-bottom shadow-sm mb-3 py-3">
  <div class="container">
    <a class="navbar-brand text-white" href="<?= Url::to('/') ?>">Mercadinho Borba Gato</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbarMain" class="collapse navbar-collapse">
      <!-- Esquerda -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a href="<?= Url::to('/') ?>" class="nav-link text-white <?= Url::is('/') ? 'active' : '' ?>">Principal</a>
        </li>
        <li class="nav-item">
          <a href="<?= Url::to('/contato') ?>" class="nav-link text-white <?= Url::is('/contato') ? 'active' : '' ?>">Contato</a>
        </li>
      </ul>

      <!-- Direita -->
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li class="nav-item me-2">
            <span class="nav-link text-white-50 small">
              Logado como <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></strong>
            </span>
          </li>
          <li class="nav-item">
            <a href="<?= Url::to('/logout') ?>" class="nav-link text-white">Sair</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a href="<?= Url::to('/registrar') ?>" class="nav-link text-white">Quero Me Cadastrar</a>
          </li>
          <li class="nav-item">
            <a href="<?= Url::to('/login') ?>" class="nav-link text-white">Entrar</a>
          </li>
        <?php endif; ?>

        <li class="nav-item position-relative ms-2">
          <!-- Use o ícone do Bootstrap por enquanto; quando criarmos /assets, pode trocar por <img src="<?= Url::to('/assets/site/img/cart.svg') ?>"> -->
          <a href="<?= Url::to('/carrinho') ?>" class="nav-link text-white position-relative">
            <i class="bi bi-cart" style="font-size:22px"></i>
            <span class="badge rounded-pill bg-light text-danger position-absolute top-0 start-100 translate-middle">
              <small><?= (int)($_SESSION['cart_count'] ?? 5) ?></small>
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
