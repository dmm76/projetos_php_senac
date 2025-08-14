<?php
include_once("includes/conexao.php");
include_once("includes/classes/Receita.php");
include_once("includes/classes/Produto.php");
include_once("includes/classes/Categoria.php");
include_once("includes/classes/ReceitaProduto.php");

//receita(idReceita, nome, descricao, idCategoria, foto)

$bd = new Database();
$receita = new Receita($bd);
$receitas = $receita->listar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Cadastro de Receitas - POO</title>
</head>

<body>
    <!-- (titulo, autor, editora, anoPublicacao, descricao, capa) -->
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>
    <div class="container card">
        <h3 class="mt-3 ms-3">Cadastro de receitas</h3>
        <div class="card-body">
            <div class="row md-2">
                <div class="col-md-12 text-end mb-3">
                    <a href="criar.php" class="btn btn-outline-primary btn-sm">+ Novo receita</a>
                </div>
            </div>
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Id receita</th>
                    <th>Nome</th>
                    <th>Descricao</th>
                    <th>Id Categoria</th>
                    <th>Foto</th>                    
                    <th>Ações</th>
                </tr>                            
                    <?php foreach ($receitas as $receita){
                      echo '
                        <tr>
                            <td>'.$receita['idReceita'].'</td>
                            <td>'.$receita['nome'].'</td>
                            <td>'.$receita['descricao'].'</td>
                            <td>'.$receita['idCategoria'].'</td>
                            <td>'.$receita['foto'].'</td>                           
                            <td>
                                <a href="receita.php?idReceita='.$receita['idReceita'].'
                                ">Editar</a>
                                <a onclick="return confirm(\'Deseja excluir realmente\');" href="excluir_receita.php?idReceita='.$receita['idReceita'].'
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