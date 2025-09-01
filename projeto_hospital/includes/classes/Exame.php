<?php
	
	include_once("includes/conexao.php");

	class Exame {

		private $bd;

		public function __construct(Database $bd){
			$this->bd = $bd;
		}

		function inserir(array $data){
			$cadastro = date('Y-m-d H:i:s');
			$idMedico = $data['idMedico'];
			$idPaciente = $data['idPaciente'];
			$idAtendimento = $data['idAtendimento'];
			$status = 'solicitado';

			$sql = "INSERT INTO solicitacoes (cadastro, idMedico, idPaciente, idAtendimento, status)
					VALUES ('$cadastro', '$idMedico', '$idPaciente', '$idAtendimento', '$status')";

			return $this->bd->query($sql);
		}

		function inserirExames(array $data){
			$idSolicitacao = $data['idSolicitacao'];
			$cod = $data['cod'];
			$descricao = $data['descricao'];
			$obs = $data['obs'];


			$sql = "INSERT INTO solic_exames (idSolicitacao, cod, descricao, obs)
					VALUES ('$idSolicitacao', '$cod', '$descricao', '$obs')";

			return $this->bd->query($sql);
		}

		function listar(){
			$sql = "SELECT solicitacoes.idSolicitacao, solicitacoes.cadastro,
                    solicitacoes.idMedico, solicitacoes.idPaciente, solicitacoes.idAtendimento,
                    solicitacoes.status, usuarios.nome as nomeMedico,
                    pacientes.nome as nomePaciente FROM solicitacoes
                    INNER JOIN usuarios ON solicitacoes.idMedico = usuarios.idUsuario
                    INNER JOIN pacientes ON solicitacoes.idPaciente = pacientes.idPaciente
                    ORDER BY solicitacoes.cadastro ASC";

			$resultado = $this->bd->query($sql);

			$rows = [];
			while($row = $resultado->fetch_assoc()){
				$rows[] = $row;
			}

			return $rows;
		}

		function listarExamesSolicitacao($idSolicitacao){
			$sql = "SELECT * FROM solic_exames WHERE idSolicitacao = '{$idSolicitacao}'";

			$resultado = $this->bd->query($sql);

			$rows = [];
			while($row = $resultado->fetch_assoc()){
				$rows[] = $row;
			}

			return $rows;
		}

		function cancelar($idAtendimento){
			$sql = "DELETE FROM atendimentos WHERE idAtendimento = '{$idAtendimento}'";
			return $this->bd->query($sql);
		}

	}

?>