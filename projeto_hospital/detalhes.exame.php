<?php
	
	include_once("includes/classes/Exame.php");

	if (!isset($_SESSION['idUsuario'])) {
		header("Location: login.php?Você precisa estar logado!");
		exit();
	}

	$bd = new Database();
	$Exame = new Exame($bd);

	if (isset($_GET['idSolicitacao'])) {
		$idSolicitacao = $_GET['idSolicitacao'];
		$idAtendimento = $_GET['idAtendimento'];
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){

		$data = [
			'idSolicitacao' => $_POST['idSolicitacao'],
			'cod' => $_POST['cod'],
			'descricao' => $_POST['descricao'],
			'obs' => $_POST['obs'],
		];

		if($Exame->inserirExames($data)){
			// header("Location: datalhes.exame.php?msg=Deu certo!");
		} else {
			header("Location: exames.php?msg=Deu ERRO!");
		}
	}

	$exames = $Exame->listarExamesSolicitacao($idSolicitacao);

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
				<h4>Inclusão de Exames</h4>
				<div class="row">
					<form action="" method="POST">
				    	<input type="hidden" name="idSolicitacao" value="<?php echo $idSolicitacao ?>">
				    	<div class="row">
					    	<div class="col-md-2">
							    <label for="cod" class="form-label">Cód.:</label>
							    <input type="text" class="form-control text-center" name="cod">
						  	</div>
						  	<div class="col-md-6">
							    <label for="descricao" class="form-label">Descrição:</label>
							    <input type="text" class="form-control text-center" name="descricao">
						  	</div>
					  	</div>
					  	<div class="row">
						  	<div class="col-md-6">
							    <label for="idAtendimento" class="form-label">Observação:</label>
							    <textarea class="form-control" name="obs"></textarea>
						  	</div>
				    	</div>
					  
					  <button type="submit" class="btn btn-primary btn-sm mt-3">Incluir exame!</button>
					</form>
					<div class="row mt-3">
						<div class="col-md-3">
							<a class="btn btn-warning btn-sm" href="atendimentos.php?acao=atendimento&idAtendimento=<?php echo $idAtendimento; ?>">Retornar ao Atendimento!</a>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="card mt-3">
			<div class="card-body">
				<div class="row">
					<table class="table table-bordered ">
						<tr>
							<th class="text-center">ID</th>
							<th class="text-center">Cód.</th>
							<th class="text-center">Descrição</th>
							<th class="text-center">Observações</th>
							<th class="text-center">Ações</th>
						</tr>
						<?php
							foreach ($exames as $exame) {
								echo '
									<tr>
										<td class="text-center">'.$exame['idExame'].'</td>
										<td>'.$exame['cod'].'</td>
										<td>'.$exame['descricao'].'</td>
										<td>'.$exame['obs'].'</td>
										<td class="text-center">
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