<?php
  include_once("includes/classes/Emprestimo.php");
  include_once("includes/classes/Reserva.php");
  // include_once("includes/menu.php");

  // if (!isset($_SESSION['id_usuario'])) {
  //   header("Location: login.php?msg=Você precisa estar logado!");
  //   exit();
  // }
  $bd = new Database();
  $emprestimo   = new Emprestimo($bd); // $bd vem de dentro do include Emprestimo.php (seu padrão)
  $emprestimoBD = new Emprestimo($bd);
  $reserva      = new Reserva($bd);

  $usuarios    = $emprestimo->listarUsuarios();
  $ferramentas = $emprestimo->listarFerramentas();

  if (isset($_GET['idEmprestimo'])) {
      $idEmprestimo    = (int)$_GET['idEmprestimo'];
      $dados           = $emprestimoBD->buscaID($idEmprestimo);
      $id_ferramenta   = (int)$dados['id_ferramenta'];
      $id_usuario      = (int)$dados['id_usuario'];
      $data_emprestimo = $dados['data_emprestimo'];
      $data_devolucao  = $dados['data_devolucao'];
  } else {
      $idEmprestimo    = 0;
      $id_ferramenta   = 0;
      $id_usuario      = 0;
      $data_emprestimo = "";
      $data_devolucao  = "";
  }

  /* ========= AÇÕES ========= */
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'salvar';

    // 1) Salvar/atualizar empréstimo (seu fluxo original)
    if ($acao === 'salvar') {
      $data = [
        'idEmprestimo'    => $_POST['idEmprestimo'],
        'id_ferramenta'   => $_POST['id_ferramenta'],
        'id_usuario'      => $_POST['id_usuario'],
        'data_emprestimo' => $_POST['data_emprestimo'],
        // 'senha'         => $_POST['senha'], // não existe no form
        'data_devolucao'  => $_POST['data_devolucao'],
      ];

      if ($emprestimo->inserir($data)) {
        // Se criou um novo empréstimo em aberto, marque ferramenta como emprestada
        if ((int)$data['idEmprestimo'] === 0 && (empty($data['data_devolucao']) || $data['data_devolucao'] > date('Y-m-d'))) {
          $bd->query("UPDATE ferramenta SET status='emprestada' WHERE id='{$data['id_ferramenta']}'");
        }
        header("Location: emprestimos.php?msg=Empréstimo salvo!");
      } else {
        header("Location: emprestimos.php?msg=Erro ao salvar o empréstimo.");
      }
      exit();
    }

    // 2) Liberar reserva (cria empréstimo p/ o 1º da fila daquela ferramenta)
    if ($acao === 'liberar_reserva') {
      $id_reserva    = (int)$_POST['id_reserva'];
      $id_ferramenta = (int)$_POST['id_ferramenta'];

      $r = $reserva->buscaID($id_reserva);
      if (!$r || $r['status'] !== 'ativa') {
        header("Location: emprestimos.php?msg=Reserva inválida ou não ativa.");
        exit();
      }

      $dadosEmp = [
        'idEmprestimo'    => 0,
        'id_ferramenta'   => $id_ferramenta,
        'id_usuario'      => (int)$r['id_usuario'],
        'data_emprestimo' => date('Y-m-d'),
        'data_devolucao'  => ''
      ];

      if ($emprestimo->inserir($dadosEmp)) {
        $reserva->marcarAtendida($id_reserva);
        $bd->query("UPDATE ferramenta SET status='emprestada' WHERE id='{$id_ferramenta}'");
        header("Location: emprestimos.php?msg=Reserva liberada e empréstimo criado!");
      } else {
        header("Location: emprestimos.php?msg=Erro ao criar empréstimo a partir da reserva.");
      }
      exit();
    }

    // 3) Finalizar empréstimo (devolver hoje; se houver fila, já transfere ao próximo)
    if ($acao === 'finalizar') {
      $idEmp = (int)$_POST['idEmprestimo'];

      // devolução hoje
      $bd->query("UPDATE emprestimo SET data_devolucao = CURDATE() WHERE id='{$idEmp}'");

      // pega ferramenta deste empréstimo
      $emp = $emprestimoBD->buscaID($idEmp);
      $idFerr = (int)$emp['id_ferramenta'];

      // próxima reserva ativa (fila)
      $prox = $reserva->proximaAtiva($idFerr);
      if ($prox) {
        $dadosEmp = [
          'idEmprestimo'    => 0,
          'id_ferramenta'   => $idFerr,
          'id_usuario'      => (int)$prox['id_usuario'],
          'data_emprestimo' => date('Y-m-d'),
          'data_devolucao'  => ''
        ];
        if ($emprestimo->inserir($dadosEmp)) {
          $reserva->marcarAtendida((int)$prox['id']);
          $bd->query("UPDATE ferramenta SET status='emprestada' WHERE id='{$idFerr}'");
        }
      } else {
        // sem fila → disponível
        $bd->query("UPDATE ferramenta SET status='disponivel' WHERE id='{$idFerr}'");
      }

      header("Location: emprestimos.php?msg=Empréstimo finalizado.");
      exit();
    }
  }

  /* ========= LISTAGENS ========= */
  $emprestimos = $emprestimo->listar();

  // reservas ativas com nomes e status da ferramenta (ordenadas por ferramenta + data)
  $reservasAtivas = [];
  $rs = $bd->query("
    SELECT r.id, r.id_ferramenta, r.id_usuario, r.data_reserva, r.status,
           f.nome AS ferramentaNome, f.status AS statusFerramenta,
           u.nome AS usuarioNome, u.apartamento
      FROM reserva r
      JOIN ferramenta f ON f.id = r.id_ferramenta
      JOIN usuario   u ON u.id = r.id_usuario
     WHERE r.status = 'ativa'
  ORDER BY f.nome ASC, r.data_reserva ASC
  ");
  if ($rs) {
    while ($row = $rs->fetch_assoc()) { $reservasAtivas[] = $row; }
  }
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Empréstimos de Ferramentas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="sidebars.css" rel="stylesheet" />
  </head>
  <body>

    <?php include_once("includes/menu.php"); ?>

    <div class="flex-grow-1 p-4">
      <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div>
      <?php endif; ?>

      <h2>Empréstimos de Ferramentas</h2>

      <!-- FORMULÁRIO -->
      <div class="card mb-4">
        <div class="card-body">
          <h3>Cadastro de Empréstimos</h3>
          <form action="" method="POST">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="idEmprestimo" value="<?php echo $idEmprestimo ?>">
            <div class="row g-3">
              <div class="col-md-3">
                <label for="id_usuario" class="form-label">Usuário</label>
                <select name="id_usuario" id="id_usuario" class="form-select">
                  <option value="<?php echo $id_usuario ?>">Selecione um Usuário</option>
                  <?php foreach ($usuarios as $usuario):
                    $sel = ($id_usuario == $usuario['id']) ? 'selected' : '';
                    echo '<option '.$sel.' value="'.$usuario['id'].'">'.$usuario['nome'].'</option>';
                  endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="id_ferramenta" class="form-label">Ferramenta</label>
                <select name="id_ferramenta" id="id_ferramenta" class="form-select">
                  <option value="<?php echo $id_ferramenta ?>">Selecione uma Ferramenta</option>
                  <?php foreach ($ferramentas as $f):
                    $sel = ($id_ferramenta == $f['id']) ? 'selected' : '';
                    echo '<option '.$sel.' value="'.$f['id'].'">'.$f['nome'].' ('.$f['status'].')</option>';
                  endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label for="data_emprestimo" class="form-label">Data do empréstimo</label>
                <input type="date" class="form-control" id="data_emprestimo" value="<?php echo htmlspecialchars($data_emprestimo) ?>" name="data_emprestimo" required>
              </div>

              <div class="col-md-3">
                <label for="data_devolucao" class="form-label">Data de devolução</label>
                <input type="date" class="form-control" id="data_devolucao" value="<?php echo htmlspecialchars($data_devolucao) ?>" name="data_devolucao">
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4 w-100">Salvar</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- RESERVAS ATIVAS -->
      <div class="card mb-4">
        <div class="card-body">
          <h4 class="mb-3">Reservas ativas</h4>
          <?php if (empty($reservasAtivas)): ?>
            <div class="alert alert-light border">Não há reservas ativas.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-sm align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Ferramenta</th>
                    <th>Status Ferramenta</th>
                    <th>Usuário</th>
                    <th>Ap.</th>
                    <th>Data da Reserva</th>
                    <th>Posição</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $posPorFerr = [];
                  foreach ($reservasAtivas as $r):
                    $fid = (int)$r['id_ferramenta'];
                    if (!isset($posPorFerr[$fid])) $posPorFerr[$fid] = 1; else $posPorFerr[$fid]++;
                    $pos = $posPorFerr[$fid];
                  ?>
                  <tr>
                    <td><?php echo (int)$r['id']; ?></td>
                    <td><?php echo htmlspecialchars($r['ferramentaNome']); ?></td>
                    <td>
                      <span class="badge text-bg-<?php
                        echo $r['statusFerramenta']==='emprestada' ? 'danger' : ($r['statusFerramenta']==='reservada' ? 'secondary' : 'success');
                      ?>">
                        <?php echo htmlspecialchars($r['statusFerramenta']); ?>
                      </span>
                    </td>
                    <td><?php echo htmlspecialchars($r['usuarioNome']); ?></td>
                    <td><?php echo htmlspecialchars($r['apartamento']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($r['data_reserva']))); ?></td>
                    <td><?php echo $pos; ?></td>
                    <td>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="acao" value="liberar_reserva">
                        <input type="hidden" name="id_reserva" value="<?php echo (int)$r['id']; ?>">
                        <input type="hidden" name="id_ferramenta" value="<?php echo (int)$r['id_ferramenta']; ?>">
                        <button class="btn btn-sm btn-primary"
                                <?php echo $pos===1 ? '' : 'disabled title="Somente o primeiro da fila pode ser liberado"'; ?>>
                          Liberar
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- EMPRÉSTIMOS -->
      <div class="card">
        <div class="card-body">
          <h4 class="mb-3">Empréstimos</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Usuário</th>
                  <th>Ferramenta</th>
                  <th>Data Empréstimo</th>
                  <th>Data Devolução</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($emprestimos as $e): ?>
                  <tr>
                    <td><?php echo (int)$e['id']; ?></td>
                    <td><?php echo htmlspecialchars($e['usuarioNome']); ?></td>
                    <td><?php echo htmlspecialchars($e['ferramentaNome']); ?></td>
                    <td><?php echo htmlspecialchars($e['data_emprestimo']); ?></td>
                    <td><?php echo htmlspecialchars($e['data_devolucao'] ?: ''); ?></td>
                    <td>
                      <a href="?idEmprestimo=<?php echo (int)$e['id']; ?>">Editar</a>
                      &nbsp;|&nbsp;
                      <a onclick="return confirm('Deseja realmente excluir?');" href="excluir.emprestimo.php?idEmprestimo=<?php echo (int)$e['id']; ?>">Excluir</a>
                      <?php
                        $aberto = (empty($e['data_devolucao']) || $e['data_devolucao'] > date('Y-m-d'));
                        if ($aberto):
                      ?>
                        &nbsp;|&nbsp;
                        <form method="post" class="d-inline">
                          <input type="hidden" name="acao" value="finalizar">
                          <input type="hidden" name="idEmprestimo" value="<?php echo (int)$e['id']; ?>">
                          <button class="btn btn-link p-0">Finalizar</button>
                        </form>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($emprestimos)): ?>
                  <tr><td colspan="6" class="text-center text-muted">Nenhum empréstimo registrado.</td></tr>
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
