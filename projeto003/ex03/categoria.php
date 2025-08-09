<?php
include_once("includes/conexao.php");

// -------------------------------------
// Variáveis do formulário (simples)
// -------------------------------------
$idCategoria = 0;
$nome = "";
$descricao = "";

// -------------------------------------
// Excluir (GET) – simples com confirm()
// -------------------------------------
if (isset($_GET['acao'], $_GET['idCategoria']) && $_GET['acao'] === 'excluir') {
    $id = (int)$_GET['idCategoria'];
    if ($id > 0) {
        $sql = "DELETE FROM categoria WHERE idCategoria = $id";
        if (mysqli_query($conexao, $sql)) {
            header("Location: categoria.php?tipoMsg=sucesso&msg=Categoria excluída com sucesso!");
        } else {
            header("Location: categoria.php?tipoMsg=erro&msg=Erro ao excluir categoria!");
        }
        exit;
    }
}

// -------------------------------------
// Salvar (POST) – inserir/atualizar
// -------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCategoria = (int)($_POST['idCategoria'] ?? 0);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome'] ?? ''));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao'] ?? ''));

    if ($nome === '') {
        header("Location: categoria.php?tipoMsg=erro&msg=Informe o nome da categoria.#form");
        exit;
    }

    if ($idCategoria === 0) {
        // Verifica duplicado por nome
        $check = mysqli_query($conexao, "SELECT 1 FROM categoria WHERE nome = '$nome' LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            header("Location: categoria.php?tipoMsg=erro&msg=Já existe uma categoria com esse nome.#form");
            exit;
        }

        $sql = "INSERT INTO categoria (nome, descricao) VALUES ('$nome', '$descricao')";
        if (mysqli_query($conexao, $sql)) {
            header("Location: categoria.php?tipoMsg=sucesso&msg=Categoria incluída com sucesso!");
        } else {
            header("Location: categoria.php?tipoMsg=erro&msg=Erro ao inserir categoria!");
        }
        exit;
    } else {
        // Verifica duplicado no update (ignora a própria)
        $check = mysqli_query($conexao, "SELECT 1 FROM categoria WHERE nome = '$nome' AND idCategoria <> $idCategoria LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            header("Location: categoria.php?tipoMsg=erro&msg=Já existe outra categoria com esse nome.#form");
            exit;
        }

        $sql = "UPDATE categoria SET nome = '$nome', descricao = '$descricao' WHERE idCategoria = $idCategoria";
        if (mysqli_query($conexao, $sql)) {
            header("Location: categoria.php?tipoMsg=sucesso&msg=Categoria atualizada com sucesso!");
        } else {
            header("Location: categoria.php?tipoMsg=erro&msg=Erro ao atualizar categoria!");
        }
        exit;
    }
}

// -------------------------------------
// Carregar para edição (GET)
// -------------------------------------
if (isset($_GET['idCategoria']) && $_GET['idCategoria'] !== '') {
    $id = (int)$_GET['idCategoria'];
    if ($id > 0) {
        $res = mysqli_query($conexao, "SELECT * FROM categoria WHERE idCategoria = $id");
        if ($res && $row = mysqli_fetch_assoc($res)) {
            $idCategoria = (int)$row['idCategoria'];
            $nome = $row['nome'] ?? "";
            $descricao = $row['descricao'] ?? "";
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
$lista = mysqli_query($conexao, "SELECT * FROM categoria $where ORDER BY nome ASC");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Categorias</title>
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="container my-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="mb-0">Categorias</h2>
            <!-- Só reseta o form e rola pra ele -->
            <a href="categoria.php#form" class="btn btn-success">+ Nova</a>
        </div>

        <div class="row g-3">
            <!-- Formulário -->
            <div class="col-lg-5">
                <div class="card" id="form">
                    <div class="card-header">
                        <?= $idCategoria ? "Editar categoria #$idCategoria" : "Cadastro de categoria" ?></div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="idCategoria" value="<?= (int)$idCategoria ?>">

                            <div class="col-12">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>"
                                    class="form-control" placeholder="Ex.: Bolos" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Descrição</label>
                                <input type="text" name="descricao" value="<?= htmlspecialchars($descricao) ?>"
                                    class="form-control" placeholder="Opcional">
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary" type="submit">Salvar</button>
                                <a class="btn btn-outline-secondary" href="categoria.php#form">Limpar</a>
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
                            <span>Lista de Categorias</span>
                            <form class="d-flex" method="get" action="categoria.php">
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
                                        <th style="width:10%">ID</th>
                                        <th style="width:30%">Nome</th>
                                        <th>Descrição</th>
                                        <th style="width:18%" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($lista && mysqli_num_rows($lista) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($lista)): ?>
                                            <tr>
                                                <td><?= (int)$row['idCategoria'] ?></td>
                                                <td><strong><?= htmlspecialchars($row['nome']) ?></strong></td>
                                                <td class="text-muted"><?= htmlspecialchars($row['descricao']) ?></td>
                                                <td class="text-center">
                                                    <a href="?idCategoria=<?= (int)$row['idCategoria'] ?>#form"
                                                        class="btn btn-sm btn-primary">Editar</a>
                                                    <a href="?acao=excluir&idCategoria=<?= (int)$row['idCategoria'] ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Excluir a categoria <?= htmlspecialchars($row['nome']) ?>?');">
                                                        Excluir
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted p-4">Nenhuma categoria encontrada.
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