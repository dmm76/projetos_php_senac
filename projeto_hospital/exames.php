<?php
	
	include_once("includes/classes/Exame.php");

	if (!isset($_SESSION['idUsuario'])) {
		header("Location: login.php?Você precisa estar logado!");
		exit();
	}

	$bd = new Database();
	$Exame = new Exame($bd);

	if (isset($_GET['idAtendimento'])) {
		$idPaciente = $_GET['idPaciente'];
		$idMedico = $_GET['idMedico'];
		$idAtendimento = $_GET['idAtendimento'];
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){

		$data = [
			'idPaciente' => $_POST['idPaciente'],
			'idMedico' => $_POST['idMedico'],
			'idAtendimento' => $_POST['idAtendimento'],
		];

		if($Exame->inserir($data)){
			header("Location: exames.php?idPaciente={$idPaciente}&idMedico={$idMedico}&idAtendimento={$idAtendimento}&msg=Deu certo!");
		} else {
			header("Location: exames.php?msg=Deu ERRO!");
		}
	}

	$solicitacoes = $Exame->listar();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
	<title>Usuários</title>
</head>
<body>
	<?php include_once('includes/menu.php'); ?>

	<div class="container">
		<div class="card">
			<div class="card-body">
				<h4>Solicitação de Exames</h4>
				<div class="row">
					<form action="" method="POST">
				    	<input type="hidden" name="idPaciente" value="<?php echo $idPaciente ?>">
				    	<input type="hidden" name="idMedico" value="<?php echo $idMedico ?>">
				    	<input type="hidden" name="idAtendimento" value="<?php echo $idAtendimento ?>">
				    	<div class="row">
					    	<div class="col-md-2">
							    <label for="idPaciente" class="form-label">Paciente:</label>
							    <input type="text" class="form-control text-center" value="<?php echo $idPaciente ?>" disabled>
						  	</div>
						  	<div class="col-md-2">
							    <label for="idMedico" class="form-label">Médico:</label>
							    <input type="text" class="form-control text-center" value="<?php echo $idMedico ?>" disabled>
						  	</div>
						  	<div class="col-md-2">
							    <label for="idAtendimento" class="form-label">Atendimento:</label>
							    <input type="text" class="form-control text-center" value="<?php echo $idAtendimento ?>" disabled>
						  	</div>
				    	</div>
					  
					  <button type="submit" class="btn btn-primary btn-sm mt-3">Incluir solicitação!</button>
					</form>

				</div>
			</div>
		</div>

		<div class="card mt-3">
			<div class="card-body">
				<div class="row">
					<table class="table table-bordered ">
						<tr>
							<th class="text-center">ID</th>
							<th class="text-center">Data</th>
							<th class="text-center">Médico</th>
							<th class="text-center">Paciente</th>
							<th class="text-center">Atendimento</th>
							<th class="text-center">Ações</th>
						</tr>
						<?php
							foreach ($solicitacoes as $solicitacao) {
								echo '
									<tr>
										<td class="text-center">'.$solicitacao['idSolicitacao'].'</td>
										<td class="text-center">'.date('d/m/Y', strtotime($solicitacao['cadastro'])).'</td>
										<td>'.$solicitacao['nomeMedico'].'</td>
										<td>'.$solicitacao['nomePaciente'].'</td>
										<td>'.$solicitacao['idAtendimento'].'</td>
										<td class="text-center">
											<a class="btn btn-primary btn-sm" href="detalhes.exame.php?idSolicitacao='.$solicitacao['idSolicitacao'].'&idAtendimento='.$solicitacao['idAtendimento'].'">Incluir exames</a>
										</td>
									</tr>';
							}

						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>