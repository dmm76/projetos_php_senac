<?php
include_once("includes/conexao.php");

class Aluno
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $nome = $data['nome'];
        $email = $data['email'];
        $telefone = $data['telefone'];

        $sql = "INSERT INTO alunos(nome, email, telefone)
                    VALUES ('$nome', '$email', '$telefone')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM alunos
            ORDER BY idAluno ASC";

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

    public function buscar($idAluno)
    {
        $sql = "SELECT * FROM alunos WHERE idAluno = '{$idAluno}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idAluno = $data['idAluno'];
        $nome = $data['nome'];
        $email = $data['email'];
        $telefone = $data['telefone'];

        $sql = "UPDATE alunos SET nome = '{$nome}', email = '{$email}', telefone = '{$telefone}'
                    WHERE idAluno = '{$idAluno}'";

        return $this->bd->query($sql);
    }

    public function deletar($idAluno){
        $id = (int)$idAluno;
        $sql = "DELETE fROM alunos where idAluno = {$id}";
        return $this->bd->query($sql);
    }


}
