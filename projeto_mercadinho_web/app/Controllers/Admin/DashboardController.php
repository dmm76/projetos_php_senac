<?php declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Url;
use App\DAO\Database;

final class DashboardController extends BaseAdminController
{
    public function index(): void
    {
        $pdo = Database::getConnection();

        $produtos = (int)($pdo->query('SELECT COUNT(*) FROM produto')->fetchColumn() ?: 0);
        $estoqueBaixo = (int)($pdo->query("
            SELECT COUNT(*) 
            FROM estoque e 
            JOIN produto p ON p.id = e.produto_id 
            WHERE e.minimo > 0 AND e.quantidade < e.minimo
        ")->fetchColumn() ?: 0);

        $this->render('admin/dashboard/index', [
            'title' => 'Dashboard Admin',
            'metrics' => [
                'produtos' => $produtos,
                'estoque_baixo' => $estoqueBaixo,
            ],
            'links' => [
                'categorias' => Url::to('/admin/categorias'),
                'marcas'     => Url::to('/admin/marcas'),
                'unidades'   => Url::to('/admin/unidades'),
                'produtos'   => Url::to('/admin/produtos'),
            ],
        ]);
    }
}
