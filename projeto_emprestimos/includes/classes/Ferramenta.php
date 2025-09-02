<?php
	
	include_once("includes/conexao.php");

	class Ferramenta {

		private $bd;

		public function __construct(Database $bd){
			$this->bd = $bd;
		}

		function inserir(array $data){
			
			$idFerramenta = $data['idferramenta'];
			$nome = $data['nome'];
			$descricao = $data['descricao'];
			$status = ($data['status']);
			$estado = $data['estado'];

			if ($idFerramenta==0) {

				$sql = "INSERT INTO ferramenta (nome, descricao, status, estado)
						VALUES ('$nome', '$descricao', '$status', '$estado')";

				return $this->bd->query($sql);
				
			} else {

				$sql = "UPDATE ferramenta SET 
				nome = '{$nome}', 
				descricao = '{$descricao}', 
				status = '{$status}',
				estado = '{$estado}'
				WHERE id = '{$idFerramenta}'";

				return $this->bd->query($sql);

			}
		}

		function listar(){
			$sql = "SELECT * FROM ferramenta ORDER BY nome";

			$resultado = $this->bd->query($sql);

			$rows = [];
			while($row = $resultado->fetch_assoc()){
				$rows[] = $row;
			}

			return $rows;
		}

		function buscaID($idFerramenta){
			$sql = "SELECT * FROM ferramenta WHERE id = '{$idFerramenta}'";

			$resultado = $this->bd->query($sql);
			$resultado = $resultado->fetch_assoc();

			return $resultado;
		}

		function deletar($idFerramenta){
			$sql = "DELETE FROM ferramenta WHERE id = '{$idFerramenta}'";
			return $this->bd->query($sql);
		}

		// function login($descricao, $status){
			
		// 	$status = md5($status);

		// 	$sql = "SELECT id, descricao, status, estado 
		// 			FROM ferramenta
		// 			WHERE descricao = '{$descricao}' AND status = '{$status}'";

		// 	$resultado = $this->bd->query($sql);
		// 	$resultado = $resultado->fetch_assoc();

		// 	return $resultado;
		// }

	}


?>