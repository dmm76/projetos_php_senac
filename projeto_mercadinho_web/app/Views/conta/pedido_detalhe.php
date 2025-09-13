<?php

use App\Core\Url;

/** @var array{id:int,codigo_externo:?string,status:string,subtotal:float,frete:float,desconto:float,total:float,criado_em:string} $pedido */
/** @var array<int,array{id:int,produto_id:int,nome:string,quantidade:int,preco:float,subtotal:float}> $itens */

$pedido = $pedido ?? [];
$itens  = $itens ?? [];

function status_badge(string $s): string
{
    $map = ['pago' => 'success', 'enviado' => 'primary', 'pendente' => 'warning', 'cancelado' => 'secondary'];
    $variant = $map[strtolower($s)] ?? 'light';
    return "<span class=\"badge bg-{$variant} text-uppercase\">" . htmlspecialchars($s) . "</span>";
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Pedido #<?= (int)$pedido['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= Url::to('/assets/site/css/style.css') ?>" />
    <style>
        .sidebar-sticky {
            position: sticky;
            top: 1rem
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column wrapper">

        <?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

        <main class="flex-fill">
            <div class="container py-3">
                <div class="row g-3">
                    <div class="col-12 col-lg-3">
                        <?php require dirname(__DIR__) . '/partials/conta-sidebar.php'; ?>
                    </div>

                    <div class="col-12 col-lg-9">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h1 class="h4 mb-0">
                                Pedido
                                <?= $pedido['codigo'] ? '<code>' . htmlspecialchars($pedido['codigo']) . '</code>' : '#' . (int)$pedido['id'] ?>
                            </h1>
                            <a href="<?= Url::to('/conta/pedidos') ?>"
                                class="btn btn-outline-secondary btn-sm">Voltar</a>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-lg-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div><strong>Status:</strong> <?= status_badge($pedido['status']) ?></div>
                                        <div><strong>Data:</strong>
                                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['criado_em']))) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between"><span>Subtotal</span><span>R$
                                                <?= number_format((float)$pedido['subtotal'], 2, ',', '.') ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between"><span>Frete</span><span>R$
                                                <?= number_format((float)$pedido['frete'], 2, ',', '.') ?></span></div>
                                        <div class="d-flex justify-content-between"><span>Desconto</span><span>R$
                                                <?= number_format((float)$pedido['desconto'], 2, ',', '.') ?></span>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between fw-semibold">
                                            <span>Total</span><span>R$
                                                <?= number_format((float)$pedido['total'], 2, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-white"><strong>Itens</strong></div>
                            <div class="card-body p-0">
                                <?php if (!empty($itens)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Produto</th>
                                                    <th class="text-end">Qtd</th>
                                                    <th class="text-end">Preço</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($itens as $i): ?>
                                                    <tr>
                                                        <td><?= (int)$i['produto_id'] ?></td>
                                                        <td><?= htmlspecialchars($i['nome']) ?></td>
                                                        <td class="text-end"><?= (int)$i['quantidade'] ?></td>
                                                        <td class="text-end">R$
                                                            <?= number_format((float)$i['preco'], 2, ',', '.') ?></td>
                                                        <td class="text-end">R$
                                                            <?= number_format((float)$i['subtotal'], 2, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="p-3">Sem itens para este pedido.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= Url::to('/assets/site/js/script.js') ?>"></script>
</body>

</html>