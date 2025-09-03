<?php
  include_once("includes/conexao.php");

  class Reserva {

    private $bd;

    public function __construct(Database $bd){
      $this->bd = $bd;
    }

    /**
     * Insere ou atualiza uma reserva.
     * $data esperado:
     * - idReserva (0 para inserir)
     * - id_ferramenta
     * - id_usuario
     * - status (opcional; padrão 'ativa' ao inserir)
     */
    function inserir(array $data){

      $idReserva    = (int)$data['idReserva'];
      $id_ferramenta= (int)$data['id_ferramenta'];
      $id_usuario   = (int)$data['id_usuario'];
      $status       = isset($data['status']) ? $data['status'] : 'ativa';

      if ($idReserva == 0) {

        // evita duplicar reserva ATIVA do mesmo usuário para a mesma ferramenta
        $sqlDup = "
          SELECT id FROM reserva
           WHERE id_ferramenta = '{$id_ferramenta}'
             AND id_usuario    = '{$id_usuario}'
             AND status        = 'ativa'
           LIMIT 1";
        $dup = $this->bd->query($sqlDup);
        if ($dup && $dup->num_rows > 0) {
          return false;
        }

        $sql = "INSERT INTO reserva (id_ferramenta, id_usuario, status)
                VALUES ('{$id_ferramenta}', '{$id_usuario}', '{$status}')";

        return $this->bd->query($sql);

      } else {

        $sql = "UPDATE reserva SET
                  id_ferramenta = '{$id_ferramenta}',
                  id_usuario    = '{$id_usuario}',
                  status        = '{$status}'
                WHERE id = '{$idReserva}'";

        return $this->bd->query($sql);
      }
    }

    /** Lista todas as reservas (com nomes, útil para admin). */
    function listar(){
      $sql = "SELECT r.*,
                     f.nome AS ferramentaNome,
                     u.nome AS usuarioNome
                FROM reserva r
                JOIN ferramenta f ON f.id = r.id_ferramenta
                JOIN usuario   u ON u.id = r.id_usuario
            ORDER BY r.data_reserva DESC";

      $resultado = $this->bd->query($sql);

      $rows = [];
      if ($resultado) {
        while($row = $resultado->fetch_assoc()){
          $rows[] = $row;
        }
      }
      return $rows;
    }

    /** Lista reservas de um usuário (para a área logada). */
    function listarPorUsuario($id_usuario){
      $sql = "SELECT r.*,
                     f.nome AS ferramentaNome
                FROM reserva r
                JOIN ferramenta f ON f.id = r.id_ferramenta
               WHERE r.id_usuario = '{$id_usuario}'
            ORDER BY r.data_reserva DESC";

      $resultado = $this->bd->query($sql);

      $rows = [];
      if ($resultado) {
        while($row = $resultado->fetch_assoc()){
          $rows[] = $row;
        }
      }
      return $rows;
    }

    /** Busca reserva por ID. */
    function buscaID($idReserva){
      $sql = "SELECT * FROM reserva WHERE id = '{$idReserva}'";
      $resultado = $this->bd->query($sql);
      return $resultado ? $resultado->fetch_assoc() : null;
    }

    /** Deleta (remove do histórico). Prefira cancelar() para apenas mudar o status. */
    function deletar($idReserva){
      $sql = "DELETE FROM reserva WHERE id = '{$idReserva}'";
      return $this->bd->query($sql);
    }

    /** Cancela uma reserva ativa (padrão para o usuário). */
    function cancelar($idReserva, $id_usuario = null){
      // se $id_usuario vier, garante que é do dono
      $filtro = $id_usuario ? " AND id_usuario = '{$id_usuario}'" : "";
      $sql = "UPDATE reserva
                 SET status = 'cancelada'
               WHERE id = '{$idReserva}' {$filtro} AND status = 'ativa'";
      return $this->bd->query($sql);
    }

    /** Verifica se existe alguma reserva ATIVA para a ferramenta. */
    function existeReservaAtivaFerramenta($id_ferramenta){
      $sql = "SELECT 1 FROM reserva
               WHERE id_ferramenta = '{$id_ferramenta}'
                 AND status = 'ativa'
               LIMIT 1";
      $resultado = $this->bd->query($sql);
      return ($resultado && $resultado->num_rows > 0);
    }

    /** Fila (todas as ativas) de uma ferramenta, mais antigo primeiro. */
    function filaDaFerramenta($id_ferramenta){
      $sql = "SELECT r.*,
                     u.nome AS usuarioNome
                FROM reserva r
                JOIN usuario u ON u.id = r.id_usuario
               WHERE r.id_ferramenta = '{$id_ferramenta}'
                 AND r.status = 'ativa'
            ORDER BY r.data_reserva ASC";
      $resultado = $this->bd->query($sql);

      $rows = [];
      if ($resultado) {
        while($row = $resultado->fetch_assoc()){
          $rows[] = $row;
        }
      }
      return $rows;
    }

    /** Retorna a próxima reserva ATIVA (topo da fila) de uma ferramenta. */
    function proximaAtiva($id_ferramenta){
      $sql = "SELECT * FROM reserva
               WHERE id_ferramenta = '{$id_ferramenta}'
                 AND status = 'ativa'
            ORDER BY data_reserva ASC
               LIMIT 1";
      $resultado = $this->bd->query($sql);
      return $resultado ? $resultado->fetch_assoc() : null;
    }

    /** Marca uma reserva como atendida (quando vira empréstimo). */
    function marcarAtendida($idReserva){
      $sql = "UPDATE reserva SET status = 'atendida' WHERE id = '{$idReserva}'";
      return $this->bd->query($sql);
    }

  }
?>
