<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('site/home/index', [
            'title' => 'Mercadinho Borba Gato',
        ]);
    }
}
