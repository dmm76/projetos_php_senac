<?php
include_once("includes/conexao.php");
$msg =  "";

// Recebe id da receita
$idReceita = isset($_GET['idReceita']) ? (int)$_GET['idReceita'] : 0;
if ($idReceita <= 0) {
    $msg =   "<div class='alert alert-danger mt-4'>ID da receita inválido ou não informado!</div>";
    exit;
}

// Busca dados da receita
$sqlReceita = "SELECT * FROM receita WHERE idReceita = $idReceita";
$resReceita = mysqli_query($conexao, $sqlReceita);
$receita = mysqli_fetch_assoc($resReceita);

if (!$receita) {
    $msg =   "<div class='alert alert-danger mt-4'>Receita não encontrada!</div>";
    exit;
}

// -------------------
// UPLOAD DE FOTO
// -------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_foto'])) {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $permitidas)) {
            $nomeFoto = "receita_" . $idReceita . "_" . time() . "." . $ext;
            $caminho = "img/$nomeFoto";
            if (!is_dir('img')) mkdir('img', 0777, true);
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
                // Atualiza o campo foto no banco
                $caminhoSql = mysqli_real_escape_string($conexao, $caminho);
                mysqli_query($conexao, "UPDATE receita SET foto = '$caminhoSql' WHERE idReceita = $idReceita");
                // Atualiza $receita para mostrar a nova foto sem recarregar a página
                $receita['foto'] = $caminho;
                $msg =   "<div class='alert alert-success mt-2'>Foto atualizada com sucesso!</div>";
            } else {
                $msg =   "<div class='alert alert-danger mt-2'>Erro ao salvar a foto.</div>";
            }
        } else {
            $msg =   "<div class='alert alert-danger mt-2'>Tipo de arquivo não permitido. Use JPG, JPEG, PNG ou GIF.</div>";
        }
    } else {
        $msg =  "<div class='alert alert-warning mt-2'>Selecione uma foto para enviar.</div>";
    }
}

// -------------------
// INSERÇÃO DE PRODUTO
// -------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProduto'], $_POST['quantidade']) && !isset($_POST['atualizar_foto'])) {
    $idProduto = (int)$_POST['idProduto'];
    $quantidade = mysqli_real_escape_string($conexao, $_POST['quantidade']);
    // Valida se o produto já está na receita (opcional)
    $verifica = mysqli_query($conexao, "SELECT * FROM receita_produto WHERE idReceita = $idReceita AND idProduto = $idProduto");
    if (mysqli_num_rows($verifica) == 0) {
        $sqlInsert = "INSERT INTO receita_produto (idReceita, idProduto, quantidade) VALUES ($idReceita, $idProduto, '$quantidade')";
        mysqli_query($conexao, $sqlInsert);
    }
    header("Location: receitas.php?idReceita=$idReceita");
    exit();
}

// Busca produtos já vinculados à receita
$sqlProdutosReceita = "
    SELECT rp.idReceita, rp.idProduto, rp.quantidade, p.nome AS nomeProduto 
    FROM receita_produto rp
    INNER JOIN produto p ON rp.idProduto = p.idProduto
    WHERE rp.idReceita = $idReceita
";
$resProdutosReceita = mysqli_query($conexao, $sqlProdutosReceita);

// Busca todos produtos para popular o select
$sqlProdutos = "SELECT * FROM produto ORDER BY nome";
$resProdutos = mysqli_query($conexao, $sqlProdutos);

if (isset($_GET['idProduto'])) {

    $idProduto = $_GET['idProduto'];

    if (isset($_GET['acao'])) {
        $sql = "DELETE FROM produto WHERE idProduto = '{$idProduto}'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=O produto <b>$nome</b> foi excluido com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao excluir cadastro!");
        }
    }



    // Valida se o valor é numérico
    if (!is_numeric($idProduto)) {
        die("ID de produto inválido.");
    }

    // Prepara a consulta protegida contra SQL Injection
    $stmt = mysqli_prepare($conexao, "SELECT * FROM produto WHERE idProduto = ?");
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // $sql = "SELECT * FROM produtos WHERE idProduto = '{$idProduto}'";
    // $resultado = mysqli_query($conexao, $sql);

    //associar a variavel resultado a uma variavel consultavel;
    $row = mysqli_fetch_assoc($resultado);

    $idProduto = $row['idProduto'];
    $nome = $row['nome'];
   
}



?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Produtos da Receita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- NavBar Padrão vindo de includes/menu -->
    <?php include_once("includes/menu.php"); ?>

    <div class="container mt-4">
        <!-- Mensagem de Cadastro da imagem -->
        <?php if (!empty($msg)) echo $msg; ?>
        <h2>Ingredientes da Receita</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4><?= htmlspecialchars($receita['nome']) ?></h4>
                <p><?= htmlspecialchars($receita['descricao']) ?></p>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <?php if (!empty($receita['foto'])): ?>
                            <img src="<?= htmlspecialchars($receita['foto']) ?>" alt="Foto da Receita" class="img-fluid rounded" style="max-width:150px;">
                        <?php else: ?>
                            <span class="text-muted">Nenhuma foto cadastrada.</span>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <form method="post" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <input type="hidden" name="atualizar_foto" value="1">
                            <button type="submit" class="btn btn-secondary">Enviar nova foto</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <form method="post" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label>Produto</label>
                <select name="idProduto" class="form-select" required>
                    <option value="">Selecione o produto</option>
                    <?php mysqli_data_seek($resProdutos, 0);
                    while ($prod = mysqli_fetch_assoc($resProdutos)): ?>
                        <option value="<?= $prod['idProduto'] ?>"><?= htmlspecialchars($prod['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Quantidade</label>
                <input type="text" name="quantidade" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>
        </form>

        <h5 class="mt-4">Produtos já adicionados:</h5>
        <table class="table table-bordered mt-2">
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Ações</th>

            </tr>
            <?php while ($row = mysqli_fetch_assoc($resProdutosReceita)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nomeProduto']) ?></td>
                    <td><?= htmlspecialchars($row['quantidade']) ?></td>
                    <td>
                        <a href='?idProduto=" . $row[' idProduto'] . "' class='btn btn-primary btn-sm'>Editar</a>
                        <a href='?idProduto=" . $row['idProduto'] . "&acao=excluir' class='btn btn-danger btn-sm'>Excluir</a>  
                    </td>  
                </tr>
            <?php endwhile; ?>
        </table>
        <a href=" receita.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>

</html>