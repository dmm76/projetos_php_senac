<?php
$carros = [
    [
        "Marca" => "João",
        "Modelo" => 33,
        "cor" => "Maringá",
        "ano" => "PR"
    ],
    [
        "Marca" => "João",
        "Modelo" => 33,
        "cor" => "Maringá",
        "ano" => "PR"
    ],
    [
        "Marca" => "João",
        "Modelo" => 33,
        "cor" => "Maringá",
        "ano" => "PR"
    ]
];

foreach($carros as $index => $carro){
    $cor = ($index % 2 == 0) ? "blue" : "red";
    echo "<span style='color: $cor'>";
    echo "<strong>Carro: " . ($index + 1) . "</strong><br>";
    foreach($carro as $chave => $valor){
        echo "$chave: $valor<br>";
    }
    echo "</span><hr>";
}
?>
