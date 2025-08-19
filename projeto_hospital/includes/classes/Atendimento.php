<?php
include_once("includes/conexao.php");
//atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idUsuario, idMedico, status, obsTriagem, obsAtendimento)

class Usuario
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {

        $cadastro = date('Y-m-d H:i:s');
        $data = date('Y-m-d');
        $hora = date('H:i:s');
        $dataInicio = $data['dataInicio'];
        $dataFim = $data['dataFim'];
        $idPaciente = $data['idPaciente'];
        $idUsuario = $data['idUsuario'];
        $idMedico = $data['idMedico'];
        $status = $data['status'];
        $obsTriagem = $data['obsTriagem'];
        $obsAtendimento = $data['obsAtendimento'];

        $sql = "INSERT INTO atendimentos(dataInicio, dataFim, idPaciente, idUsuario, idMedico, status, obsTriagem, obsAtendimento)
                    VALUES ('$dataInicio', '$dataFim', '$idPaciente', '$idUsuario', '$idMedico', '$status', '$obsTriagem', '$obsAtendimento')";

        return $this->bd->query($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM atendimentos
            ORDER BY idAtendimentos ASC";

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

    public function buscar($idAtendimento)
    {
        $sql = "SELECT * FROM atendimentos WHERE idAtendimento = '{$idAtendimento}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    }

    public function atualizar(array $data)
    {
        $idAtendimento = $data['idAtedimento'];
        $cadastro = date('Y-m-d H:i:s');
        $data = date('Y-m-d');
        $hora = date('H:i:s');
        $dataInicio = $data['dataInicio'];
        $dataFim = $data['dataFim'];
        $idPaciente = $data['idPaciente'];
        $idUsuario = $data['idUsuario'];
        $idMedico = $data['idMedico'];
        $status = $data['status'];
        $obsTriagem = $data['obsTriagem'];
        $obsAtendimento = $data['obsAtendimento'];

        $sql = "UPDATE produto SET cadastro = '$cadastro', data = '{$data}', dataInicio = '$dataInicio', dataFim = '$dataFim', idPaciente = '$idPaciente'
                    WHERE idUsuario = '{$idUsuario}'";

        return $this->bd->query($sql);
    }

    public function deletar($idUsuario)
    {
        $id = (int)$idUsuario;
        $sql = "DELETE FROM usuarios where idUsuario = {$id}";
        return $this->bd->query($sql);
    }
}
