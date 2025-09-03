<?php
include_once("includes/classes/ferramenta.php");   // sua classe Ferramenta (usa $bd->query etc.)
include_once("includes/classes/Emprestimo.php");   // para checar empréstimo ativo
include_once("includes/classes/Reserva.php");      // para checar reserva ativa
// include_once("includes/menu.php");

// if (!isset($_SESSION['id_usuario'])) {
//   header("Location: login.php?msg=Você precisa estar logado!");
//   exit();
// }

$bd = new Database();
$ferramenta   = new Ferramenta($bd);
$ferramentaBD = new Ferramenta($bd);

$emprestimo = new Emprestimo($bd);
$reserva    = new Reserva($bd);

// opções fixas do ENUM
$statusOptions = [
  'disponivel'  => 'Disponível',
  'reservada'   => 'Reservada',
  'emprestada'  => 'Emprestada',
];

$msg = $_GET['msg'] ?? null;

/* ===== Carregar registro se edição ===== */
if (isset($_GET['idferramenta'])) {
  $idferramenta = (int)$_GET['idferramenta'];
  $dados = $ferramentaBD->buscaID($idferramenta);

  $nome      = $dados['nome'] ?? '';
  $descricao = $dados['descricao'] ?? '';
  $status    = $dados['status'] ?? 'disponivel';
  $estado    = $dados['estado'] ?? '';
} else {
  $idferramenta = 0;
  $nome = $descricao = $estado = '';
  $status = 'disponivel';
}

/* ===== Salvar ===== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $idferramenta = (int)($_POST['idferramenta'] ?? 0);
  $nome         = trim($_POST['nome'] ?? '');
  $descricao    = trim($_POST['descricao'] ?? '');
  $statusReq    = $_POST['status'] ?? 'disponivel';
  $estado       = trim($_POST['estado'] ?? '');

  // Normalização de status para manter coerência com o sistema:
  // Se existe empréstimo em aberto → 'emprestada'
  $temEmpAtivo = false;
  if ($idferramenta > 0) {
    // empréstimos em aberto: sem data_devolucao ou data_devolucao futura
    $rsEmp = $bd->query("
      SELECT 1 FROM emprestimo
       WHERE id_ferramenta = '{$idferramenta}'
         AND (data_devolucao IS NULL OR data_devolucao='' OR data_devolucao > CURDATE())
       LIMIT 1
    ");
    $temEmpAtivo = ($rsEmp && $rsEmp->num_rows > 0);
  }

  // Se não tem empréstimo em aberto, mas existe reserva ativa → 'reservada'
  $temReservaAtiva = $idferramenta > 0 ? $reserva->existeReservaAtivaFerramenta($idferramenta) : false;

  if ($temEmpAtivo) {
    $statusFinal = 'emprestada';
  } elseif ($temReservaAtiva) {
    $statusFinal = 'reservada';
  } else {
    // sem vínculos: usa o status solicitado, mas se vier 'emprestada' sem empréstimo, normalizo para 'disponivel'
    $statusFinal = ($statusReq === 'emprestada') ? 'disponivel' : $statusReq;
  }

  $data = [
    'idferramenta' => $idferramenta,
    'nome'         => $nome,
    'descricao'    => $descricao,
    'status'       => $statusFinal,
    'estado'       => $estado,
  ];

  if ($ferramenta->inserir($data)) {
    // mensagem mais clara
    $msg = "Ferramenta salva com status '{$statusFinal}'.";
    header("Location: ferramentas.php?msg=" . urlencode($msg));
  } else {
    header("Location: ferramentas.php?msg=" . urlencode("Erro ao salvar a ferramenta."));
  }
  exit();
}

/* ===== Listagem ===== */
$ferramentas = [];
$rs = $bd->query("SELECT id, nome, descricao, status, COALESCE(estado,'') AS estado FROM ferramenta ORDER BY nome");
if ($rs) {
  while ($row = $rs->fetch_assoc()) $ferramentas[] = $row;
}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ferramentas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="sidebars.css" rel="stylesheet" />
  <style>
    .badge-disponivel { background:#198754; }
    .badge-reservada  { background:#6c757d; }
    .badge-emprestada { background:#dc3545; }
  </style>
</head>
<body>

<?php include_once("includes/menu.php"); ?>

<div class="flex-grow-1 p-4">
  <h2>Cadastro e Gestão de Ferramentas</h2>

  <?php if ($msg): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <!-- Formulário -->
  <div class="card mb-4">
    <div class="card-body">
      <h3>Cadastro de Ferramentas</h3>
      <form action="" method="POST">
        <input type="hidden" name="idferramenta" value="<?php echo (int)$idferramenta; ?>">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome"
                   value="<?php echo htmlspecialchars($nome); ?>" required>
          </div>

          <div class="col-md-4">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao"
                   value="<?php echo htmlspecialchars($descricao); ?>">
          </div>

          <div class="col-md-2">
            <label for="estado" class="form-label">Estado de Conservação</label>
            <input type="text" class="form-control" id="estado" name="estado"
                   value="<?php echo htmlspecialchars($estado); ?>" placeholder="Ex.: Bom, Ótimo, Precisa reparo">
          </div>

          <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
              <?php foreach ($statusOptions as $val=>$label): ?>
                <option value="<?php echo $val; ?>" <?php echo ($status === $val ? 'selected' : ''); ?>>
                  <?php echo $label; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">
              O sistema ajusta automaticamente conforme empréstimos/reservas ativas.
            </div>
          </div>

          <div class="col-md-2">
            <button type="submit" class="btn btn-primary mt-4 w-100">Salvar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabela -->
  <div class="card">
    <div class="card-body">
      <h4 class="mb-3">Ferramentas cadastradas</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Estado</th>
              <th>Status</th>
              <th style="width: 180px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ferramentas as $f): ?>
              <tr>
                <td><?php echo (int)$f['id']; ?></td>
                <td><?php echo htmlspecialchars($f['nome']); ?></td>
                <td><?php echo htmlspecialchars($f['descricao']); ?></td>
                <td><?php echo htmlspecialchars($f['estado']); ?></td>
                <td>
                  <?php
                    $badge = $f['status']==='emprestada' ? 'badge-emprestada' :
                             ($f['status']==='reservada' ? 'badge-reservada' : 'badge-disponivel');
                  ?>
                  <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($f['status']); ?></span>
                </td>
                <td>
                  <a href="?idferramenta=<?php echo (int)$f['id']; ?>">Editar</a>
                  &nbsp;|&nbsp;
                  <a onclick="return confirm('Deseja realmente excluir?');"
                     href="excluir.ferramenta.php?idferramenta=<?php echo (int)$f['id']; ?>">
                    Excluir
                  </a>
                  <?php if ($f['status'] !== 'disponivel'): ?>
                    &nbsp;|&nbsp;
                    <a class="text-decoration-none"
                       href="setar.status.ferramenta.php?id=<?php echo (int)$f['id']; ?>&status=disponivel"
                       onclick="return confirm('Marcar como disponível? Isso só será efetivo se não houver empréstimo/reserva ativa.');">
                      Disponibilizar
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($ferramentas)): ?>
              <tr><td colspan="6" class="text-center text-muted">Nenhuma ferramenta cadastrada.</td></tr>
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
