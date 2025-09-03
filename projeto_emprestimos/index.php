<?php
// index.php — Dashboard do Condomínio Sol da Meia-Noite (sem includes/bootstrap.php)
include_once("includes/conexao.php");
$bd = new Database();

/* Helpers rápidos */
function getCount($bd, $sql) {
  $res = $bd->query($sql);
  if ($res && $row = $res->fetch_assoc()) {
    return (int)array_values($row)[0];
  }
  return 0;
}
$isLogged = isset($_SESSION['id_usuario']);
$nomeUser = $_SESSION['nome_usuario'] ?? ($_SESSION['email_usuario'] ?? 'Visitante');

/* ===== Métricas gerais ===== */
$totalFerr = getCount($bd, "SELECT COUNT(*) FROM ferramenta");
$dispFerr  = getCount($bd, "SELECT COUNT(*) FROM ferramenta WHERE status='disponivel'");
$resvFerr  = getCount($bd, "SELECT COUNT(*) FROM ferramenta WHERE status='reservada'");
$empFerr   = getCount($bd, "SELECT COUNT(*) FROM ferramenta WHERE status='emprestada'");

/* ===== Métricas do usuário ===== */
$minhasReservas = 0;
$meusAbertos    = 0;
if ($isLogged) {
  $uid = (int)$_SESSION['id_usuario'];
  $minhasReservas = getCount($bd, "SELECT COUNT(*) FROM reserva WHERE id_usuario='{$uid}' AND status='ativa'");
  $meusAbertos    = getCount($bd, "
    SELECT COUNT(*)
      FROM emprestimo
     WHERE id_usuario='{$uid}'
       AND (data_devolucao IS NULL OR data_devolucao='' OR data_devolucao > CURDATE())
  ");
}

/* ===== Últimos empréstimos ===== */
$ultimos = [];
$sqlUlt = "
  SELECT e.id, e.data_emprestimo, e.data_devolucao,
         f.nome AS ferramentaNome,
         u.nome AS usuarioNome, u.apartamento
    FROM emprestimo e
    JOIN ferramenta f ON f.id = e.id_ferramenta
    JOIN usuario   u ON u.id = e.id_usuario
ORDER BY e.data_emprestimo DESC
   LIMIT 8";
$resUlt = $bd->query($sqlUlt);
if ($resUlt) {
  while ($row = $resUlt->fetch_assoc()) $ultimos[] = $row;
}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Condomínio Sol da Meia-Noite · Empréstimos</title>

  <!-- Pode manter Bootstrap via CDN (não tem relação com o arquivo bootstrap.php) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f6f7fb; }
    .card-stat { border:0; box-shadow: 0 6px 16px rgba(0,0,0,.06); border-radius: 12px; }
    .brand-title { letter-spacing:.3px; }
    .subtle { color:#6c757d; }
    .table thead th { white-space: nowrap; }
  </style>
</head>
<body>

<?php include_once __DIR__ . "/includes/menu.php"; ?>

<!-- CONTEÚDO PRINCIPAL -->
<div class="flex-grow-1 p-4" style="width:100%">
  <div class="container-fluid">
    <div class="mb-4">
      <h2 class="brand-title mb-1">Condomínio Sol da Meia-Noite</h2>
      <div class="subtle">Página de empréstimos · <?php echo date('d/m/Y'); ?></div>
    </div>

    <!-- SAUDAÇÃO / CTA -->
    <div class="mb-4">
      <?php if ($isLogged): ?>
        <div class="alert alert-light border d-flex justify-content-between align-items-center">
          <div>
            Olá, <strong><?php echo htmlspecialchars($nomeUser); ?></strong>! Bem-vindo ao painel de empréstimos.
          </div>
          <div class="d-flex gap-2">
            <a href="reservas.php" class="btn btn-primary">Fazer reserva</a>
            <a href="emprestimos.php" class="btn btn-outline-primary">Ver empréstimos</a>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
          <div>Você não está logado. Faça login para reservar e emprestar ferramentas.</div>
          <a href="login.php" class="btn btn-dark">Login</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- MÉTRICAS -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card card-stat">
          <div class="card-body">
            <div class="subtle">Ferramentas</div>
            <div class="display-6 fw-semibold"><?php echo $totalFerr; ?></div>
            <div class="small subtle">Total cadastradas</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card card-stat">
          <div class="card-body">
            <div class="subtle">Disponíveis</div>
            <div class="display-6 fw-semibold"><?php echo $dispFerr; ?></div>
            <div class="small text-success">Prontas para uso</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card card-stat">
          <div class="card-body">
            <div class="subtle">Reservadas</div>
            <div class="display-6 fw-semibold"><?php echo $resvFerr; ?></div>
            <div class="small text-secondary">Aguardando retirada</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card card-stat">
          <div class="card-body">
            <div class="subtle">Emprestadas</div>
            <div class="display-6 fw-semibold"><?php echo $empFerr; ?></div>
            <div class="small text-danger">Em uso</div>
          </div>
        </div>
      </div>
    </div>

    <!-- MÉTRICAS DO USUÁRIO -->
    <?php if ($isLogged): ?>
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
          <div class="card card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <div class="subtle">Minhas reservas ativas</div>
                <div class="h2 m-0"><?php echo $minhasReservas; ?></div>
              </div>
              <a href="reservas.php" class="btn btn-sm btn-primary">Ver/Reservar</a>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card card-stat">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <div class="subtle">Meus empréstimos em aberto</div>
                <div class="h2 m-0"><?php echo $meusAbertos; ?></div>
              </div>
              <a href="emprestimos.php" class="btn btn-sm btn-outline-primary">Acompanhar</a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- ÚLTIMOS EMPRÉSTIMOS -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="m-0">Últimos empréstimos</h5>
          <a href="emprestimos.php" class="btn btn-sm btn-outline-secondary">Ver todos</a>
        </div>

        <?php if (empty($ultimos)): ?>
          <div class="alert alert-light border">Ainda não há empréstimos registrados.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm table-striped align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ferramenta</th>
                  <th>Usuário</th>
                  <th>Ap.</th>
                  <th>Data Empréstimo</th>
                  <th>Devolução</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ultimos as $e):
                  $aberto = (empty($e['data_devolucao']) || $e['data_devolucao'] > date('Y-m-d'));
                ?>
                  <tr>
                    <td><?php echo (int)$e['id']; ?></td>
                    <td><?php echo htmlspecialchars($e['ferramentaNome']); ?></td>
                    <td><?php echo htmlspecialchars($e['usuarioNome']); ?></td>
                    <td><?php echo htmlspecialchars($e['apartamento']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($e['data_emprestimo']))); ?></td>
                    <td><?php echo !empty($e['data_devolucao']) ? htmlspecialchars(date('d/m/Y', strtotime($e['data_devolucao']))) : '—'; ?></td>
                    <td>
                      <span class="badge <?php echo $aberto ? 'text-bg-warning' : 'text-bg-success'; ?>">
                        <?php echo $aberto ? 'Aberto' : 'Finalizado'; ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="text-center small subtle mt-4">
      © <?php echo date('Y'); ?> Condomínio Sol da Meia-Noite · Sistema de Empréstimos
    </div>
  </div>
</div>

<!-- Bootstrap JS (se quiser componentes como dropdown no menu) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
