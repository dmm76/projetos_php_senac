<!-- 10. Impressão de uma pirâmide de asteriscos
Utilize laços para imprimir uma pirâmide de asteriscos com 5 linhas. Exemplo:

*
**
***
****
*****
***** -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <?php

   for($i=1; $i<=6; $i++){
        for($j=1; $j<=$i; $j++){
            echo "*";
        }            
        echo "<br>";        
   } 
?>
</body>

</html>