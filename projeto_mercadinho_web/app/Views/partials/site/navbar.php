<nav class="navbar navbar-expand-lg navbar-dark bg-danger border-bottom shadow-sm mb-3 py-3">
  <div class="container">
    <a class="navbar-brand text-white" href="<?= \App\Core\Url::to('/') ?>">Mercadinho Borba Gato</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav flex-grow-1">
        <li class="nav-item">
          <a href="<?= \App\Core\Url::to('/') ?>" class="nav-link text-white">Principal</a>
        </li>
        <li class="nav-item">
          <a href="<?= \App\Core\Url::to('/contato') ?>" class="nav-link text-white">Contato</a>
        </li>
      </ul>
      <div class="align-self-end position-relative">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="#" class="nav-link text-white" data-bs-toggle="modal" data-bs-target="#modalCadastro">Quero Me Cadastrar</a>
          </li>
          <li class="nav-item">
            <a href="<?= \App\Core\Url::to('/login') ?>" class="nav-link text-white">Entrar</a>
          </li>
          <li class="nav-item">
            <span class="badge rounded-pill bg-light text-danger position-absolute ms-4 mt-0">
              <small>5</small>
            </span>
            <a href="<?= \App\Core\Url::to('/carrinho') ?>" class="nav-link text-white">
              <img src="<?= \App\Core\Url::to('/assets/site/img/cart.svg') ?>" alt="Carrinho" style="width:24px;height:24px"/>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Modal Cadastro (stub) -->
<div class="modal fade" id="modalCadastro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Cadastro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Formulário de cadastro aqui (integraremos com /registrar).</p>
      </div>
    </div>
  </div>
</div>
