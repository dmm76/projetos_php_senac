<?php
// relatorio_atendimentos.php
// Página de Relatório de Atendimentos (médico)
// Requisitos atendidos:
// - Filtros por intervalo de datas, status, paciente (nome/cpf) e médico (nome)
// - Resumo com contagens por status e métricas (total, média de duração)
// - Tabela com os resultados
// - Exportação para CSV e botão de impressão
// - Layout Bootstrap 5 (mesmo estilo do seu index)

include_once("includes/conexao.php");

$bd = new Database();
$con = $bd->conexao; // mysqli

// --------- Defaults dos filtros ---------
$hoje = date('Y-m-d');
$dataInicio = isset($_GET['dataInicio']) && $_GET['dataInicio'] !== '' ? $_GET['dataInicio'] : $hoje;
$dataFim     = isset($_GET['dataFim']) && $_GET['dataFim'] !== '' ? $_GET['dataFim'] : $hoje;
$status      = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : 'todos'; // todos | agendado | recepcionado | triado | finalizado
$nomePaciente = isset($_GET['nomePaciente']) ? trim($_GET['nomePaciente']) : '';
$cpf          = isset($_GET['cpf']) ? trim($_GET['cpf']) : '';
$nomeMedico   = isset($_GET['nomeMedico']) ? trim($_GET['nomeMedico']) : '';

// --------- Monta condições e params (prepared) ---------
$conds = [];
$params = [];
$types  = '';

// Janela de data (inclusive)
$conds[] = "a.data >= ?";   $params[] = $dataInicio; $types .= 's';
$conds[] = "a.data <= ?";   $params[] = $dataFim;     $types .= 's';

if ($status !== 'todos') {
  $conds[] = "a.status = ?"; $params[] = $status; $types .= 's';
}

if ($nomePaciente !== '') {
  $conds[] = "p.nome LIKE ?"; $params[] = "%$nomePaciente%"; $types .= 's';
}

if ($cpf !== '') {
  $conds[] = "p.cpf LIKE ?"; $params[] = "%$cpf%"; $types .= 's';
}

if ($nomeMedico !== '') {
  $conds[] = "u.nome LIKE ?"; $params[] = "%$nomeMedico%"; $types .= 's';
}

$where = count($conds) ? ('WHERE ' . implode(' AND ', $conds)) : '';

// --------- Query principal (lista) ---------
$sqlLista = "SELECT 
    a.idAtendimento,
    a.data,
    a.hora,
    a.dataInicio,
    a.dataFim,
    a.status,
    a.obsTriagem,
    a.obsAtendimento,
    p.nome  AS nomePaciente,
    p.cpf   AS cpfPaciente,
    u.nome  AS nomeMedico
  FROM atendimentos a
    JOIN pacientes p ON p.idPaciente = a.idPaciente
    JOIN usuarios  u ON u.idUsuario  = a.idMedico
  $where
  ORDER BY a.data ASC, a.hora ASC";

$stmtLista = $con->prepare($sqlLista);
if (!$stmtLista) { die("Erro ao preparar SQL lista: " . $con->error); }
if ($types !== '') $stmtLista->bind_param($types, ...$params);
$stmtLista->execute();
$resLista = $stmtLista->get_result();
$linhas   = $resLista->fetch_all(MYSQLI_ASSOC);

// --------- Agregações: contagens por status ---------
$sqlAgg = "SELECT a.status, COUNT(*) as total
  FROM atendimentos a
    JOIN pacientes p ON p.idPaciente = a.idPaciente
    JOIN usuarios  u ON u.idUsuario  = a.idMedico
  $where
  GROUP BY a.status";

$stmtAgg = $con->prepare($sqlAgg);
if (!$stmtAgg) { die("Erro ao preparar SQL agregação: " . $con->error); }
if ($types !== '') $stmtAgg->bind_param($types, ...$params);
$stmtAgg->execute();
$resAgg = $stmtAgg->get_result();
$contagens = [
  'agendado'     => 0,
  'recepcionado' => 0,
  'triado'       => 0,
  'finalizado'   => 0,
];
while ($row = $resAgg->fetch_assoc()) {
  $contagens[$row['status']] = (int)$row['total'];
}

$totalRegistros = array_sum($contagens);

