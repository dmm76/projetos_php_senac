<?php
	
	include_once("includes/classes/Usuario.php");

	if (!isset($_SESSION['idUsuario'])) {
		header("Location: login.php?Você precisa estar logado!");
		exit();
	}

	$bd = new Database();
	$usuario = new Usuario($bd);
	$usuarioBD = new Usuario($bd);

	if (isset($_GET['idUsuario'])) {
		$idUsuario = $_GET['idUsuario'];
		$dados = $usuarioBD->buscaID($idUsuario);

		$nome = $dados['nome'];
		$email = $dados['email'];
		$nivel = $dados['nivel'];

	} else {
		$idUsuario = 0;
		$nome = "";
		$email = "";
		$nivel = "";
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$data = [
			'idUsuario' => $_POST['idUsuario'],
			'nome' => $_POST['nome'],
			'email' => $_POST['email'],
			'senha' => $_POST['senha'],
			'nivel' => $_POST['nivel'],
		];

		if($usuario->inserir($data)){
			header("Location: usuarios.php?msg=Deu certo!");
		} else {
			header("Location: usuarios.php?msg=Deu ERRO!");
		}
	}

	$usuarios = $usuario->listar();

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
				<h3>Usuários</h3>
				<div class="row">
					<form action="" method="POST">
				    	<input type="hidden" name="idUsuario" value="<?php echo $idUsuario ?>">
					  
					  <div class="mb-3">
					    <label for="nome" class="form-label">Nome</label>
					    <input type="text" class="form-control" id="nome" value="<?php echo $nome ?>" name="nome" placeholder="Digite o nome completo">
					  </div>
					  
					  <div class="mb-3">
					    <label for="email" class="form-label">E-mail</label>
					    <input type="email" class="form-control" id="email" value="<?php echo $email ?>" name="email" placeholder="Digite o email">
					  </div>
					  
					  <div class="mb-3">
					    <label for="senha" class="form-label">Senha</label>
					    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a senha">
					  </div>
					  
					  <div class="mb-3">
					    <label for="nivel" class="form-label">Nível</label>
					    <select class="form-select" id="nivel" name="nivel">
					      <option selected>Selecione o nível!</option>
					      <option <?php if($nivel=='recepcao'){ echo 'selected'; } ?> value="recepcao">Recepção</option>
					      <option <?php if($nivel=='enfermeiro'){ echo 'selected'; } ?> value="enfermeiro">Enfermeiro</option>
					      <option <?php if($nivel=='medico'){ echo 'selected'; } ?> value="medico">Médico</option>
					      <option <?php if($nivel=='admin'){ echo 'selected'; } ?> value="admin">Administrador</option>
					    </select>
					  </div>
					  
					  <button type="submit" class="btn btn-primary">Enviar</button>
					</form>

				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">

				<div class="row">
					<table class="table table-bordered table-sm">
						<tr>
							<th>ID</th>
							<th>Nome</th>
							<th>E-mail</th>
							<th>Nível</th>
							<th>Ações</th>
						</tr>
						<?php
							foreach ($usuarios as $usuario) {
								echo '
									<tr>
										<td>'.$usuario['idUsuario'].'</td>
										<td>'.$usuario['nome'].'</td>
										<td>'.$usuario['email'].'</td>
										<td>'.$usuario['nivel'].'</td>
										<td>
											<a href="?idUsuario='.$usuario['idUsuario'].'">Editar</a>
											<a onclick="return confirm(\'Deseja realmente excluir?\');" href="excluir.usuario.php?idUsuario='.$usuario['idUsuario'].'">Excluir</a>
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