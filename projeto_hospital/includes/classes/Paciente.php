<?php
include_once("includes/conexao.php");
//pacientes(idpacientes, cadastro, nome, cpf, email, telefone, endereco, idUsuario)
class Paciente
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $idPaciente = $data['idPaciente'];
        $cadastro = date('Y-m-d H:i:s');
        $nome = $data['nome'];
        $cpf = $data['cpf'];
        $email = $data['email'];
        $telefone = $data['telefone'];
        $endereco = $data['endereco'];
        $idUsuario = $data['idUsuario'];

        if ($idPaciente == 0) {

            $sql = "INSERT INTO pacientes(cadastro, nome, cpf, email, telefone, endereco, idUsuario )
                    VALUES ('$cadastro', '$nome', '$cpf', '$email', '$telefone', '$endereco', '$idUsuario')";

            return $this->bd->query($sql);
        } else {
            $sql = "UPDATE pacientes SET nome = '{$nome}', cpf = '$cpf',
                    email = '$email', telefone = '$telefone', endereco = '$endereco'
                    WHERE idPaciente = '{$idPaciente}'";

            return $this->bd->query($sql);
        }
    }

    public function listar()
    {
        $sql = "SELECT * FROM pacientes
            ORDER BY idPaciente ASC";

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

    public function buscar($idPaciente)
    {
        $sql = "SELECT * FROM pacientes WHERE idPaciente = '{$idPaciente}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    // public function atualizar(array $data)
    // {
    //     $idPaciente = $data['idPaciente'];
    //     $cadastro = $data['cadastro'];
    //     $nome = $data['nome'];
    //     $email = $data['email'];
    //     $telefone = $data['telefone'];
    //     $endereco = $data['endereco'];

    //     $sql = "UPDATE pacientes SET cadastro = '$cadastro', nome = '{$nome}', email = '$email', telefone = '$telefone', endereco = '$endereco'
    //                 WHERE idPaciente = '{$idPaciente}'";

    //     return $this->bd->query($sql);
    // }

    public function deletar($idPaciente)
    {
        $id = (int)$idPaciente;
        $sql = "DELETE FROM pacientes where idPaciente = {$id}";
        return $this->bd->query($sql);
    }
}
