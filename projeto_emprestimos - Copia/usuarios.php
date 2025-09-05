<?php
include_once("includes/classes/Usuario.php");
require_once 'includes/auth.php';
require_once 'includes/acl.php';

requireLogin();
requireRole(['admin']); // só admin acessa

$bd = new Database();
$usuario = new Usuario($bd);
$usuarioBD = new Usuario($bd);

if (isset($_GET['idUsuario'])) {
  $idUsuario = $_GET['idUsuario'];
  $dados = $usuarioBD->buscaID($idUsuario);

  $nome = $dados['nome'];
  $email = $dados['email'];
  $apartamento = $dados['apartamento'];
} else {
  $idUsuario = 0;
  $nome = "";
  $email = "";
  $apartamento = "";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = [
    'idUsuario' => $_POST['idUsuario'],
    'nome' => $_POST['nome'],
    'email' => $_POST['email'],
    'senha' => $_POST['senha'],
    'apartamento' => $_POST['apartamento'],
  ];

  if ($usuario->inserir($data)) {
    header("Location: usuarios.php?msg=Deu certo!");
  } else {
    header("Location: usuarios.php?msg=Deu ERRO!");
  }
}

$usuarios = $usuario->listar();

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="" />
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
  <meta name="generator" content="Astro v5.13.2" />
  <title>Sidebars · Bootstrap v5.3</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <meta name="theme-color" content="#712cf9" />
  <link href="sidebars.css" rel="stylesheet" />
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .b-example-divider {
      width: 100%;
      height: 3rem;
      background-color: #0000001a;
      border: solid rgba(0, 0, 0, 0.15);
      border-width: 1px 0;
      box-shadow:
        inset 0 0.5em 1.5em #0000001a,
        inset 0 0.125em 0.5em #00000026;
    }

    .b-example-vr {
      flex-shrink: 0;
      width: 1.5rem;
      height: 100vh;
    }

    .bi {
      vertical-align: -0.125em;
      fill: currentColor;
    }

    .nav-scroller {
      position: relative;
      z-index: 2;
      height: 2.75rem;
      overflow-y: hidden;
    }

    .nav-scroller .nav {
      display: flex;
      flex-wrap: nowrap;
      padding-bottom: 1rem;
      margin-top: -1px;
      overflow-x: auto;
      text-align: center;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }

    .btn-bd-primary {
      --bd-violet-bg: #712cf9;
      --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
      --bs-btn-font-weight: 600;
      --bs-btn-color: var(--bs-white);
      --bs-btn-bg: var(--bd-violet-bg);
      --bs-btn-border-color: var(--bd-violet-bg);
      --bs-btn-hover-color: var(--bs-white);
      --bs-btn-hover-bg: #6528e0;
      --bs-btn-hover-border-color: #6528e0;
      --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
      --bs-btn-active-color: var(--bs-btn-hover-color);
      --bs-btn-active-bg: #5a23c8;
      --bs-btn-active-border-color: #5a23c8;
    }

    .bd-mode-toggle {
      z-index: 1500;
    }

    .bd-mode-toggle .bi {
      width: 1em;
      height: 1em;
    }

    .bd-mode-toggle .dropdown-menu .active .bi {
      display: block !important;
    }
  </style>
</head>

<body>

  <?php include_once("includes/menu.php"); ?>

  <div class="flex-grow-1 p-4">
    <h2>Empréstimos de Ferramentas</h2>
    <div class="card">
      <div class="card-body">
        <h3>Cadastro de Usuários</h3>
        <div class="row">
          <form action="" method="POST">
            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario ?>">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" value="<?php echo $nome ?>"
                  name="nome" placeholder="Digite o nome completo">
              </div>
              <div class="col-md-2 mb-3">
                <label for="apartamento" class="form-label">Apartamento</label>
                <input type="text" class="form-control" id="apartamento"
                  value="<?php echo $apartamento ?>" name="apartamento"
                  placeholder="Digite bloco e numero do apartamento">
              </div>
              <div class="col-md-3 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" value="<?php echo $email ?>"
                  name="email" placeholder="Digite o email">
              </div>
              <div class="col-md-2 mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha"
                  placeholder="Digite a senha">
              </div>
            </div>
            <div class="col-md-2 mt-2">
              <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
          </form>

        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">

        <div class="row">
          <table class="table table-bordered table-sm">
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Apartamento</th>
              <th>Ações</th>
            </tr>
            <?php
            foreach ($usuarios as $usuario) {
              echo '
									<tr>
										<td class="text-center" >' . $usuario['id'] . '</td>
										<td class="text-center" >' . $usuario['nome'] . '</td>
										<td class="text-center" >' . $usuario['email'] . '</td>
										<td class="text-center" >' . $usuario['apartamento'] . '</td>
										<td class="text-center" >
											<a class="btn btn-warning" href="?idUsuario=' . $usuario['id'] . '">Editar</a>
											<a class="btn btn-danger" onclick="return confirm(\'Deseja realmente excluir?\');" href="excluir.usuario.php?idUsuario=' . $usuario['id'] . '">Excluir</a>
										</td>
									</tr>';
            }

            ?>
          </table>
        </div>
      </div>
    </div>
  </div>

  </main>
  <script src="../assets/dist/js/bootstrap.bundle.min.js" class="astro-vvvwv3sm"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>