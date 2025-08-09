<?php
include_once("includes/conexao.php");

// -------------------------------------
// Variáveis simples
// -------------------------------------
$idProduto = 0;
$nome = "";

// -------------------------------------
// Excluir (GET) simples
// -------------------------------------
if (isset($_GET['acao'], $_GET['idProduto']) && $_GET['acao'] === 'excluir') {
    $id = (int)$_GET['idProduto'];
    if ($id > 0) {
        $sql = "DELETE FROM produto WHERE idProduto = $id";
        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=Produto excluído com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao excluir produto!");
        }
        exit;
    }
}

// -------------------------------------
// Salvar (POST) – inserir/atualizar
// -------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProduto = (int)($_POST['idProduto'] ?? 0);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome'] ?? ''));

    if ($nome === '') {
        header("Location: produto.php?tipoMsg=erro&msg=Informe o nome do produto.#form");
        exit;
    }

    if ($idProduto === 0) {
        // Verifica duplicado
        $check = mysqli_query($conexao, "SELECT 1 FROM produto WHERE nome = '$nome' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            header("Location: produto.php?tipoMsg=erro&msg=Já existe um produto com esse nome.#form");
            exit;
        }

        $sql = "INSERT INTO produto (nome) VALUES ('$nome')";
        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=Produto incluído com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao inserir produto!");
        }
        exit;
    } else {
        // Verifica duplicado no update (ignora o próprio)
        $check = mysqli_query($conexao, "SELECT 1 FROM produto WHERE nome = '$nome' AND idProduto <> $idProduto LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            header("Location: produto.php?tipoMsg=erro&msg=Já existe outro produto com esse nome.#form");
            exit;
        }

        $sql = "UPDATE produto SET nome = '$nome' WHERE idProduto = $idProduto";
        if (mysqli_query($conexao, $sql)) {
            header("Location: produto.php?tipoMsg=sucesso&msg=Produto atualizado com sucesso!");
        } else {
            header("Location: produto.php?tipoMsg=erro&msg=Erro ao atualizar produto!");
        }
        exit;
    }
}

// -------------------------------------
// Carregar para edição (GET)
// -------------------------------------
if (isset($_GET['idProduto']) && $_GET['idProduto'] !== '') {
    $id = (int)$_GET['idProduto'];
    if ($id > 0) {
        $res = mysqli_query($conexao, "SELECT * FROM produto WHERE idProduto = $id");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            $idProduto = (int)$row['idProduto'];
            $nome = $row['nome'] ?? "";
        }
    }
}

// -------------------------------------
// Lista + busca
// -------------------------------------
$where = "";
if (!empty($_GET['s'])) {
    $s = mysqli_real_escape_string($conexao, $_GET['s']);
    $where = "WHERE nome LIKE '%$s%'";
}
$lista = mysqli_query($conexao, "SELECT * FROM produto $where ORDER BY nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Produtos</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="container my-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="mb-0">Produtos</h2>
            <!-- Reseta o form e rola pra ele -->
            <a href="produto.php#form" class="btn btn-success">+ Novo</a>
        </div>

        <div class="row g-3">
            <!-- Formulário -->
            <div class="col-lg-5">
                <div class="card" id="form">
                    <div class="card-header"><?= $idProduto ? "Editar produto #$idProduto" : "Cadastro de produto" ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="idProduto" value="<?= (int)$idProduto ?>">

                            <div class="col-12">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>"
                                    class="form-control" placeholder="Ex.: Farinha de trigo" required>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary" type="submit">Salvar</button>
                                <a class="btn btn-outline-secondary" href="produto.php#form">Limpar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once("includes/mensagens.php"); ?>
            </div>

            <!-- Lista -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <span>Lista de Produtos</span>
                            <form class="d-flex" method="get" action="produto.php">
                                <input class="form-control form-control-sm me-2" type="search" name="s"
                                    value="<?= htmlspecialchars($_GET['s'] ?? '') ?>" placeholder="Buscar por nome...">
                                <button class="btn btn-sm btn-outline-primary" type="submit">Buscar</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:15%">ID</th>
                                        <th>Nome</th>
                                        <th style="width:18%" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($lista && mysqli_num_rows($lista) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($lista)): ?>
                                            <tr>
                                                <td><?= (int)$row['idProduto'] ?></td>
                                                <td><strong><?= htmlspecialchars($row['nome']) ?></strong></td>
                                                <td class="text-center">
                                                    <a href="?idProduto=<?= (int)$row['idProduto'] ?>#form"
                                                        class="btn btn-sm btn-primary">Editar</a>
                                                    <a href="?acao=excluir&idProduto=<?= (int)$row['idProduto'] ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Excluir o produto <?= htmlspecialchars($row['nome']) ?>?');">
                                                        Excluir
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted p-4">Nenhum produto encontrado.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container -->
</body>

</html>