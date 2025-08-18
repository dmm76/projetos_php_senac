<?php

    class Database {
        public $conexao;

        public function __construct($host = "localhost", $usuario = "root", $senha = "",  $base = "hospital")
        {
            $this->conexao = new mysqli($host, $usuario, $senha, $base);

            if($this->conexao->connect_error){
                echo "Falha na conexão" . $this->conexao->connect_error;
            }
        }

        public function query($sql){
            return $this->conexao->query($sql);
        }
    }

?>