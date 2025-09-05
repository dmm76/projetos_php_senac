<?php
include_once("includes/conexao.php");

class Usuario
{

	private $bd;

	public function __construct(Database $bd)
	{
		$this->bd = $bd;
	}

	function inserir(array $data)
	{

		$idUsuario = $data['idUsuario'];
		$nome = $data['nome'];
		$email = $data['email'];
		$senha = md5($data['senha']);
		$apartamento = $data['apartamento'];

		if ($idUsuario == 0) {

			$sql = "INSERT INTO usuario (nome, email, senha, apartamento)
						VALUES ('$nome', '$email', '$senha', '$apartamento')";

			return $this->bd->query($sql);
		} else {

			$sql = "UPDATE usuario SET 
				nome = '{$nome}', 
				email = '{$email}', 
				senha = '{$senha}',
				apartamento = '{$apartamento}'
				WHERE id = '{$idUsuario}'";

			return $this->bd->query($sql);
		}
	}

	function listar()
	{
		$sql = "SELECT * FROM usuario ORDER BY nome";

		$resultado = $this->bd->query($sql);

		$rows = [];
		while ($row = $resultado->fetch_assoc()) {
			$rows[] = $row;
		}

		return $rows;
	}

	function buscaID($idUsuario)
	{
		$sql = "SELECT * FROM usuario WHERE id = '{$idUsuario}'";

		$resultado = $this->bd->query($sql);
		$resultado = $resultado->fetch_assoc();

		return $resultado;
	}

	function deletar($idUsuario)
	{
		$sql = "DELETE FROM usuario WHERE id = '{$idUsuario}'";
		return $this->bd->query($sql);
	}

	function login($email, $senha)
	{

		$senha = md5($senha);

		$sql = "SELECT id, email, senha, apartamento, nivel 
					FROM usuario
					WHERE email = '{$email}' AND senha = '{$senha}'";

		$resultado = $this->bd->query($sql);
		$resultado = $resultado->fetch_assoc();

		return $resultado;
	}
}
