<?php

$host = "localhost";
$usuario = "root";
$senha = "Debase33@";
$banco = "receitas";

$conexao =  mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    echo "Erro ao conectar com banco de dados: " . mysqli_connect_error();
};
