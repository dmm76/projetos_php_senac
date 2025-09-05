<?php
require_once 'includes/auth.php';
require_once 'includes/acl.php';
// requireLogin();
// requireRole(['admin']); // só admin edita

include_once("includes/classes/Reserva.php");

$bd      = new Database();
$reserva = new Reserva($bd);

$idReserva = (int)($_GET['idReserva'] ?? 0);
if ($idReserva <= 0) {
    header("Location: emprestimos.php?msg=ID de reserva inválido");
    exit;
}

/** Carrega dados da reserva */
$dados = $reserva->buscaID($idReserva);
if (!$dados) {
    header("Location: emprestimos.php?msg=Reserva não encontrada");
    exit;
}

/** Opcional: nomes para exibir na tela */
$nomeFerramenta = '';
$nomeUsuario    = '';
if (!empty($dados['id_ferramenta'])) {
    if ($rs = $bd->query("SELECT nome FROM ferramenta WHERE id=" . (int)$dados['id_ferramenta'] . " LIMIT 1")) {
        $r = $rs->fetch_assoc();
        $nomeFerramenta = $r['nome'] ?? '';
    }
}
if (!empty($dados['id_usuario'])) {
    if ($rs = $bd->query("SELECT nome,email FROM usuario WHERE id=" . (int)$dados['id_usuario'] . " LIMIT 1")) {
        $r = $rs->fetch_assoc();
        // mostra nome, senão email
        $nomeUsuario = $r['nome'] ?: ($r['email'] ?? '');
    }
}

$msg_ok = $msg_er = "";

/** Salvar */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // status vindo do form
    $status = $_POST['status'] ?? 'ativa';
    $permitidos = ['ativa', 'atendida', 'cancelada'];
    if (!in_array($status, $permitidos, true)) {
        $status = 'ativa';
    }

    // IMPORTANTÍSSIMO: enviar os IDs atuais como hidden e reaproveitar aqui
    $id_ferramenta = (int)($_POST['id_ferramenta'] ?? $dados['id_ferramenta']);
    $id_usuario    = (int)($_POST['id_usuario'] ?? $dados['id_usuario']);

    $ok = $reserva->inserir([
        'idReserva'     => $idReserva,
        'id_ferramenta' => $id_ferramenta,
        'id_usuario'    => $id_usuario,
        'status'        => $status,
    ]);

    if ($ok) {
        header("Location: emprestimos.php?msg=Reserva atualizada com sucesso!");
        exit;
    } else {
        $msg_er = "Erro ao atualizar a reserva.";
    }
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once("includes/menu.php"); ?>

    <div class="flex-grow-1 p-4">
        <div class="container-fluid" style="max-width: 720px;">
            <h2 class="mb-3">Editar Reserva #<?php echo (int)$dados['id']; ?></h2>

            <?php if ($msg_ok): ?><div class="alert alert-success"><?php echo htmlspecialchars($msg_ok); ?></div>
            <?php endif; ?>
            <?php if ($msg_er): ?><div class="alert alert-danger"><?php echo htmlspecialchars($msg_er); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <!-- IDs necessários para o UPDATE da sua classe -->
                        <input type="hidden" name="id_ferramenta" value="<?php echo (int)$dados['id_ferramenta']; ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo (int)$dados['id_usuario']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Ferramenta</label>
                            <input type="text" class="form-control"
                                value="<?php echo htmlspecialchars($nomeFerramenta); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <input type="text" class="form-control"
                                value="<?php echo htmlspecialchars($nomeUsuario); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="ativa" <?php echo $dados['status'] === 'ativa' ? 'selected' : ''; ?>>
                                    Ativa
                                </option>
                                <option value="atendida"
                                    <?php echo $dados['status'] === 'atendida' ? 'selected' : ''; ?>>
                                    Atendida</option>
                                <option value="cancelada"
                                    <?php echo $dados['status'] === 'cancelada' ? 'selected' : ''; ?>>
                                    Cancelada</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                            <a href="emprestimos.php" class="btn btn-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>