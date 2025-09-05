<?php
require_once 'includes/auth.php';
require_once 'includes/acl.php';
// Se quiser exigir login aqui (recomendado):
//requireLogin();

include_once 'includes/conexao.php';
include_once 'includes/classes/Usuario.php';

$bd      = new Database();
$Usuario = new Usuario($bd);

$usuarioId = (int)($_SESSION['id_usuario'] ?? 0);
$nivelSess = strtolower($_SESSION['nivel'] ?? 'comum');

if ($usuarioId <= 0) {
    header("Location: login.php?msg=Faça login para continuar");
    exit;
}

$msg_ok = $msg_er = "";

/** Carrega dados atuais do usuário */
$meu = $Usuario->buscaID($usuarioId);
if (!$meu) {
    // Sessão inválida? Força logout
    header("Location: logout.php");
    exit;
}

/** Processamento dos formulários */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'salvar_perfil') {
        // Lê dados do POST
        $nome        = trim($_POST['nome'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $apartamento = trim($_POST['apartamento'] ?? '');

        if ($nome === '' || $email === '') {
            $msg_er = "Nome e e-mail são obrigatórios.";
        } else {
            // (opcional) checagem de e-mail duplicado
            // Se seu Database tiver escape_string:
            // $emailEsc = $bd->escape_string($email);
            // $rs = $bd->query("SELECT id FROM usuario WHERE email='{$emailEsc}' AND id <> '{$usuarioId}' LIMIT 1");
            // if ($rs && $rs->num_rows > 0) { $msg_er = "Este e-mail já está em uso."; }
            // else { ... }

            // Atualiza pelo método da classe
            $ok = $Usuario->atualizarPerfil($usuarioId, $nome, $email, $apartamento);
            if ($ok) {
                // Atualiza sessão para refletir no menu
                $_SESSION['nome_usuario']  = $nome;
                $_SESSION['email_usuario'] = $email;
                $msg_ok = "Perfil atualizado com sucesso!";

                // Recarrega os dados para preencher os inputs
                $meu = $Usuario->buscaID($usuarioId);
            } else {
                $msg_er = "Erro ao atualizar o perfil.";
            }
        }
    }

    if ($acao === 'trocar_senha') {
        $senha_atual = trim($_POST['senha_atual'] ?? '');
        $senha_nova  = trim($_POST['senha_nova'] ?? '');
        $senha_conf  = trim($_POST['senha_conf'] ?? '');

        if ($senha_atual === '' || $senha_nova === '' || $senha_conf === '') {
            $msg_er = "Preencha todos os campos de senha.";
        } elseif ($senha_nova !== $senha_conf) {
            $msg_er = "A nova senha e a confirmação não conferem.";
        } else {
            $ok = $Usuario->alterarSenha($usuarioId, $senha_atual, $senha_nova);
            if ($ok) {
                $msg_ok = "Senha alterada com sucesso!";
            } else {
                $msg_er = "Senha atual incorreta ou erro ao alterar.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-br" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Meu Perfil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 6px 16px rgba(0, 0, 0, .06);
            border: 0;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <?php include_once 'includes/menu.php'; ?>

    <div class="flex-grow-1 p-4" style="width:100%">
        <div class="container-fluid">
            <h2 class="mb-4">Meu Perfil</h2>

            <?php if ($msg_ok): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($msg_ok); ?></div>
            <?php endif; ?>
            <?php if ($msg_er): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($msg_er); ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Dados pessoais -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Dados Pessoais</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="acao" value="salvar_perfil">
                                <div class="mb-3">
                                    <label class="form-label" for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                        value="<?php echo htmlspecialchars($meu['nome'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="apartamento">Apartamento</label>
                                    <input type="text" class="form-control" id="apartamento" name="apartamento"
                                        placeholder="Ex.: B102"
                                        value="<?php echo htmlspecialchars($meu['apartamento'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo htmlspecialchars($meu['email'] ?? ''); ?>" required>
                                </div>
                                <button class="btn btn-primary" type="submit">Salvar alterações</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Alterar senha -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Alterar Senha</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="acao" value="trocar_senha">
                                <div class="mb-3">
                                    <label class="form-label" for="senha_atual">Senha atual</label>
                                    <input type="password" class="form-control" id="senha_atual" name="senha_atual"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="senha_nova">Nova senha</label>
                                    <input type="password" class="form-control" id="senha_nova" name="senha_nova"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="senha_conf">Confirmar nova senha</label>
                                    <input type="password" class="form-control" id="senha_conf" name="senha_conf"
                                        required>
                                </div>
                                <button class="btn btn-outline-primary" type="submit">Alterar senha</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center small text-muted mt-4">
                Último acesso: <?php echo date('d/m/Y H:i'); ?>
            </div>
        </div>
    </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>