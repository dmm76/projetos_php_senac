<?php
    // $nomes = ["João", "Douglas", "Josue", "Dani", "Jeferson"];

    // foreach($nomes as $nome){
    //     echo "Nome: $nome<br>";
    // }

    $alunos = [
    ["nome" => "João", "nota" => "5.9"],
    ["nome" => "Maria", "nota" => "7.9" ], 
    ["nome" => "Douglas", "nota" => "10.0"],
    ["nome" => "Josue", "nota" => "4.5"],
    ["nome" => "Ana", "nota" => "4.8"]
    ];

    foreach($alunos as $aluno){
        if($aluno["nota"] < 6){
            echo "<tr style='color: red;'>";
            echo "<tr style='color:red'>" . $aluno["nome"] . " - " . $aluno["nota"] . "</tr>";
            echo "</tr>";
        }else{
             echo "<tr style='color: blue;'>";
            echo "<tr style='color:blue'>" . $aluno["nome"] . " - " . $aluno["nota"] . "</tr>";
            echo "</tr>";
        }
    }

    // echo $aluno["nome"] . " - " . $aluno["nota"] . "<br>";
?>