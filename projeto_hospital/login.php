<?php
  
  include_once("includes/classes/Usuario.php");

  $bd = new Database();
  $Usuario = new Usuario($bd);

  if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $dados = $Usuario->login($email, $senha);

    if (isset($dados['email'])) {
      
      $_SESSION['idUsuario'] = $dados['idUsuario'];
      $_SESSION['nivel'] = $dados['nivel'];

      if ($_SESSION['nivel']=='recepcao') {
        header('Location: index.php');
      }

      if ($_SESSION['nivel']=='enfermeiro') {
        header('Location: preconsulta.php');
      }

      if ($_SESSION['nivel']=='medico') {
        header('Location: consulta.php');
      }

      if ($_SESSION['nivel']=='adm') {
        header('Location: index.php');
      }
    } else {
      header('Location: login.php?verifique os dados');
    }

  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tela de Login</title>
  <!-- Bootstrap 5 CDN -->
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
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card">
      <h3 class="text-center mb-4">Login</h3>
      <form action="" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Digite seu e-mail" required>
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS (opcional, para funcionalidades como modal ou toast) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
