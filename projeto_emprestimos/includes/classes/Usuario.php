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
		$idUsuario   = (int)($data['idUsuario'] ?? 0);
		$nome        = $data['nome'] ?? '';
		$email       = $data['email'] ?? '';
		$apartamento = $data['apartamento'] ?? '';
		$senhaRaw    = $data['senha'] ?? '';

		if ($idUsuario == 0) {
			// INSERT exige senha
			$senha = md5($senhaRaw);
			$sql = "INSERT INTO usuario (nome, email, senha, apartamento)
                    VALUES ('{$nome}', '{$email}', '{$senha}', '{$apartamento}')";
			return $this->bd->query($sql);
		} else {
			// UPDATE: só muda senha se vier preenchida
			if (trim($senhaRaw) !== '') {
				$senha = md5($senhaRaw);
				$sql = "UPDATE usuario SET 
                          nome = '{$nome}', 
                          email = '{$email}', 
                          senha = '{$senha}',
                          apartamento = '{$apartamento}'
                        WHERE id = '{$idUsuario}'";
			} else {
				$sql = "UPDATE usuario SET 
                          nome = '{$nome}', 
                          email = '{$email}', 
                          apartamento = '{$apartamento}'
                        WHERE id = '{$idUsuario}'";
			}
			return $this->bd->query($sql);
		}
	}

	/** Atualiza perfil (sem mexer em senha/nível) */
	function atualizarPerfil(int $idUsuario, string $nome, string $email, string $apartamento)
	{
		$sql = "UPDATE usuario SET 
                    nome = '{$nome}',
                    email = '{$email}',
                    apartamento = '{$apartamento}'
                WHERE id = '{$idUsuario}'
                LIMIT 1";
		return $this->bd->query($sql);
	}

	/**
	 * Altera senha validando a senha atual (MD5).
	 * Retorna:
	 * - true  => alterou
	 * - false => senha atual incorreta ou erro no update
	 */
	function alterarSenha(int $idUsuario, string $senhaAtual, string $senhaNova): bool
	{
		$senhaAtualMd5 = md5($senhaAtual);

		// valida senha atual
		$sqlCheck = "SELECT id FROM usuario 
                      WHERE id = '{$idUsuario}' AND senha = '{$senhaAtualMd5}' 
                      LIMIT 1";
		$rs = $this->bd->query($sqlCheck);
		if (!$rs || $rs->num_rows === 0) {
			return false; // senha atual errada
		}

		$senhaNovaMd5 = md5($senhaNova);
		$sqlUpd = "UPDATE usuario SET senha = '{$senhaNovaMd5}' 
                    WHERE id = '{$idUsuario}' 
                    LIMIT 1";
		return (bool)$this->bd->query($sqlUpd);
	}

	function listar()
	{
		$sql = "SELECT * FROM usuario ORDER BY nome";
		$resultado = $this->bd->query($sql);

		$rows = [];
		if ($resultado) {
			while ($row = $resultado->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		return $rows;
	}

	function buscaID($idUsuario)
	{
		$idUsuario = (int)$idUsuario;
		$sql = "SELECT * FROM usuario WHERE id = '{$idUsuario}'";
		$resultado = $this->bd->query($sql);
		return $resultado ? $resultado->fetch_assoc() : null;
	}

	function deletar($idUsuario)
	{
		$idUsuario = (int)$idUsuario;
		$sql = "DELETE FROM usuario WHERE id = '{$idUsuario}'";
		return $this->bd->query($sql);
	}

	function login($email, $senha)
	{
		$senha = md5($senha);
		$sql = "SELECT id, nome, email, senha, apartamento, nivel 
                  FROM usuario
                 WHERE email = '{$email}' AND senha = '{$senha}'
                 LIMIT 1";
		$resultado = $this->bd->query($sql);
		return $resultado ? $resultado->fetch_assoc() : null;
	}
}
