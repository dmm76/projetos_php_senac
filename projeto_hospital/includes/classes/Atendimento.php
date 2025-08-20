<?php
include_once("includes/conexao.php");
//atendimentos(idAtendimento, cadastro, data, hora, dataInicio, dataFim, idPaciente, idUsuario, idMedico, status, obsTriagem, obsAtendimento)

class Atendimento
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $idAtendimento = $data['idAtendimento'];
        $cadastro = date('Y-m-d H:i:s');
        $dia = date('Y-m-d');
        $hora = date('H:i:s');
        $dataInicio = $data['dataInicio'];
        $dataFim = $data['dataFim'];
        $idPaciente = $data['idPaciente'];
        $idUsuario = $data['idUsuario'];
        $idMedico = $data['idMedico'];
        $status = $data['status'];
        $obsTriagem = $data['obsTriagem'];
        $obsAtendimento = $data['obsAtendimento'];

        if ($idAtendimento == 0) {
            $sql = "INSERT INTO atendimentos(cadastro, dia, hora, dataInicio, dataFim, idPaciente, idUsuario, idMedico, status, obsTriagem, obsAtendimento)
                    VALUES ('$cadastro', '$dia', '$hora', '$dataInicio', '$dataFim', '$idPaciente', '$idUsuario', '$idMedico', '$status', '$obsTriagem', '$obsAtendimento')";

            return $this->bd->query($sql);
        } else {
            $sql = "UPDATE produto SET data = '{$dia}', hora='{$hora}', dataInicio = '$dataInicio',
                dataFim = '$dataFim', idPaciente = '$idPaciente', idUsuario = $idUsuario, idMedico = $idMedico,
                status = $status, obsTriagem = $obsTriagem, obsAtedimento = $obsAtendimento
                WHERE idAtendimento = '{$idAtendimento}'";

            return $this->bd->query($sql);
        }
    }

    public function listar()
    {
        $sql = "SELECT * FROM atendimentos
            ORDER BY idAtendimento ASC";

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

    // public function atualizar(array $data)
    // {
    //     $idAtendimento = $data['idAtedimento'];
    //     $cadastro = date('Y-m-d H:i:s');
    //     $dia = date('Y-m-d');
    //     $hora = date('H:i:s');
    //     $dataInicio = $data['dataInicio'];
    //     $dataFim = $data['dataFim'];
    //     $idPaciente = $data['idPaciente'];
    //     $idUsuario = $data['idUsuario'];
    //     $idMedico = $data['idMedico'];
    //     $status = $data['status'];
    //     $obsTriagem = $data['obsTriagem'];
    //     $obsAtendimento = $data['obsAtendimento'];

    //     $sql = "UPDATE produto SET cadastro = '$cadastro', data = '{$dia}', hora='{$hora}', dataInicio = '$dataInicio',
    //             dataFim = '$dataFim', idPaciente = '$idPaciente', idUsuario = $idUsuario, idMedico = $idMedico,
    //             status = $status, obsTriagem = $obsTriagem, obsAtedimento = $obsAtendimento
    //             WHERE idAtendimento = '{$idAtendimento}'";

    //     return $this->bd->query($sql);
    // }

    public function deletar($idAtendimento)
    {
        $id = (int)$idAtendimento;
        $sql = "DELETE FROM atendimentos where idAtendimento = {$id}";
        return $this->bd->query($sql);
    }
}
