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

    echo "<table class='table table-border table-striped'>
        <tr>
            <th>Nome</th>
            <th>Idade</th>
            <th>Cidade</th>
            <th>UF</th>
        </tr>";
    foreach ($pessoas as $pessoa) {
        echo "<tr>
                <td>" . $pessoa["nome"] . "</td>
                <td>" . $pessoa["idade"] . "</td>
                <td>" . $pessoa["cidade"] . "</td>
                <td>" . $pessoa["uf"] . "</td>
             </tr>";
    }
    ?>
</body>

</html>