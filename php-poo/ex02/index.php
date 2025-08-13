<?php
include_once("includes/conexao.php");
include_once("includes/classes/Livro.php");

$bd = new Database();
$livro = new Livro($bd);
$livros = $livro->listar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro de Livro - POO</title>
</head>

<body>
    <!-- (titulo, autor, editora, anoPublicacao, descricao, capa) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card">
        <h3 class="mt-3 ms-3">Cadastro de Livros</h3>
        <div class="card-body">
            <div class="row md-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="criar.php" class="btn btn-outline-primary btn-sm">+ Novo Livro</a>
                </div>
            </div>
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Id Livro</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editora</th>
                    <th>Ano Publicação</th>
                    <th>Descrição</th>
                    <th>Capa</th>
                    <th>Ações</th>
                </tr>                            
                    <?php foreach ($livros as $livro){
                      echo '
                        <tr>
                            <td>'.$livro['idLivro'].'</td>
                            <td>'.$livro['titulo'].'</td>
                            <td>'.$livro['autor'].'</td>
                            <td>'.$livro['editora'].'</td>
                            <td>'.$livro['anoPublicacao'].'</td>
                            <td>'.$livro['descricao'].'</td>
                            <td>'.$livro['capa'].'</td>
                            
                            <td>
                                <a href="criar.php?idLivro='.$livro['idLivro'].'
                                ">Editar</a>
                                <a onclick="return confirm(\'Deseja excluir realmente\');" href="excluir.php?idLivro='.$livro['idLivro'].'
                                ">Excluir</a>
                            </td>
                        </tr>';                          
                    }                        
                    ?>                  
             
            </table>           
        </div>
    </div>

</body>

</html>