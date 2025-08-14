<?php
include_once("includes/conexao.php");
//receita_produto(idReceita_produto, idReceita, idProduto, quantidade)
class ReceitaProduto
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $idReceita = $data['idReceita'];
        $idProduto = $data['idProduto'];
        $quantidade = $data['quantidade'];
        

        $sql = "INSERT INTO receita_produto(idReceita, idProduto, quantidade)
                    VALUES ('$idReceita', '$idProduto', '$quantidade')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM receita_produto
            ORDER BY idReceita_produto ASC";

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

    public function buscar($idReceita_produto)
    {
        $sql = "SELECT * FROM receita_produto WHERE idReceita_produto = '{$idReceita_produto}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idReceita_produto = $data['idReceita_produto'];
        $idReceita = $data['idReceita'];
        $idProduto = $data['idProduto'];
        $quantidade = $data['quantidade'];       

        $sql = "UPDATE receita_produto SET idReceita = '{$idReceita}', idProduto = '{$idProduto}', quantidade = '{$quantidade}'
                    WHERE idReceita_produto = '{$idReceita_produto}'";

        return $this->bd->query($sql);
    }

    public function deletar($idReceita_produto){
        $id = (int)$idReceita_produto;
        $sql = "DELETE fROM receita_produto where idReceita_produto = {$id}";
        return $this->bd->query($sql);
    }
}