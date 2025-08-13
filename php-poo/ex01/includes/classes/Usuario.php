<?php
include_once("includes/conexao.php");

//idUsuario
//nome
//email
//telefone


class Usuario
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

        $sql = "INSERT INTO usuario(nome, email, telefone)
                    VALUES ('$nome', '$email', '$telefone')";

        return $this->bd->query($sql);
    }


    public function listar()
    {
        $sql = "SELECT * FROM usuario
            ORDER BY idUsuario ASC";

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

    public function buscar($idUsuario)
    {
        $sql = "SELECT * FROM usuario WHERE idUsuario = '{$idUsuario}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idUsuario = $data['idUsuario'];
        $nome = $data['nome'];
        $email = $data['email'];
        $telefone = $data['telefone'];
        

        $sql = "UPDATE usuario SET nome = '{$nome}', email = '{$email}', telefone = '{$telefone}'
                    WHERE idUsuario = '{$idUsuario}'";

        return $this->bd->query($sql);
    }

    public function deletar($idUsuario){
        $id = (int)$idUsuario;
        $sql = "DELETE fROM usuario where idUsuario = {$id}";
        return $this->bd->query($sql);
    }
}