<?php
include_once("includes/classes/Usuario.php");

$bd = new Database();
$usuarioModel = new Usuario($bd);
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $user = $usuarioModel->login($email, $senha);

    if ($user) {
        $_SESSION['idUsuario'] = $user['idUsuario'];
        $_SESSION['nivel'] = $user['nivel'];

        header('Location: index.php'); // página interna
        exit;

    } else {
        $erro = 'E-mail ou senha inválidos.';
    }
}

if(isset($user['email'])){
  $_SESSION['idUsuario'] = $user['idUsario'];
  $_SESSION['nivel'] = $user['nivel'];

  if($_SESSION['nivel']=='recepcao'){
    header('Location: index.php');
  }

  if($_SESSION['nivel']=='enfermeiro'){
    header('Location: preconsulta.php');
  }  

  if($_SESSION['nivel']=='medico'){
    header('Location: consulta.php');
  } 

  if($_SESSION['nivel']=='admin'){
    header('Location: index.php');
  }  
}else{
  header('Location: login.php?verifique os dados');
}

?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>.login-card{max-width:420px}</style>
</head>
<body class="bg-light">
  <div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="login-card w-100">
      <div class="card shadow-sm rounded-4">
        <div class="card-body p-4">
          <h1 class="h4 text-center mb-4">Entrar</h1>

          <?php if ($erro): ?>
            <div class="alert alert-danger small"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>

          <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
              <label class="form-label" for="email">E-mail</label>
              <input type="email" name="email" id="email" class="form-control" required>
              <div class="invalid-feedback">Informe um e-mail válido.</div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="senha">Senha</label>
              <input type="password" name="senha" id="senha" class="form-control" minlength="4" required>
              <div class="invalid-feedback">Informe sua senha.</div>
            </div>

            <div class="d-grid">
              <button class="btn btn-primary" type="submit">Entrar</button>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center text-muted small mt-3 mb-0">Não tem conta? peça ao admin 🙂</p>
    </div>
  </div>
<script>
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  forms.forEach(f => f.addEventListener('submit', e => {
    if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
    f.classList.add('was-validated');
  }));
})();
</script>
</body>
</html>
