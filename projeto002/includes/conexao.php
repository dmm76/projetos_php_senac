<?php

    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "sistema_escolar";

    $conexao =  mysqli_connect($host, $usuario, $senha, $banco);

    if(!$conexao){
        echo "Erro ao conectar com banco de dados: ". mysqli_connect_error();
    };

?>