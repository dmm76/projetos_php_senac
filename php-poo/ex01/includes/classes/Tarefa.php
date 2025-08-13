<?php
include_once("includes/conexao.php");

class Tarefa
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $descricao = $data['descricao'];
        $dataInicial = $data['dataInicial'];
        $dataFinal = $data['dataFinal'];
        $status = $data['status'];
        $idUsuario = $data['idUsuario'];
       

        $sql = "INSERT INTO tarefa(descricao, dataInicial, dataFinal, status, idUsuario)
                    VALUES ('$descricao', '$dataInicial', '$dataFinal', '$status', '$idUsuario')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM tarefa
            ORDER BY idtarefa ASC";

        //essa linha pega todos os dados vindos do banco e insere em resultado
        $resultado = $this->bd->query($sql);

        $rows = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function buscar($idtarefa)
    {
        $sql = "SELECT * FROM tarefa WHERE idtarefa = '{$idtarefa}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idtarefa = $data['idtarefa'];
        $descricao = $data['descricao'];
        $dataInicial = $data['dataInicial'];
        $dataFinal = $data['dataFinal'];
        $status = $data['status'];
        $idUsuario = $data['idUsuario'];
      

        $sql = "UPDATE tarefa SET descricao = '{$descricao}', dataInicial = '{$dataInicial}', dataFinal = '{$dataFinal}', status = '{$status}', idUsuario = '{$idUsuario}'
                    WHERE idtarefa = '{$idtarefa}'";

        return $this->bd->query($sql);
    }

    public function deletar($idtarefa){
        $id = (int)$idtarefa;
        $sql = "DELETE fROM tarefa where idtarefa = {$id}";
        return $this->bd->query($sql);
    }
}