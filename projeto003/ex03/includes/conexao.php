<?php

    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "tarefas";

    $conexao =  mysqli_connect($host, $usuario, $senha, $banco);

    if(!$conexao){
        echo "Erro ao conectar com banco de dados: ". mysqli_connect_error();
    };

?>