// --------- Métrica: duração média (em minutos) ---------
$sqlAvg = "SELECT AVG(TIMESTAMPDIFF(MINUTE, a.dataInicio, a.dataFim)) as mediaMinutos
  FROM atendimentos a
    JOIN pacientes p ON p.idPaciente = a.idPaciente
    JOIN usuarios  u ON u.idUsuario  = a.idMedico
  $where
  AND a.dataInicio IS NOT NULL AND a.dataFim IS NOT NULL";

$stmtAvg = $con->prepare($sqlAvg);
if (!$stmtAvg) { die("Erro ao preparar SQL média: " . $con->error); }
if ($types !== '') $stmtAvg->bind_param($types, ...$params);
$stmtAvg->execute();
$resAvg = $stmtAvg->get_result();
$mediaMinutos = 0;
if ($row = $resAvg->fetch_assoc()) {
  $mediaMinutos = $row['mediaMinutos'] !== null ? round((float)$row['mediaMinutos'], 1) : 0;
}

// --------- Exportação CSV ---------
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  $arquivo = 'relatorio_atendimentos_' . $dataInicio . '_a_' . $dataFim . '.csv';
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=' . $arquivo);

  $out = fopen('php://output', 'w');
  // Cabeçalho
  fputcsv($out, [
    'ID', 'Data', 'Hora', 'Paciente', 'CPF', 'Médico', 'Status', 'Início', 'Fim', 'Duração (min)'
  ], ';');

  foreach ($linhas as $l) {
    $duracao = '';
    if (!empty($l['dataInicio']) && !empty($l['dataFim'])) {
      $q = $con->query("SELECT TIMESTAMPDIFF(MINUTE, '" . $con->real_escape_string($l['dataInicio']) . "', '" . $con->real_escape_string($l['dataFim']) . "') AS dif");
      $d = $q ? $q->fetch_assoc() : null;
      $duracao = $d ? (int)$d['dif'] : '';
    }

    fputcsv($out, [
      $l['idAtendimento'],
      $l['data'],
      $l['hora'],
      $l['nomePaciente'],
      $l['cpfPaciente'],
      $l['nomeMedico'],
      $l['status'],
      $l['dataInicio'],
      $l['dataFim'],
      $duracao,
    ], ';');
  }
  fclose($out);
  exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Relatório de Atendimentos</title>
  <style>
    @media print {
      .no-print { display: none !important; }
      .table th, .table td { border-color: #000 !important; }
    }
  </style>
</head>
<body>
<?php include_once("includes/menu.php"); ?>
<div class="container mt-3">
  <div class="card">
    <div class="card-body">
      <h3>Relatório de Atendimentos</h3>
      <form class="row g-3" method="GET">
        <div class="col-md-2">
          <label class="form-label">Data Início</label>
          <input type="date" name="dataInicio" value="<?php echo htmlspecialchars($dataInicio); ?>" class="form-control">
        </div>
        <div class="col-md-2">
          <label class="form-label">Data Fim</label>
          <input type="date" name="dataFim" value="<?php echo htmlspecialchars($dataFim); ?>" class="form-control">
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <option value="todos" <?php echo $status==='todos'?'selected':''; ?>>Todos</option>
            <option value="agendado" <?php echo $status==='agendado'?'selected':''; ?>>Agendado</option>
            <option value="recepcionado" <?php echo $status==='recepcionado'?'selected':''; ?>>Recepcionado</option>
            <option value="triado" <?php echo $status==='triado'?'selected':''; ?>>Triado</option>
            <option value="finalizado" <?php echo $status==='finalizado'?'selected':''; ?>>Finalizado</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Paciente (nome)</label>
          <input type="text" name="nomePaciente" value="<?php echo htmlspecialchars($nomePaciente); ?>" class="form-control" placeholder="Nome do paciente">
        </div>
        <div class="col-md-3">
          <label class="form-label">CPF</label>
          <input type="text" name="cpf" id="cpf" value="<?php echo htmlspecialchars($cpf); ?>" class="form-control" placeholder="000.000.000-00">
        </div>
        <div class="col-md-4">
          <label class="form-label">Médico (nome)</label>
          <input type="text" name="nomeMedico" value="<?php echo htmlspecialchars($nomeMedico); ?>" class="form-control" placeholder="Nome do médico">
        </div>
        <div class="col-md-8 d-flex align-items-end gap-2">
          <button class="btn btn-primary">Aplicar filtros</button>
          <a class="btn btn-outline-secondary" href="?">Limpar</a>
          <a class="btn btn-success" href="?<?php echo http_build_query(array_merge($_GET, ['export' => 'csv'])); ?>">Exportar CSV</a>
          <button type="button" class="btn btn-outline-dark" onclick="window.print()">Imprimir</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Cards de resumo -->
  <div class="row mt-3">
    <div class="col-md-3">
      <div class="card text-bg-light">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Total</span><strong><?php echo (int)$totalRegistros; ?></strong></div>
          <small class="text-muted">Período: <?php echo htmlspecialchars($dataInicio); ?> a <?php echo htmlspecialchars($dataFim); ?></small>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card border-warning">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Agendado</span><strong><?php echo (int)$contagens['agendado']; ?></strong></div>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card border-secondary">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Recepcionado</span><strong><?php echo (int)$contagens['recepcionado']; ?></strong></div>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <div class="card border-success">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Triado</span><strong><?php echo (int)$contagens['triado']; ?></strong></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-primary">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Finalizado</span><strong><?php echo (int)$contagens['finalizado']; ?></strong></div>
          <small class="text-muted">Duração média: <?php echo (int)$mediaMinutos; ?> min</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabela -->
  <div class="card mt-3">
    <div class="card-body">
      <h5 class="card-title">Resultados</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th class="text-center">ID</th>
              <th>Paciente</th>
              <th class="text-center">CPF</th>
              <th>Médico</th>
              <th class="text-center">Data</th>
              <th class="text-center">Hora</th>
              <th class="text-center">Status</th>
              <th class="text-center">Início</th>
              <th class="text-center">Fim</th>
              <th class="text-center">Duração (min)</th>
              <th>Obs (triagem/atendimento)</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!count($linhas)) : ?>
            <tr><td colspan="11" class="text-center text-muted">Nenhum registro encontrado para os filtros informados.</td></tr>
          <?php else: ?>
            <?php foreach ($linhas as $l): ?>
              <?php
                $duracao = '';
                if (!empty($l['dataInicio']) && !empty($l['dataFim'])) {
                  $q = $con->query("SELECT TIMESTAMPDIFF(MINUTE, '" . $con->real_escape_string($l['dataInicio']) . "', '" . $con->real_escape_string($l['dataFim']) . "') AS dif");
                  $d = $q ? $q->fetch_assoc() : null;
                  $duracao = $d ? (int)$d['dif'] : '';
                }
                // Badge de status
                $badge = match($l['status']) {
                  'agendado'     => '<span class="badge text-bg-warning">Agendado</span>',
                  'recepcionado' => '<span class="badge text-bg-secondary">Recepcionado</span>',
                  'triado'       => '<span class="badge text-bg-success">Triado</span>',
                  'finalizado'   => '<span class="badge text-bg-primary">Finalizado</span>',
                  default        => '<span class="badge text-bg-light">-</span>'
                };
              ?>
              <tr>
                <td class="text-center"><?php echo (int)$l['idAtendimento']; ?></td>
                <td><?php echo htmlspecialchars($l['nomePaciente']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($l['cpfPaciente']); ?></td>
                <td><?php echo htmlspecialchars($l['nomeMedico']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($l['data']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($l['hora']); ?></td>
                <td class="text-center"><?php echo $badge; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($l['dataInicio']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($l['dataFim']); ?></td>
                <td class="text-center"><?php echo $duracao !== '' ? (int)$duracao : '-'; ?></td>
                <td>
                  <?php
                    $obs = [];
                    if (!empty($l['obsTriagem'])) $obs[] = 'Triagem: ' . $l['obsTriagem'];
                    if (!empty($l['obsAtendimento'])) $obs[] = 'Atend.: ' . $l['obsAtendimento'];
                    echo htmlspecialchars(implode(' | ', $obs));
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/imask"></script>
<script>
  const elemento = document.getElementById('cpf');
  if (elemento) {
    IMask(elemento, { mask: '000.000.000-00' });
  }
</script>
</body>
</html>
