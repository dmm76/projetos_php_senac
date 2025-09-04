<?php
include_once("includes/classes/Emprestimo.php");
// Se você já tem uma classe Reserva, pode incluir aqui, mas abaixo farei a consulta direta.
// include_once("includes/classes/Reserva.php");

$nivel = strtolower($_SESSION['nivel'] ?? 'comum');
$bd = new Database();

$emprestimo   = new Emprestimo($bd);
$emprestimoBD = new Emprestimo($bd);

/* Listas para os selects do formulário */
$usuarios    = $emprestimo->listarUsuarios();
$ferramentas = $emprestimo->listarFerramentas();

/* Edição */
if (isset($_GET['idEmprestimo'])) {
  $idEmprestimo    = (int)$_GET['idEmprestimo'];
  $dados           = $emprestimoBD->buscaID($idEmprestimo);
  $id_ferramenta   = $dados['id_ferramenta'];
  $id_usuario      = $dados['id_usuario'];
  $data_emprestimo = $dados['data_emprestimo'];
  $data_devolucao  = $dados['data_devolucao'];
} else {
  $idEmprestimo = 0;
  $id_ferramenta = 0;
  $id_usuario = 0;
  $data_emprestimo = "";
  $data_devolucao  = "";
}

/* Salvar (apenas admin) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nivel === 'admin') {
  $data = [
    'idEmprestimo'    => $_POST['idEmprestimo'],
    'id_ferramenta'   => $_POST['id_ferramenta'],
    'id_usuario'      => $_POST['id_usuario'],
    'data_emprestimo' => $_POST['data_emprestimo'],
    'senha'           => $_POST['senha'] ?? null, // não usado, mas deixei pra manter compatibilidade
    'data_devolucao'  => $_POST['data_devolucao'],
  ];

  if ($emprestimo->inserir($data)) {
    header("Location: emprestimos.php?msg=Deu certo!");
    exit;
  } else {
    header("Location: emprestimos.php?msg=Deu ERRO!");
    exit;
  }
}

/* Listas para as tabelas */
$emprestimos = $emprestimo->listar();

/* ===== Reservas (consulta direta) =====
   Se você já tem método na classe, pode trocar pelo $reserva->listarAtivas() etc. */
$reservas = [];
$sqlReservas = "
  SELECT r.id, r.id_ferramenta, r.id_usuario, r.data_reserva, r.status,
         f.nome AS ferramentaNome, u.nome AS usuarioNome
    FROM reserva r
    JOIN ferramenta f ON f.id = r.id_ferramenta
    JOIN usuario   u ON u.id = r.id_usuario
ORDER BY r.data_reserva DESC
";
if ($rs = $bd->query($sqlReservas)) {
  while ($row = $rs->fetch_assoc()) {
    $reservas[] = $row;
  }
}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Empréstimos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="sidebars.css" rel="stylesheet" />
</head>

