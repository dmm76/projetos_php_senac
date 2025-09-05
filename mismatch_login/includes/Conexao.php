<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
class Database
{
    public $conexao;

    public function __construct($host = "localhost", $usuario = "root", $senha = "Debase33@",  $base = "mismatch")
    {
        $this->conexao = new mysqli($host, $usuario, $senha, $base);

        if ($this->conexao->connect_error) {
            echo "Falha na conexão" . $this->conexao->connect_error;
        }
    }

    public function query($sql)
    {
        return $this->conexao->query($sql);
    }
}
