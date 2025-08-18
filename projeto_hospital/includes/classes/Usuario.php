<?php
include_once("includes/conexao.php");
//usuario(idUsuario, cadastro, nome, email, senha, nivel)
class Usuario
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
       
        $cadastro = $data['cadastro'];
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];
        $nivel = $data['nivel'];       

        $sql = "INSERT INTO usuario(idUsuario, cadastro, nome, email, senha, nivel)
                    VALUES ('$nome', '$cadastro', '$nome', '$email', '$senha', '$nivel')";

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
        $cadastro = $data['cadastro'];
        $nome = $data['nome'];
        $email = $data['nome'];
        $senha = $data['nome'];
        $nivel = $data['nome'];   
       
        $sql = "UPDATE produto SET cadastro = '$cadastro', nome = '{$nome}', email = '$email', senha = '$senha', nivel = '$nivel'
                    WHERE idAluno = '{$idAluno}'";

        return $this->bd->query($sql);
    }

    public function deletar($idAluno){
        $id = (int)$idAluno;
        $sql = "DELETE fROM alunos where idAlunos = {$id}";
        return $this->bd->query($sql);
    }
}