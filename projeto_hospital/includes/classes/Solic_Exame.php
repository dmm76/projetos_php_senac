<?php
include_once("includes/conexao.php");
//solic_exames(idExame, idSolicitacao, cod, status, obs)
class Solic_Exame
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
       
        $idSolicitacao = $data['idSolicitacao'];
        $cod = $data['cod'];
        $status = $data['status'];
        $obs = $data['obs'];
        

        $sql = "INSERT INTO solic_exames(idSolicitacao, cod, status, obs)
                    VALUES ('$idSolicitacao', '$cod', '$status', '$obs')";

        return $this->bd->query($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM solic_exames
            ORDER BY idExame ASC";

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
        $sql = "SELECT * FROM solic_exames WHERE idExame = '{$idExame}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idExame = $data['idExame'];
        $idSolicitacao = $data['idSolicitacao'];
        $cod = $data['cod'];
        $status = $data['status'];
        $obs = $data['obs'];
       
        $sql = "UPDATE produto SET idExame = '$idExame', idSolicitacao = '{$idSolicitacao}', cod = '$cod', status = '$status', obs = '$obs',
                    WHERE idExame = '{$idExame}'";

        return $this->bd->query($sql);
    }

    public function deletar($idExame){
        $id = (int)$idExame;
        $sql = "DELETE fROM solic_exames where idExame = {$id}";
        return $this->bd->query($sql);
    }
}