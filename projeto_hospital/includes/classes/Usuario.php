<?php
	
	include_once("includes/conexao.php");

	class Usuario {

		private $bd;

		public function __construct(Database $bd){
			$this->bd = $bd;
		}

		function inserir(array $data){
			$cadastro = date('Y-m-d H:i:s');
			$idUsuario = $data['idUsuario'];
			$nome = $data['nome'];
			$email = $data['email'];
			$senha = md5($data['senha']);
			$nivel = $data['nivel'];

			if ($idUsuario==0) {

				$sql = "INSERT INTO usuarios (cadastro, nome, email, senha, nivel)
						VALUES ('$cadastro', '$nome', '$email', '$senha', '$nivel')";

				return $this->bd->query($sql);
				
			} else {

				$sql = "UPDATE usuarios SET 
				nome = '{$nome}', 
				email = '{$email}', 
				senha = '{$senha}',
				nivel = '{$nivel}'
				WHERE idUsuario = '{$idUsuario}'";

				return $this->bd->query($sql);

			}
		}

		function listar(){
			$sql = "SELECT * FROM usuarios ORDER BY nome";

			$resultado = $this->bd->query($sql);

			$rows = [];
			while($row = $resultado->fetch_assoc()){
				$rows[] = $row;
			}

			return $rows;
		}

		function buscaID($idUsuario){
			$sql = "SELECT * FROM usuarios WHERE idUsuario = '{$idUsuario}'";

			$resultado = $this->bd->query($sql);
			$resultado = $resultado->fetch_assoc();

			return $resultado;
		}

		function deletar($idUsuario){
			$sql = "DELETE FROM usuarios WHERE idUsuario = '{$idUsuario}'";
			return $this->bd->query($sql);
		}

		function login($email, $senha){
			
			$senha = md5($senha);

			$sql = "SELECT idUsuario, email, senha, nivel 
					FROM usuarios
					WHERE email = '{$email}' AND senha = '{$senha}'";

			$resultado = $this->bd->query($sql);
			$resultado = $resultado->fetch_assoc();

			return $resultado;
		}

	}

?>