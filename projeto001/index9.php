<?php
if (($_POST)) {
   $numero1 = $_POST['n1'];
   $numero2 = $_POST['n2'];        
   $sinal = $_POST['sinal'];
   if($sinal == "+"){
    $resultado = $numero1 + $numero2;
   }else if($sinal == "-"){
        $resultado = $numero1 - $numero2;
   }else{
    return 0;
   }
    echo "<br>Resultado: " . $resultado;
    
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Primeiro Numero</label>
                            <input type="number" name="n1" placeholder="Digite o primeiro numero" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label for="">Segundo Numero</label>
                            <input type="number" name="n2" placeholder="Digite o segundo numero" class="form-control">
                        </div>
                        
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" name="sinal"  value="+" class="btn btn-primary">Somar</button>
                            <button type="submit" name="sinal"  value="-"  class="btn btn-primary">Subtrair</button>
                            <button type="submit" name="sinal"  value=""  class="btn btn-primary">Sair</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>