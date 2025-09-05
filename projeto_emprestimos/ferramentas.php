<?php
include_once("includes/classes/ferramenta.php");   // usa sua classe (com salvarComRegra/disponibilizar)
include_once("includes/classes/Emprestimo.php");   // se precisar em outros pontos
include_once("includes/classes/Reserva.php");      // se precisar em outros pontos
require_once 'includes/auth.php';
require_once 'includes/acl.php';

requireLogin();
requireRole(['admin']); // só admin acessa

$bd         = new Database();
$ferramenta = new Ferramenta($bd);

$msg = $_GET['msg'] ?? null;

/** ===== Ações por GET (ex.: disponibilizar) ===== */
if (isset($_GET['acao']) && $_GET['acao'] === 'disponibilizar') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        $res = $ferramenta->disponibilizar($id);  // usa método da classe
        header("Location: ferramentas.php?msg=" . urlencode($res['msg']));
        exit;
    }
}

/** ===== Carregar registro se edição ===== */
if (isset($_GET['idferramenta'])) {
    $idferramenta = (int)$_GET['idferramenta'];
    $dados        = $ferramenta->buscaID($idferramenta);

    $nome      = $dados['nome']   ?? '';
    $descricao = $dados['descricao'] ?? '';
    $status    = $dados['status'] ?? 'disponível'; // com acento (igual ao ENUM)
    $estado    = $dados['estado'] ?? '';
} else {
    $idferramenta = 0;
    $nome = $descricao = $estado = '';
    $status = 'disponível';
}

/** ===== Salvar (POST) — tudo via Ferramenta::salvarComRegra ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'idferramenta' => (int)($_POST['idferramenta'] ?? 0),
        'nome'         => trim($_POST['nome'] ?? ''),
        'descricao'    => trim($_POST['descricao'] ?? ''),
        'status'       => $_POST['status'] ?? 'disponível',  // com acento
        'estado'       => trim($_POST['estado'] ?? ''),
    ];

    $res = $ferramenta->salvarComRegra($data);
    header("Location: ferramentas.php?msg=" . urlencode($res['msg']));
    exit;
}

/** ===== Listagem ===== */
$ferramentas = $ferramenta->listar();

/** select de status — MUST bater com o ENUM do banco (com acento) */
$statusOptions = [
    'disponível' => 'Disponível',
    'reservada'  => 'Reservada',
    'emprestada' => 'Emprestada',
];
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ferramentas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="sidebars.css" rel="stylesheet" />
    <style>
        .badge-disponivel {
            background: #198754;
        }

        .badge-reservada {
            background: #6c757d;
        }

        .badge-emprestada {
            background: #dc3545;
        }
    </style>
</head>

<body>

    <?php include_once("includes/menu.php"); ?>

    <div class="flex-grow-1 p-4">
        <h2>Cadastro e Gestão de Ferramentas</h2>

        <?php if ($msg): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <!-- Formulário -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>Cadastro de Ferramentas</h3>
                <form action="" method="POST">
                    <input type="hidden" name="idferramenta" value="<?php echo (int)$idferramenta; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                value="<?php echo htmlspecialchars($nome); ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao"
                                value="<?php echo htmlspecialchars($descricao); ?>">
                        </div>

                        <div class="col-md-2">
                            <label for="estado" class="form-label">Estado de Conservação</label>
                            <input type="text" class="form-control" id="estado" name="estado"
                                value="<?php echo htmlspecialchars($estado); ?>"
                                placeholder="Ex.: Bom, Ótimo, Precisa reparo">
                        </div>

                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <?php foreach ($statusOptions as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php echo ($status === $val ? 'selected' : ''); ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                O sistema ajusta automaticamente conforme empréstimos/reservas ativas.
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mt-4 w-100">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela -->
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">Ferramentas cadastradas</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nome</th>
                                <th class="text-center">Descrição</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ferramentas as $f): ?>
                                <tr>
                                    <td class="text-center"><?php echo (int)$f['id']; ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($f['nome']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($f['descricao']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($f['estado']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        // classe visual (sem acento só no nome da classe)
                                        $badge = $f['status'] === 'emprestada' ? 'badge-emprestada'
                                            : ($f['status'] === 'reservada' ? 'badge-reservada' : 'badge-disponivel');
                                        ?>
                                        <span class="badge <?php echo $badge; ?>">
                                            <?php echo htmlspecialchars($f['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-warning"
                                            href="?idferramenta=<?php echo (int)$f['id']; ?>">Editar</a>
                                        &nbsp;|&nbsp;
                                        <a class="btn btn-danger" onclick="return confirm('Deseja realmente excluir?');"
                                            href="excluir.ferramenta.php?idferramenta=<?php echo (int)$f['id']; ?>">
                                            Excluir
                                        </a>
                                        <?php if ($f['status'] !== 'disponível'): /* com acento */ ?>
                                            &nbsp;|&nbsp;
                                            <a class="btn btn-success"
                                                href="?acao=disponibilizar&id=<?php echo (int)$f['id']; ?>"
                                                onclick="return confirm('Marcar como disponível? Isso só será efetivo se não houver empréstimo/reserva ativa.');">
                                                Disponibilizar
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($ferramentas)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Nenhuma ferramenta cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sidebars.js"></script>
</body>

</html>