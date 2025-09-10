<?php

declare(strict_types=1);

namespace App\Controllers\Site;

final class ContatoController
{
    public function show(): void
    {
        $title = 'Contato';
        require __DIR__ . '/../../Views/site/home/contato.php';
    }
}
