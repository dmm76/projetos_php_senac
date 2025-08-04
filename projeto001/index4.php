<?php
$pessoas = [
    [
        "nome" => "João",
        "idade" => 33,
        "cidade" => "Maringá",
        "uf" => "PR"
    ],
    [
        "nome" => "Maria",
        "idade" => 25,
        "cidade" => "Sarandi",
        "uf" => "PR"
    ],
    [
        "nome" => "Hendrius",
        "idade" => 50,
        "cidade" => "Curitiba",
        "uf" => "PR"
    ]
];

foreach($pessoas as $index => $pessoa){
    $cor = ($index % 2 == 0) ? "blue" : "red";
    echo "<span style='color: $cor'>";
    echo "<strong>Pessoa: " . ($index + 1) . "</strong><br>";
    foreach($pessoa as $chave => $valor){
        echo "$chave: $valor<br>";
    }
    echo "</span><hr>";
}
?>
