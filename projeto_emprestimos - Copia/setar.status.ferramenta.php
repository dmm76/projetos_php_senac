<?php
include_once("includes/classes/ferramenta.php");
include_once("includes/classes/Reserva.php");

$bd = new Database();
$ferramenta = new Ferramenta($bd);
$reserva    = new Reserva($bd);

$id     = (int)($_GET['id'] ?? 0);
$status = $_GET['status'] ?? 'disponivel';

if ($id <= 0) { header("Location: ferramentas.php?msg=ID inválido."); exit(); }

// checa vínculos
$temEmp = $bd->query("
  SELECT 1 FROM emprestimo
   WHERE id_ferramenta='{$id}'
     AND (data_devolucao IS NULL OR data_devolucao='' OR data_devolucao > CURDATE())
   LIMIT 1
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
