<?php
include_once("includes/classes/Usuario.php");

$bd = new Database();
$Usuario = new Usuario($bd);

$msg = "";
$next = $_GET['next'] ?? $_POST['next'] ?? 'index.php'; // <- destino

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $senha = trim($_POST['senha'] ?? '');

  if ($email === '' || $senha === '') {
    $msg = "Informe e-mail e senha.";
  } else {
    $dados = $Usuario->login($email, $senha); // precisa retornar 'nivel'

    if ($dados && isset($dados['id'])) {
      session_regenerate_id(true); // segurança
      $_SESSION['id_usuario']    = $dados['id'];
      $_SESSION['email_usuario'] = $dados['email'];
      $_SESSION['nome_usuario']  = $dados['nome'] ?? $dados['email'];
      $_SESSION['apartamento']   = $dados['apartamento'] ?? '';
      $_SESSION['nivel']         = strtolower($dados['nivel'] ?? 'comum');

      // simples sanidade para evitar open redirect
      if (preg_match('#^https?://#i', $next)) {
        $next = 'index.php';
      }

      header("Location: {$next}");
      exit;
    } else {
      $msg = "E-mail ou senha inválidos!";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tela de Login</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e0e0e0;
      height: 100vh;
    }

    .login-card {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card">
      <h3 class="text-center mb-4">Login</h3>

      <?php if (!empty($msg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Digite seu e-mail"
            required>
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha"
            required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>