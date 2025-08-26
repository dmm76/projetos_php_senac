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
            $sql = "INSERT INTO atendimentos(cadastro, data, hora, dataInicio, dataFim, idPaciente, idUsuario, idMedico, status, obsTriagem, obsAtendimento)
                    VALUES ('$cadastro', '$dia', '$hora', '$dataInicio', '$dataFim', '$idPaciente', '$idUsuario', '$idMedico', '$status', '$obsTriagem', '$obsAtendimento')";
            return $this->bd->query($sql);
        } else {
            if ($status == 'triado') {
                $update = ", obsTriagem = '$obsTriagem'";
            }
            if ($status == 'finalizado') {
                $update = ", obsAtendimento = '$obsAtendimento'";
            }
            $sql = "UPDATE atendimentos SET data = '{$dia}', hora='{$hora}', dataInicio = '$dataInicio',
                dataFim = '$dataFim', idPaciente = '$idPaciente', idUsuario = '$idUsuario', idMedico = '$idMedico',
                status = '$status' $update
                WHERE idAtendimento = '{$idAtendimento}'";
                return $this->bd->query($sql);
        }
    }

    public function listar()
    {
        // $sql = "SELECT * FROM atendimentos
        //     ORDER BY idAtendimento ASC";

        $sql = "SELECT atendimentos.idAtendimento, atendimentos.data,
                atendimentos.hora, pacientes.nome AS nomePaciente,
                usuarios.nome AS nomeMedico FROM atendimentos  INNER JOIN pacientes 
                ON atendimentos.idPaciente = pacientes.idPaciente
                INNER JOIN usuarios
                ON usuarios.idUsuario = atendimentos.idMedico
                ORDER BY DATA, hora ASC";

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


    public function listarAtendimentos($dataAtendimento, $nome, $cpf, $status)
    {
        $where = '';
        if ($nome != "") {
            $where .= "AND pacientes.nome LIKE '%$nome%'";
        }

        if ($cpf != "") {
            $where .= "AND pacientes.cpf = '{$cpf}'";
        }

        if ($status != '') {
            $where .= "AND atendimentos.status = '$status'";
        }

        $sql = "SELECT
                atendimentos.idAtendimento, atendimentos.data, 
                atendimentos.hora,
                atendimentos.status,
                pacientes.nome as nomePaciente,
                usuarios.nome as nomeMedico
                FROM atendimentos
                INNER JOIN pacientes ON atendimentos.idPaciente = pacientes.idPaciente
                INNER JOIN usuarios ON atendimentos.idMedico = usuarios.idUsuario
                WHERE
                    atendimentos.data = '{$dataAtendimento}'
                    $where
                ORDER BY data, hora ASC";
        $resultado = $this->bd->query($sql);

        $rows = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function listarPacientes()
    {
        $sql = "SELECT * FROM pacientes ORDER BY nome";

        $resultado = $this->bd->query($sql);

        $rows = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function listarMedicos()
    {
        $sql = "SELECT * FROM usuarios
                WHERE nivel = 'medico' ORDER BY nome";

        $resultado = $this->bd->query($sql);

        $rows = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function alterarStatus($status, $idAtendimento)
    {
        $sql = "UPDATE atendimentos SET status = '$status' WHERE idAtendimento = '{$idAtendimento}'";
        return $this->bd->query($sql);
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
