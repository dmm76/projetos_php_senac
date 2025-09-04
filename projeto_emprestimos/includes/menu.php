<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$nivel = strtolower($_SESSION['nivel'] ?? ($_SESSION['user']['nivel'] ?? 'comum'));
$logado = !empty($_SESSION['id_usuario']);
$__atual = basename($_SERVER['PHP_SELF']);
function active($a,$b){ return $a===$b?'active':''; }
?>
<svg xmlns="http://www.w3.org/2000/svg" class="d-none"></svg>

<main class="d-flex flex-nowrap">
  <h1 class="visually-hidden">Sidebars</h1>

  <!-- COLUNA DA SIDEBAR -->
  <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width:280px; height:100vh">
    <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi pe-none me-2" width="40" height="32" aria-hidden="true"></svg>
      <span class="fs-4">Sidebar</span>
    </a>
    <hr />
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="index.php" class="nav-link text-white <?php echo active('index.php',$__atual); ?>">
          <svg class="bi pe-none me-2" width="16" height="16"></svg> Home
        </a>
      </li>

      <?php if ($logado): ?>
        <!-- VISÍVEL PARA QUALQUER USUÁRIO LOGADO -->
        <li>
          <a href="reservas.php" class="nav-link text-white <?php echo active('reservas.php',$__atual); ?>">
            <svg class="bi pe-none me-2" width="16" height="16"></svg> Reservas
          </a>
        </li>
        <li>
          <a href="emprestimos.php" class="nav-link text-white <?php echo active('emprestimos.php',$__atual); ?>">
            <svg class="bi pe-none me-2" width="16" height="16"></svg> Empréstimos
          </a>
        </li>
      <?php endif; ?>

      <?php if ($logado && $nivel === 'admin'): ?>
        <!-- APENAS ADMIN -->
        <li>
          <a href="ferramentas.php" class="nav-link text-white <?php echo active('ferramentas.php',$__atual); ?>">
            <svg class="bi pe-none me-2" width="16" height="16"></svg> Ferramentas
          </a>
        </li>
        <li>
          <a href="usuarios.php" class="nav-link text-white <?php echo active('usuarios.php',$__atual); ?>">
            <svg class="bi pe-none me-2" width="16" height="16"></svg> Usuários
          </a>
        </li>
      <?php endif; ?>
    </ul>
    <hr />

    <?php if ($logado): ?>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <img src="https://github.com/mdo.png" width="32" height="32" class="rounded-circle me-2" />
          <div class="d-flex flex-column">
            <strong><?php echo htmlspecialchars($_SESSION['nome_usuario'] ?? $_SESSION['email_usuario'] ?? 'Usuário'); ?></strong>
            <small class="text-secondary">Perfil: <?php echo htmlspecialchars($nivel); ?></small>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
          <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item" href="logout.php">Sair</a></li>
        </ul>
      </div>
    <?php else: ?>
      <div class="mt-3">
        <a href="login.php" class="btn btn-outline-light w-100">Login</a>
      </div>
    <?php endif; ?>
  </div>
  <!-- NÃO FECHAR O </main> AQUI -->
