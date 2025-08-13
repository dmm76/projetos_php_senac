<?php
include_once("includes/conexao.php");

class Livro
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $titulo = $data['titulo'];
        $autor = $data['autor'];
        $editora = $data['editora'];
        $anoPublicacao = $data['anoPublicacao'];
        $descricao = $data['descricao'];
        $capa = $data['capa'];

        $sql = "INSERT INTO livro(titulo, autor, editora, anoPublicacao, descricao, capa)
                    VALUES ('$titulo', '$autor', '$editora', '$anoPublicacao', '$descricao', '$capa')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM livro
            ORDER BY idlivro ASC";

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

    public function buscar($idLivro)
    {
        $sql = "SELECT * FROM livro WHERE idLivro = '{$idLivro}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idLivro = $data['idLivro'];
        $titulo = $data['titulo'];
        $autor = $data['autor'];
        $editora = $data['editora'];
        $anoPublicacao = $data['anoPublicacao'];
        $descricao = $data['descricao'];
        $capa = $data['capa'];

        $sql = "UPDATE livro SET titulo = '{$titulo}', autor = '{$autor}', editora = '{$editora}', anoPublicacao = '{$anoPublicacao}', descricao = '{$descricao}', capa = '{$capa}'
                    WHERE idLivro = '{$idLivro}'";

        return $this->bd->query($sql);
    }

    public function deletar($idLivro){
        $id = (int)$idLivro;
        $sql = "DELETE fROM livro where idLivro = {$id}";
        return $this->bd->query($sql);
    }
}