<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <!-- ... seus <symbol> ... -->
</svg>

<main class="d-flex flex-nowrap">
  <h1 class="visually-hidden">Sidebars</h1>
  <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width:280px; height:100vh">
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi pe-none me-2" width="40" height="32" aria-hidden="true">
        <use xlink:href="#bootstrap"></use>
      </svg>
      <span class="fs-4">Sidebar</span>
    </a>
    <hr />
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="index.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='index.php'?'active':''; ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
          Home
        </a>
      </li>
      <li>
        <a href="reservas.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='reservas.php'?'active':''; ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
          Reservas
        </a>
      </li>
      <li>
        <a href="emprestimos.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='emprestimos.php'?'active':''; ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
          Empréstimos
        </a>
      </li>
      <li>
        <a href="ferramentas.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='ferramentas.php'?'active':''; ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
          Ferramentas
        </a>
      </li>
      <li>
        <a href="usuarios.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF'])=='usuarios.php'?'active':''; ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
          Usuários
        </a>
      </li>
    </ul>
    <hr />

    <?php if (isset($_SESSION['id_usuario'])): ?>
      <!-- Usuário logado -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2" />
          <strong><?php echo htmlspecialchars($_SESSION['nome_usuario'] ?? $_SESSION['email_usuario']); ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
          <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item" href="logout.php">Sair</a></li>
        </ul>
      </div>
    <?php else: ?>
      <!-- Usuário não logado -->
      <div class="mt-3">
        <a href="login.php" class="btn btn-outline-light w-100">Login</a>
      </div>
    <?php endif; ?>
  </div>
