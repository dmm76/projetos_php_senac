<?php
include_once("includes/conexao.php");
//categoria(idCategoria, nome, descricao)
class Categoria
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $nome = $data['nome'];
        $descricao = $data['descricao'];       

        $sql = "INSERT INTO categoria(nome, descricao)
                    VALUES ('$nome', '$descricao')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM categoria
            ORDER BY idCategoria ASC";

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

    public function buscar($idCategoria)
    {
        $sql = "SELECT * FROM categoria WHERE idCategoria = '{$idCategoria}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idCategoria = $data['idCategoria'];
        $nome = $data['nome'];
        $descricao = $data['descricao'];       

        $sql = "UPDATE categoria SET nome = '{$nome}', descricao = '{$descricao}'
                    WHERE idcategoria = '{$idCategoria}'";

        return $this->bd->query($sql);
    }

    public function deletar($idCategoria){
        $id = (int)$idCategoria;
        $sql = "DELETE fROM categoria where idCategoria = {$id}";
        return $this->bd->query($sql);
    }
}