<?php
session_start();

/* Evita cache (senão o navegador mostra página antiga ao voltar) */
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

/* Se não tem cookie de login, manda para login */
if (empty($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_COOKIE['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Mismatch - Inicial</title>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-3">MisMatch</h3>

                        <div class="alert alert-success">
                            Você está logado como
                            <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></strong>.
                        </div>

                        <form action="logout.php" method="post">
                            <button type="submit" class="btn btn-danger">Sair</button>
                            <a href="index.php" class="btn btn-outline-secondary ms-2">Ir para Home</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>