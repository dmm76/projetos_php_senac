<?php

	include_once("includes/classes/Atendimento.php");

	if (!isset($_SESSION['idUsuario'])) {
		header("Location: login.php?Você precisa estar logado!");
		exit();
	}

	$bd = new Database();
	$Atendimento = new Atendimento($bd);
	$status = 'agendado';

	if (isset($_GET['status'])) {
		$idAtendimento = $_GET['idAtendimento'];
		$status = $_GET['status'];

		if ($Atendimento->alterarStatus($status, $idAtendimento)) {
			header("Location: ?msg=Status marcado como $status");
		}
	}

	if (isset($_GET['dataInicio'])) {
		$dataInicio = $_GET['dataInicio'];
		$dataFim = $_GET['dataFim'];
		$nome = $_GET['nome'];
		$cpf = $_GET['cpf'];
		$status = $_GET['statusPesquisa'];
	} else {
		$dataInicio = date('Y-m-d');
		$dataFim = date('Y-m-d');
		$nome = '';
		$cpf = '';
	}

	$atendimentos = $Atendimento->listarAtendimentos($dataInicio, $dataFim, $nome, $cpf, $status);
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
	<?php include_once("includes/menu.php"); ?>

	<div class="container">
		<div class="card mt-3">
			<div class="card-body">
				<h3>Recepção</h3>
				<form action="" method="GET">
					<div class="row mb-3">
						<div class="col-md-3">
							<label>Status:</label>
							<select class="form-select" name="statusPesquisa">
								<option <?php if($status=='agendado'){ echo 'selected'; } ?> value="agendado">Agendado</option>
								<option <?php if($status=='recepcionado'){ echo 'selected'; } ?> value="recepcionado">Recepcionado</option>
								<option <?php if($status=='triado'){ echo 'selected'; } ?> value="triado">Triado</option>
								<option <?php if($status=='finalizado'){ echo 'selected'; } ?> value="finalizado">Finalizado</option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Início:</label>
							<input type="date" name="dataInicio" value="<?php echo $dataInicio ?>" class="form-control">
						</div>
						<div class="col-md-3">
							<label>Fim:</label>
							<input type="date" name="dataFim" value="<?php echo $dataFim ?>" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Nome paciente:</label>
							<input type="text" name="nome" value="<?php echo $nome ?>" class="form-control" placeholder="Digite o nome do paciente...">
						</div>
						<div class="col-md-3">
							<label>CPF:</label>
							<input type="text" name="cpf" value="<?php echo $cpf ?>" class="form-control" placeholder="Digite o CPF...">
						</div>
						<div class="col-md-2 mt-4">
							<button class="btn btn-primary">Pesquisar</button>
						</div>
					</div>
				</form>
				<h5 class="card-title">Atendimentos do dia</h5>
				<table class="table table-bordered ">
					<tr>
						<th class="text-center">ID</th>
						<th class="text-center">Paciente</th>
						<th class="text-center">Data</th>
						<th class="text-center">Hora</th>
						<th class="text-center">Médico</th>
						<th class="text-center">Status</th>
						<th class="text-center">Ações</th>
					</tr>
					<?php
						foreach ($atendimentos as $atendimento) {

							if ($atendimento['status']=='agendado') {
								$status = '<a href="#" class="btn btn-warning btn-sm">AGENDADO</a>';
								$acoes = '<a class="btn btn-warning btn-sm" href="?status=recepcionado&idAtendimento='.$atendimento['idAtendimento'].'">Marcar Presente</a>';
							}

							if ($atendimento['status']=='recepcionado') {
								$status = '<a href="#" class="btn btn-secondary btn-sm">RECEPCIONADO</a>';
								$acoes = '';
							}

							if ($atendimento['status']=='triado') {
								$status = '<a href="#" class="btn btn-success btn-sm">TRIADO</a>';
								$acoes = '';
							}

							if ($atendimento['status']=='finalizado') {
								$status = '<a href="#" class="btn btn-primary btn-sm">FINALIZADO</a>';
								$acoes = '';
							}

							echo '
								<tr>
									<td class="text-center">'.$atendimento['idAtendimento'].'</td>
									<td>'.$atendimento['nomePaciente'].'</td>
									<td class="text-center">'.date('d/m/Y', strtotime($atendimento['data'])).'</td>
									<td class="text-center">'.date('H:i', strtotime($atendimento['hora'])).'</td>
									<td>'.$atendimento['nomeMedico'].'</td>
									<td class="text-center">'.$status.'</td>
									<td class="text-center">
										'.$acoes.'
									</td>
								</tr>';
						}

					?>
				</table>
			</div>
		</div>
	</div>
</body>
</html>