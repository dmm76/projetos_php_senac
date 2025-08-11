<?php
    include_once("includes/conexao.php");

    class Aluno{
        private $bd;
        public function __construct(Database $bd){
            $this->bd = $bd;
        }

        public function inserir(array $data){
            $nome = $data['nome'];
            $email = $data['email'];
            $telefone = $data['telefone'];

            $sql = "INSERT INTO alunos (nome, email, telefone)
                    VALUES ('$nome', '$email', '$telefone')";

            return $this->bd->query($sql);
        }
    }
?>