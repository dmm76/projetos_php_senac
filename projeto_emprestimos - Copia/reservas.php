<?php
include_once "includes/classes/Reserva.php";
require_once 'includes/auth.php';
require_once 'includes/acl.php';

//requireLogin();
// requireRole(['admin']); // só admin acessa

// BLOQUEIA NÃO LOGADO (descomente quando estiver com login ativo)
if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.php?msg=Você precisa estar logado!");
  exit();
}

$bd = new Database();
$reserva = new Reserva($bd);

$MSG = $_GET['msg'] ?? null;

/* ==== AÇÕES ==== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'] ?? '';

  if ($acao === 'criar') {
    $id_ferramenta = (int)$_POST['id_ferramenta'];
    $id_usuario    = (int)$_SESSION['id_usuario'];

    // usa o molde da classe: inserir(array $data)
    $ok = $reserva->inserir([
      'idReserva'     => 0,
      'id_ferramenta' => $id_ferramenta,
      'id_usuario'    => $id_usuario,
      'status'        => 'ativa'
    ]);

    if ($ok) {
      // Se a ferramenta estava 'disponivel', vira 'reservada'
      $bd->query("UPDATE ferramenta SET status='reservada' WHERE id='{$id_ferramenta}' AND status='disponivel'");
      header("Location: reservas.php?msg=Reserva registrada!");
    } else {
      header("Location: reservas.php?msg=Você já possui uma reserva ativa para esta ferramenta.");
    }
    exit();
  }

  if ($acao === 'cancelar') {
    $id_reserva    = (int)$_POST['id_reserva'];
    $id_ferramenta = (int)$_POST['id_ferramenta'];
    $id_usuario    = (int)$_SESSION['id_usuario'];

    // classe no molde possui cancelar($idReserva, $id_usuario=null)
    $ok = $reserva->cancelar($id_reserva, $id_usuario);

    if ($ok) {
      // Se não há mais reservas ativas p/ a ferramenta e ela não está emprestada, volta p/ 'disponivel'
      $temFila = $reserva->existeReservaAtivaFerramenta($id_ferramenta);
      if (!$temFila) {
        $bd->query("
          UPDATE ferramenta
             SET status='disponivel'
           WHERE id='{$id_ferramenta}'
             AND status='reservada'
        ");
      }
      header("Location: reservas.php?msg=Reserva cancelada.");
    } else {
      header("Location: reservas.php?msg=Não foi possível cancelar a reserva.");
    }
    exit();
  }
}

/* ==== DADOS PARA A PÁGINA ==== */

// ferramentas (usando mysqli + fetch_assoc)
$ferramentas = [];
$resFerr = $bd->query("SELECT id, nome, status, COALESCE(descricao,'') AS descricao FROM ferramenta ORDER BY nome");
if ($resFerr) {
  while ($row = $resFerr->fetch_assoc()) {
    $ferramentas[] = $row;
  }
}

// minhas reservas (classe no molde)
$minhasReservas = $reserva->listarPorUsuario((int)$_SESSION['id_usuario']);

// mapa: quais ferramentas já tenho reserva ativa (para desabilitar botão)
$jaTenho = [];
foreach ($minhasReservas as $r) {
  if ($r['status'] === 'ativa') {
    $jaTenho[(int)$r['id_ferramenta']] = true;
  }
}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reservas de Ferramentas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .badge-disponivel { background:#198754; }
    .badge-reservada  { background:#6c757d; }
    .badge-emprestada { background:#dc3545; }
    .card-tool { min-height: 100%; }
  </style>
</head>
<body>

<?php include_once "includes/menu.php"; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0">Reservas</h2>
    <span class="text-muted">Olá, <strong><?php echo htmlspecialchars($_SESSION['nome_usuario'] ?? 'Usuário'); ?></strong></span>
  </div>

  <?php if ($MSG): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($MSG); ?></div>
  <?php endif; ?>

  <!-- GRID DE FERRAMENTAS -->
  <div class="row g-3">
    <?php if (empty($ferramentas)): ?>
      <div class="col-12">
        <div class="alert alert-warning">Nenhuma ferramenta cadastrada.</div>
      </div>
    <?php else: ?>
      <?php foreach ($ferramentas as $f):
        $idF = (int)$f['id'];
        $status = $f['status']; // disponivel, reservada, emprestada
        $podeReservar = !isset($jaTenho[$idF]); // não permite duplicar reserva do mesmo usuário
      ?>
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card card-tool shadow-sm">
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0"><?php echo htmlspecialchars($f['nome']); ?></h5>
                <span class="badge <?php
                  echo $status === 'disponivel' ? 'badge-disponivel' :
                       ($status === 'emprestada' ? 'badge-emprestada' : 'badge-reservada');
                ?>"><?php echo htmlspecialchars($status); ?></span>
              </div>
              <p class="text-muted flex-grow-1 mb-3"><?php echo nl2br(htmlspecialchars($f['descricao'] ?: '—')); ?></p>

              <form method="post" class="mt-auto">
                <input type="hidden" name="acao" value="criar">
                <input type="hidden" name="id_ferramenta" value="<?php echo $idF; ?>">
                <button class="btn btn-primary w-100"
                        <?php echo $podeReservar ? '' : 'disabled'; ?>
                        title="<?php echo $podeReservar ? '' : 'Você já tem uma reserva ativa desta ferramenta'; ?>">
                  Reservar
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- MINHAS RESERVAS -->
  <div class="mt-5">
    <h4>Minhas Reservas</h4>
    <?php if (empty($minhasReservas)): ?>
      <div class="alert alert-secondary">Você não possui reservas.</div>
    <?php else: ?>
      <table class="table table-sm table-bordered align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Ferramenta</th>
            <th>Status</th>
            <th>Data</th>
            <th class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($minhasReservas as $r): ?>
            <tr>
              <td><?php echo (int)$r['id']; ?></td>
              <td><?php echo htmlspecialchars($r['ferramentaNome']); ?></td>
              <td><?php echo htmlspecialchars($r['status']); ?></td>
              <td><?php echo htmlspecialchars($r['data_reserva']); ?></td>
              <td class="text-center">
                <?php if ($r['status'] === 'ativa'): ?>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="acao" value="cancelar">
                    <input type="hidden" name="id_reserva" value="<?php echo (int)$r['id']; ?>">
                    <input type="hidden" name="id_ferramenta" value="<?php echo (int)$r['id_ferramenta']; ?>">
                    <button class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Cancelar esta reserva?');">
                      Cancelar
                    </button>
                  </form>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