<body>

  <?php include_once("includes/menu.php"); ?>

  <div class="flex-grow-1 p-4">
    <h2>Empréstimos de Ferramentas</h2>

    <?php if ($nivel === 'admin'): ?>
      <!-- Formulário de cadastro (apenas admin) -->
      <div class="card mb-4">
        <div class="card-body">
          <h3>Cadastro de Empréstimos</h3>
          <form action="" method="POST">
            <input type="hidden" name="idEmprestimo" value="<?php echo (int)$idEmprestimo; ?>">
            <div class="row g-3">
              <div class="col-md-3">
                <label for="id_usuario" class="form-label">Usuário</label>
                <select name="id_usuario" id="id_usuario" class="form-select" required>
                  <option value="">Selecione um Usuário</option>
                  <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo (int)$usuario['id']; ?>"
                      <?php echo ($id_usuario == $usuario['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($usuario['nome']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="id_ferramenta" class="form-label">Ferramenta</label>
                <select name="id_ferramenta" id="id_ferramenta" class="form-select" required>
                  <option value="">Selecione uma Ferramenta</option>
                  <?php foreach ($ferramentas as $f): ?>
                    <option value="<?php echo (int)$f['id']; ?>"
                      <?php echo ($id_ferramenta == $f['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($f['nome']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="data_emprestimo" class="form-label">Data do Empréstimo</label>
                <input type="date" class="form-control" id="data_emprestimo"
                  name="data_emprestimo" value="<?php echo htmlspecialchars($data_emprestimo); ?>">
              </div>

              <div class="col-md-3">
                <label for="data_devolucao" class="form-label">Data de Devolução</label>
                <input type="date" class="form-control" id="data_devolucao"
                  name="data_devolucao" value="<?php echo htmlspecialchars($data_devolucao); ?>">
              </div>

              <div class="col-md-2 mt-4">
                <button type="submit" class="btn btn-primary w-100">Salvar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- Tabela de Empréstimos -->
    <div class="card mb-4">
      <div class="card-body">
        <h3>Lista de Empréstimos</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-sm align-middle">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Usuário</th>
                <th class="text-center">Ferramenta</th>
                <th class="text-center">Data Empréstimo</th>
                <th class="text-center">Data Devolução</th>
                <?php if ($nivel === 'admin'): ?>
                  <th class="text-center">Ações</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($emprestimos as $emp): ?>
                <tr>
                  <td class="text-center"><?php echo (int)$emp['id']; ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($emp['usuarioNome']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($emp['ferramentaNome']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($emp['data_emprestimo']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($emp['data_devolucao']); ?></td>
                  <?php if ($nivel === 'admin'): ?>
                    <td class="text-center">
                      <a class="btn btn-warning" href="?idEmprestimo=<?php echo (int)$emp['id']; ?>">Editar</a>
                      &nbsp;|&nbsp;
                      <a class="btn btn-danger" onclick="return confirm('Deseja realmente excluir?');"
                        href="excluir.emprestimo.php?idEmprestimo=<?php echo (int)$emp['id']; ?>">
                        Excluir
                      </a>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($emprestimos)): ?>
                <tr>
                  <td colspan="<?php echo ($nivel === 'admin') ? 6 : 5; ?>" class="text-center text-muted">
                    Nenhum empréstimo cadastrado.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tabela de Reservas -->
    <div class="card">
      <div class="card-body">
        <h3>Lista de Reservas</h3>
        <div class="table-responsive">
          <table class="table table-bordered table-sm align-middle">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Usuário</th>
                <th class="text-center">Ferramenta</th>
                <th class="text-center">Data da Reserva</th>
                <th class="text-center">Status</th>
                <?php if ($nivel === 'admin'): ?>
                  <th class="text-center">Ações</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservas as $r): ?>
                <tr>
                  <td class="text-center"><?php echo (int)$r['id']; ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($r['usuarioNome']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($r['ferramentaNome']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($r['data_reserva']); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($r['status']); ?></td>
                  <?php if ($nivel === 'admin'): ?>
                    <td class="text-center">
                      <!-- Ajuste as URLs/rotas conforme seus handlers -->
                      <a class="btn btn-success btn-sm"
                        href="liberar.reserva.php?idReserva=<?php echo (int)$r['id']; ?>"
                        onclick="return confirm('Liberar esta reserva em um empréstimo?');">
                        Liberar
                      </a>
                      <a class="btn btn-warning" href="editar.reserva.php?idReserva=<?php echo (int)$r['id']; ?>">Editar</a>
                      &nbsp;|&nbsp;
                      <a class="btn btn-danger" onclick="return confirm('Deseja realmente excluir esta reserva?');"
                        href="excluir.reserva.php?idReserva=<?php echo (int)$r['id']; ?>">
                        Excluir
                      </a>

                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($reservas)): ?>
                <tr>
                  <td colspan="<?php echo ($nivel === 'admin') ? 6 : 5; ?>" class="text-center text-muted">
                    Nenhuma reserva cadastrada.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="sidebars.js"></script>
</body>

</html>