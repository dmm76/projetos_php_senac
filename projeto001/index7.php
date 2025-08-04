<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php
    $carros = [
        [
            "marca" => "Tesla",
            "modelo" => "One",
            "cor" => "Branca",
            "ano" => "2025"
        ],
        [
             "marca" => "Chevrolet",
            "modelo" => "Onix",
            "cor" => "Preta",
            "ano" => "2022"
        ],
        [
            "marca" => "Volkswagen",
            "modelo" => "Golf",
            "cor" => "Azul",
            "ano" => "2020"
        ],
        [
            "marca" => "Toyota",
            "modelo" => "Corolla",
            "cor" => "Prata",
            "ano" => "2023"
        ],
        [
            "marca" => "Fiat",
            "modelo" => "Argo",
            "cor" => "Vermelha",
            "ano" => "2021"
        ]
    ];

    echo "<table class='table table-border table-striped'>
        <tr style='color: white; background-color: #42426f'>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Ano de Fabricação</th>
        </tr>";
    foreach($carros as $index => $carro) {
        $cor = ($index % 2 == 0) ? "black" : "white";
        $cor_fundo = ($index % 2 == 0) ? "#00FFFF" : "#708090";
        echo "<tr style='color: $cor; background-color: $cor_fundo'>
                <td>" . $carro["marca"] . "</td>
                <td>" . $carro["modelo"] . "</td>
                <td>" . $carro["cor"] . "</td>
                <td>" . $carro["ano"] . "</td>
             </tr>";
    }
    ?>
</body>

</html>