<?php
include_once("includes/conexao.php");
//solicitacoes(idsolicitacoes, cadastro, idSolicitante, idPaciente, idMedico)
class Solicitacao
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
       
        $cadastro = date('Y-m-d H:i:s');
        $idSolicitante = $data['idSolicitante'];
        $idPaciente = $data['idPaciente'];
        $idMedico = $data['idMedico'];
        

        $sql = "INSERT INTO solicitacoes(cadastro, idSolicitante, idPaciente, idMedico)
                    VALUES ('$cadastro', '$idSolicitante', '$idPaciente', '$idMedico')";

        return $this->bd->query($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM solicitacoes
            ORDER BY idSolicitacoes ASC";

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

    public function buscar($idSolicitacoes)
    {
        $sql = "SELECT * FROM solicitacoes WHERE idSolicitacoes = '{$idSolicitacoes}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idSolicitacoes = $data['idSolicitacoes'];
        $cadastro = $data['cadastro'];
        $idSolicitante = $data['idSolicitante'];
        $idPaciente = $data['idPaciente'];
        $idMedico = $data['idMedico'];
       
        $sql = "UPDATE produto SET cadastro = '$cadastro', idSolicitante = '{$idSolicitante}', idPaciente = '$idPaciente', idMedico = '$idMedico',
                    WHERE idSolicitacoes = '{$idSolicitacoes}'";

        return $this->bd->query($sql);
    }

    public function deletar($idSolicitacoes){
        $id = (int)$idSolicitacoes;
        $sql = "DELETE fROM solicitacoes where idSolicitacoes = {$id}";
        return $this->bd->query($sql);
    }
}