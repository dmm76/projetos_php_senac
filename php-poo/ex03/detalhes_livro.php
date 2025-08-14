<?php
// detalhes_livro.php
include_once("includes/classes/Livro.php");

$bd = new Database();
$livroModel = new Livro($bd);

// Pega o ID da query string
$idLivro = isset($_GET['idLivro']) ? (int)$_GET['idLivro'] : 0;

$erro = '';
$livro = null;

if ($idLivro <= 0) {
    $erro = 'ID do livro inválido ou não informado.';
} else {
    // Método buscar deve retornar um array associativo com os campos do livro
    // Ex.: ['idLivro'=>1,'titulo'=>'...','autor'=>'...','editora'=>'...','anoPublicacao'=>2024,'descricao'=>'...','capa'=>'...']
    $livro = $livroModel->buscar($idLivro);
    if (!$livro || empty($livro['idLivro'])) {
        $erro = 'Livro não encontrado.';
    }
}

// Helper: escape seguro
function e($v) { return htmlspecialchars((string)$v ?? '', ENT_QUOTES, 'UTF-8'); }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Livro<?= $livro ? ' — ' . e($livro['titulo']) : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .capa {
            max-width: 260px;
            max-height: 360px;
            object-fit: cover;
            border-radius: .5rem;
            box-shadow: 0 4px 18px rgba(0,0,0,.08);
        }
        .campo-label {
            color: #6c757d;
            font-size: .9rem;
            margin-bottom: .15rem;
        }
        .campo-valor {
            font-weight: 500;
        }
        .descricao pre {
            white-space: pre-wrap; /* quebra linha preservando parágrafos */
            margin: 0;
            font-family: inherit;
        }
    </style>
</head>
<body class="bg-light">
<?php include_once("includes/menu.php"); ?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="index.php">Início</a></li>
            <li class="breadcrumb-item"><a href="relatorio.php">Relatório de Livros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalhes do Livro</li>
        </ol>
    </nav>

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?= e($erro) ?>
        </div>
        <a href="relatorio.php" class="btn btn-secondary">← Voltar</a>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row gap-4">
                    <div>
                        <?php if (!empty($livro['capa'])): ?>
                            <img src="<?= e($livro['capa']) ?>" alt="Capa do livro <?= e($livro['titulo']) ?>" class="capa">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center capa" style="width:260px;height:360px;">
                                Sem capa
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-grow-1">
                        <h2 class="mb-2"><?= e($livro['titulo']) ?></h2>

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="campo-label">Autor</div>
                                <div class="campo-valor"><?= e($livro['autor'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="campo-label">Editora</div>
                                <div class="campo-valor"><?= e($livro['editora'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-3">
                                <div class="campo-label">Ano de Publicação</div>
                                <div class="campo-valor"><?= e($livro['anoPublicacao'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-9">
                                <div class="campo-label">ID</div>
                                <div class="campo-valor">#<?= (int)$livro['idLivro'] ?></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="descricao">
                            <div class="campo-label mb-2">Descrição</div>
                            <?php if (!empty($livro['descricao'])): ?>
                                <pre><?= e($livro['descricao']) ?></pre>
                            <?php else: ?>
                                <span class="text-muted">Nenhuma descrição cadastrada.</span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <a class="btn btn-secondary" href="relatorio_livros.php">← Voltar</a>
                            <!-- Ajuste os links abaixo conforme suas rotas de edição/remoção -->
                            <a class="btn btn-primary" href="editar_livro.php?idLivro=<?= (int)$livro['idLivro'] ?>">Editar</a>
                            <a class="btn btn-outline-danger"
                               href="remover_livro.php?idLivro=<?= (int)$livro['idLivro'] ?>"
                               onclick="return confirm('Tem certeza que deseja remover este livro?');">
                               Remover
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php /* Exemplo opcional: bloco para anexos, categorias, etc.
        <div class="card mt-4">
            <div class="card-header">Outras informações</div>
            <div class="card-body">
                ...
            </div>
        </div>
        */ ?>
    <?php endif; ?>
</div>
</body>
</html>
