<?php
include_once("includes/classes/ferramenta.php");
include_once("includes/classes/Reserva.php");

$bd = new Database();
$ferramenta = new Ferramenta($bd);
$reserva    = new Reserva($bd);

$id     = (int)($_GET['id'] ?? 0);
$status = $_GET['status'] ?? 'disponivel';

if ($id <= 0) {
  header("Location: ferramentas.php?msg=ID inválido.");
  exit();
}

// checa vínculos
$temEmp = $bd->query("
  SELECT e.id, u.nome AS usuarioNome, e.data_emprestimo, e.data_devolucao
  FROM emprestimo e
  JOIN usuario u ON u.id = e.id_usuario
 WHERE e.id_ferramenta = '{$id}'
   AND e.data_devolucao >= CURDATE()
 ORDER BY e.data_emprestimo DESC
 LIMIT 1;
");
$temEmp = ($temEmp && $temEmp->num_rows > 0);

$temRes = $reserva->existeReservaAtivaFerramenta($id);

// normaliza
if ($temEmp)      $statusFinal = 'emprestada';
elseif ($temRes)  $statusFinal = 'reservada';
else              $statusFinal = $status;

// atualiza
$bd->query("UPDATE ferramenta SET status='{$statusFinal}' WHERE id='{$id}'");
header("Location: ferramentas.php?msg=Status atualizado para '{$statusFinal}'.");
exit();
