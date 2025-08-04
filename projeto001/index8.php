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
            "ano" => "2026"
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
            "ano" => "2019"
        ]
    ];

    echo "<table class='table table-bordered table-striped'>
        <thead>
        <tr style='color: white; background-color: #42426f'>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Ano de Fabricação</th>
        </tr>
        </thead>";
    foreach ($carros as $index => $carro) {
        if ($carro["ano"] < 2020) {
            echo "<tbody><tr class= 'bg-danger'>
                <td>" . $carro["marca"] . "</td>
                <td>" . $carro["modelo"] . "</td>
                <td>" . $carro["cor"] . "</td>
                <td>" . $carro["ano"] . "</td>
             </tr></tbody>";
        } else if ($carro["ano"] < 2023) {
            echo "<tbody><tr class= 'bg-warning'>
                <td>" . $carro["marca"] . "</td>
                <td>" . $carro["modelo"] . "</td>
                <td>" . $carro["cor"] . "</td>
                <td>" . $carro["ano"] . "</td>
             </tr></tbody>";
        } else {
            echo "<tbody><tr class= 'bg-success'>
                <td>" . $carro["marca"] . "</td>
                <td>" . $carro["modelo"] . "</td>
                <td>" . $carro["cor"] . "</td>
                <td>" . $carro["ano"] . "</td>
             </tr></tbody>";
        }
    }
    echo "</table>"
    ?>
</body>

</html>