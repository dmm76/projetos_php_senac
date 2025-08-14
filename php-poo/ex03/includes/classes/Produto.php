<?php
include_once("includes/conexao.php");
//produto(idProduto, nome)
class Produto
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $nome = $data['nome'];       

        $sql = "INSERT INTO produto(nome)
                    VALUES ('$nome')";

        return $this->bd->query($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM produto
            ORDER BY idProduto ASC";

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

    public function buscar($idProduto)
    {
        $sql = "SELECT * FROM produto WHERE idProduto = '{$idProduto}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idProduto = $data['idProduto'];
        $nome = $data['nome'];
       
        $sql = "UPDATE produto SET nome = '{$nome}'
                    WHERE idProduto = '{$idProduto}'";

        return $this->bd->query($sql);
    }

    public function deletar($idProduto){
        $id = (int)$idProduto;
        $sql = "DELETE fROM produto where idProduto = {$id}";
        return $this->bd->query($sql);
    }
}