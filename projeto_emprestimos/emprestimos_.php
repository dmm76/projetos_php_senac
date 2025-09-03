<?php

  include_once("includes/classes/Emprestimo.php");
  // include_once("includes/menu.php");

  // if (!isset($_SESSION['idEmprestimo'])) {
  // 	header("Location: login.php?Você precisa estar logado!");
  // 	exit();
  // }

  $bd = new Database();
  $emprestimo = new Emprestimo($bd);
  $emprestimoBD = new Emprestimo($bd);

  $usuarios= $emprestimo->listarUsuarios();
  $ferramentas= $emprestimo->listarFerramentas();

  if (isset($_GET['idEmprestimo'])) {
      $idEmprestimo = $_GET['idEmprestimo'];
      $dados = $emprestimoBD->buscaID($idEmprestimo);
      $id_ferramenta = $dados['id_ferramenta'];
      $id_usuario = $dados['id_usuario'];
      $data_emprestimo = $dados['data_emprestimo'];
      $data_devolucao = $dados['data_devolucao'];
  } else {
      $idEmprestimo = 0;
      $id_ferramenta = 0;
      $id_usuario = 0;
      $data_emprestimo = "";
      $data_devolucao = "";
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
          'idEmprestimo' => $_POST['idEmprestimo'],
          'id_ferramenta' => $_POST['id_ferramenta'],
          'id_usuario' => $_POST['id_usuario'],
          'data_emprestimo' => $_POST['data_emprestimo'],
          'senha' => $_POST['senha'],
          'data_devolucao' => $_POST['data_devolucao'],
      ];

      if ($emprestimo->inserir($data)) {
          header("Location: emprestimos.php?msg=Deu certo!");
      } else {
          header("Location: emprestimos.php?msg=Deu ERRO!");
      }
  }

  $emprestimos = $emprestimo->listar();

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta
      name="author"
      content="Mark Otto, Jacob Thornton, and Bootstrap contributors"
    />
    <meta name="generator" content="Astro v5.13.2" />
    <title>Sidebars · Bootstrap v5.3</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
                <h3>Cadastro de Empréstimos</h3>
                <div class="row">
                    <form action="" method="POST">
                        <input type="hidden" name="idEmprestimo" value="<?php echo $idEmprestimo ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="id_usuario" class="form-label">Usuario</label>
                                <select name="id_usuario" id="id_usuario"  class="form-select">
                                    <option value="<?php echo $id_usuario ?>">Selecione um Usuario</option>
                                    
                                        <?php
                                        foreach ($usuarios as $usuario) {
                                            if ($id_usuario == $usuario['id']) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option ' . $sel . ' value="' . $usuario['id'] . '">' . $usuario['nome'] . '</option>';
                                        }
                                        ?>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="id_ferramenta" class="form-label">Ferramenta</label>
                                <select name="id_ferramenta" id="id_ferramenta"  class="form-select">
                                    <option value="<?php echo $id_ferramenta ?>">Selecione um Ferramenta</option>
                                    
                                        <?php
                                        foreach ($ferramentas as $ferramenta) {
                                            if ($id_ferramenta == $ferramenta['id']) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option ' . $sel . ' value="' . $ferramenta['id'] . '">' . $ferramenta['nome'] . '</option>';
                                        }
                                        ?>

                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="data_emprestimo" class="form-label">E-mail</label>
                                <input type="date" class="form-control" id="data_emprestimo" value="<?php echo $data_emprestimo ?>" name="data_emprestimo" placeholder="Digite o data_emprestimo">
                            </div>                           

                            <div class="col-md-3 mb-3">
                                <label for="data_devolucao" class="form-label">data_devolucao</label>
                                <input type="date" class="form-control" id="data_devolucao" value="<?php echo $data_devolucao ?>" name="data_devolucao" placeholder="Digite bloco e numero do data_devolucao">
                            </div>
                            <div class="col-md-2 mt-4">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>                            
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
                            <th>Usuário</th>
                            <th>Ferramenta</th>
                            <th>data_emprestimo</th>
                            <th>data_devolucao</th>
                            <th>Ações</th>
                        </tr>
                        <?php
                        foreach ($emprestimos as $emprestimo) {
                            echo '
									<tr>
										<td>' . $emprestimo['id'] . '</td>                                        
										<td>' . $emprestimo['usuarioNome'] . '</td>
                                        <td>' . $emprestimo['ferramentaNome'] . '</td>
										<td>' . $emprestimo['data_emprestimo'] . '</td>
										<td>' . $emprestimo['data_devolucao'] . '</td>
										<td>
											<a href="?idEmprestimo=' . $emprestimo['id'] . '">Editar</a>
											<a onclick="return confirm(\'Deseja realmente excluir?\');" href="excluir.emprestimo.php?idEmprestimo=' . $emprestimo['id'] . '">Excluir</a>
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
    <script
      src="../assets/dist/js/bootstrap.bundle.min.js"
      class="astro-vvvwv3sm"
    ></script>
    <script src="sidebars.js" class="astro-vvvwv3sm"></script>
  </body>
</html>
