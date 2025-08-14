<?php
include_once("includes/conexao.php");
//receita(idReceita, nome, descricao, idCategoria, foto)
class Receita
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
        $idCategoria = $data['idCategoria'];
        $foto = $data['foto'];      

        $sql = "INSERT INTO receita(nome, descricao, idCategoria, foto)
                    VALUES ('$nome', '$descricao', '$idCategoria', '$foto')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM receita
            ORDER BY idReceita ASC";

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

    public function buscar($idReceita)
    {
        $sql = "SELECT * FROM receita WHERE idReceita = '{$idReceita}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idReceita = $data['idReceita'];
        $nome = $data['nome'];
        $descricao = $data['descricao'];
        $idCategoria = $data['idCategoria'];
        $foto = $data['foto'];       

        $sql = "UPDATE receita SET nome = '{$nome}', descricao = '{$descricao}', idCategoria = '{$idCategoria}', foto = '{$foto}'
                    WHERE idreceita = '{$idReceita}'";

        return $this->bd->query($sql);
    }

    public function deletar($idReceita){
        $idReceita = (int)$idReceita;
        $sql = "DELETE fROM receita where idReceita = {$idReceita}";
        return $this->bd->query($sql);
    }
}