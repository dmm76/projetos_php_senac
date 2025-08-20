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
        $idUsuario = $data['idUsuario'];
        $cadastro = date('Y-m-d H:i:s');
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];
        $nivel = $data['nivel'];

        if ($idUsuario == 0) {
            $sql = "INSERT INTO usuarios(cadastro, nome, email, senha, nivel)
                    VALUES ('$cadastro', '$nome', '$email', '$senha', '$nivel')";

            return $this->bd->query($sql);
        } else {
            $sql = "UPDATE usuarios SET nome = '{$nome}', email = '$email', senha = '$senha', nivel = '$nivel'
                    WHERE idUsuario = '{$idUsuario}'";

            return $this->bd->query($sql);
        }
    }

    public function listar()
    {
        $sql = "SELECT * FROM usuarios
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
        $sql = "SELECT * FROM usuarios WHERE idUsuario = '{$idUsuario}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    // public function atualizar(array $data)
    // {
    //     $idUsuario = $data['idUsuario'];
    //     $cadastro = $data['cadastro'];
    //     $nome = $data['nome'];
    //     $email = $data['email'];
    //     $senha = $data['senha'];
    //     $nivel = $data['nivel'];

    //     $sql = "UPDATE produto SET cadastro = '$cadastro', nome = '{$nome}', email = '$email', senha = '$senha', nivel = '$nivel'
    //                 WHERE idUsuario = '{$idUsuario}'";

    //     return $this->bd->query($sql);
    // }

    public function deletar($idUsuario)
    {
        $id = (int)$idUsuario;
        $sql = "DELETE FROM usuarios where idUsuario = {$id}";
        return $this->bd->query($sql);
    }
}
