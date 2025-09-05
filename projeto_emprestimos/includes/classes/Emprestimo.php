<?php
include_once("includes/conexao.php");

class Emprestimo
{

	private $bd;

	public function __construct(Database $bd)
	{
		$this->bd = $bd;
	}

	function inserir(array $data)
	{

		$idEmprestimo = $data['idEmprestimo'];
		$id_ferramenta = $data['id_ferramenta'];
		$id_usuario = $data['id_usuario'];
		$data_emprestimo = ($data['data_emprestimo']);
		$data_devolucao = $data['data_devolucao'];

		if ($idEmprestimo == 0) {

			$sql = "INSERT INTO emprestimo (id_ferramenta, id_usuario, data_emprestimo, data_devolucao)
						VALUES ('$id_ferramenta', '$id_usuario', '$data_emprestimo', '$data_devolucao')";

			return $this->bd->query($sql);
		} else {

			$sql = "UPDATE emprestimo SET 
				id_ferramenta = '{$id_ferramenta}', 
				id_usuario = '{$id_usuario}', 
				data_emprestimo = '{$data_emprestimo}',
				data_devolucao = '{$data_devolucao}'
				WHERE id = '{$idEmprestimo}'";

			return $this->bd->query($sql);
		}
	}

	function listar()
	{

		$sql = "SELECT emprestimo.id as id, emprestimo.data_emprestimo, emprestimo.data_devolucao,
                    emprestimo.id_ferramenta, emprestimo.id_usuario,
                    ferramenta.nome AS ferramentaNome,
                    usuario.nome AS usuarioNome FROM emprestimo INNER JOIN ferramenta
                    ON emprestimo.id_ferramenta = ferramenta.id
                    INNER JOIN usuario ON emprestimo.id_usuario = usuario.id
                    ORDER BY emprestimo.id ASC";
		// $sql = "SELECT * FROM ferramenta ORDER BY id_ferramenta";

		$resultado = $this->bd->query($sql);

		$rows = [];
		while ($row = $resultado->fetch_assoc()) {
			$rows[] = $row;
		}

		return $rows;
	}

	public function listarUsuarios()
	{
		$sql = "SELECT * FROM usuario ORDER BY nome";

		$resultado = $this->bd->query($sql);

		$rows = [];
		if ($resultado && $resultado->num_rows > 0) {
			while ($row = $resultado->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		return $rows;
	}

	public function listarFerramentas()
	{
		$sql = "SELECT * FROM ferramenta ORDER BY nome";

		$resultado = $this->bd->query($sql);

		$rows = [];
		if ($resultado && $resultado->num_rows > 0) {
			while ($row = $resultado->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		return $rows;
	}





	function buscaID($idEmprestimo)
	{
		$sql = "SELECT * FROM emprestimo WHERE id = '{$idEmprestimo}'";

		$resultado = $this->bd->query($sql);
		$resultado = $resultado->fetch_assoc();

		return $resultado;
	}

	function deletar($idEmprestimo)
	{
		$sql = "DELETE FROM emprestimo WHERE id = '{$idEmprestimo}'";
		return $this->bd->query($sql);
	}

	// function login($id_usuario, $data_emprestimo){

	// 	$data_emprestimo = md5($data_emprestimo);

	// 	$sql = "SELECT id, id_usuario, data_emprestimo, data_devolucao 
	// 			FROM ferramenta
	// 			WHERE id_usuario = '{$id_usuario}' AND data_emprestimo = '{$data_emprestimo}'";

	// 	$resultado = $this->bd->query($sql);
	// 	$resultado = $resultado->fetch_assoc();

	// 	return $resultado;
	// }

}
