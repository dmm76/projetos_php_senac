<?php
include_once("includes/conexao.php");

class Ferramenta
{
	private $bd;

	public function __construct(Database $bd)
	{
		$this->bd = $bd;
	}

	/** INSERT/UPDATE normal, mas recebendo já o status normalizado pelo caller */
	function inserir(array $data)
	{
		$idFerramenta = (int)($data['idferramenta'] ?? 0);
		$nome         = $data['nome'] ?? '';
		$descricao    = $data['descricao'] ?? '';
		$status       = $data['status'] ?? 'disponível';
		$estado       = $data['estado'] ?? '';

		if ($idFerramenta == 0) {
			$sql = "INSERT INTO ferramenta (nome, descricao, status, estado)
                    VALUES ('{$nome}', '{$descricao}', '{$status}', '{$estado}')";
		} else {
			$sql = "UPDATE ferramenta SET 
                        nome = '{$nome}', 
                        descricao = '{$descricao}', 
                        status = '{$status}',
                        estado = '{$estado}'
                    WHERE id = '{$idFerramenta}'";
		}
		return $this->bd->query($sql);
	}

	/** === NOVO: calcula se há empréstimo “em aberto” (data_devolucao >= hoje) === */
	public function hasEmprestimoAberto(int $idferramenta): bool
	{
		$rs = $this->bd->query("
            SELECT 1 FROM emprestimo
             WHERE id_ferramenta = '{$idferramenta}'
               AND data_devolucao >= CURDATE()
             LIMIT 1
        ");
		return ($rs && $rs->num_rows > 0);
	}

	/** === NOVO: calcula se há reserva ativa p/ a ferramenta === */
	public function hasReservaAtiva(int $idferramenta): bool
	{
		$rs = $this->bd->query("
            SELECT 1 FROM reserva
             WHERE id_ferramenta = '{$idferramenta}'
               AND status = 'ativa'
             LIMIT 1
        ");
		return ($rs && $rs->num_rows > 0);
	}

	/** === NOVO: normaliza status pedido de acordo com vínculos === */
	public function normalizarStatus(int $idferramenta, string $statusRequisitado): string
	{
		$temEmp   = $this->hasEmprestimoAberto($idferramenta);
		$temRes   = $this->hasReservaAtiva($idferramenta);
		$validos  = ['disponível', 'reservada', 'emprestada'];

		if ($temEmp) return 'emprestada';
		if ($temRes) return 'reservada';

		// sem vínculos: aceita um dos válidos; evita “emprestada” sem empréstimo
		$status = in_array($statusRequisitado, $validos, true) ? $statusRequisitado : 'disponível';
		if ($status === 'emprestada') $status = 'disponível';
		return $status;
	}

	/** === NOVO: salva com normalização (insert/update) === */
	public function salvarComRegra(array $data): array
	{
		$idferramenta = (int)($data['idferramenta'] ?? 0);
		$statusReq    = $data['status'] ?? 'disponível';
		$statusFinal  = $this->normalizarStatus($idferramenta, $statusReq);

		$data['status'] = $statusFinal; // força o normalizado

		$ok  = $this->inserir($data);
		$msg = $ok
			? "Ferramenta salva com status '{$statusFinal}'."
			: "Erro ao salvar a ferramenta.";

		return ['ok' => (bool)$ok, 'status' => $statusFinal, 'msg' => $msg];
	}

	/** === NOVO: atualizar (apenas) o status, aplicando regra === */
	public function atualizarStatus(int $id, string $statusDesejado): array
	{
		// normaliza de acordo com vínculos atuais
		$statusFinal = $this->normalizarStatus($id, $statusDesejado);

		// se pediram disponível mas há vínculos, retorna msg explicativa
		if ($statusDesejado === 'disponível' && $statusFinal !== 'disponível') {
			$msg = "Não é possível marcar como disponível: ";
			if ($statusFinal === 'emprestada') $msg .= "há empréstimo em aberto";
			if ($statusFinal === 'reservada')  $msg .= ($msg !== "Não é possível marcar como disponível: " ? " e " : "") . "reserva ativa";
			$msg .= ".";
			return ['ok' => false, 'status' => $statusFinal, 'msg' => $msg];
		}

		$ok = $this->bd->query("UPDATE ferramenta SET status='{$statusFinal}' WHERE id='{$id}' LIMIT 1");
		$msg = $ok
			? "Status atualizado para '{$statusFinal}'."
			: "Erro ao atualizar status.";
		return ['ok' => (bool)$ok, 'status' => $statusFinal, 'msg' => $msg];
	}

	/** === NOVO: atalho para “Disponibilizar” === */
	public function disponibilizar(int $id): array
	{
		return $this->atualizarStatus($id, 'disponível');
	}

	function listar()
	{
		$sql = "SELECT id, nome, descricao, status, COALESCE(estado,'') AS estado
                  FROM ferramenta
              ORDER BY nome";
		$resultado = $this->bd->query($sql);

		$rows = [];
		if ($resultado) {
			while ($row = $resultado->fetch_assoc()) $rows[] = $row;
		}
		return $rows;
	}

	function buscarStatusEnum(): array
	{
		$sql = "SELECT COLUMN_TYPE
                  FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'ferramenta'
                   AND COLUMN_NAME = 'status'";
		$r = $this->bd->query($sql);
		if (!$r) return [];
		$row = $r->fetch_assoc();
		if (!$row || empty($row['COLUMN_TYPE'])) return [];
		if (!preg_match('/^enum\((.*)\)$/i', $row['COLUMN_TYPE'], $m)) return [];
		return array_map(fn($v) => trim($v, " '\""), explode(',', $m[1]));
	}

	function buscaID($idFerramenta)
	{
		$sql = "SELECT * FROM ferramenta WHERE id = '{$idFerramenta}'";
		$resultado = $this->bd->query($sql);
		return $resultado ? $resultado->fetch_assoc() : null;
	}

	function deletar($idFerramenta)
	{
		$sql = "DELETE FROM ferramenta WHERE id = '{$idFerramenta}'";
		return $this->bd->query($sql);
	}
}
