<?php declare(strict_types=1);

use App\Controllers\Site\HomeController;
use App\Controllers\Site\AuthController;
use App\Controllers\Site\ContatoController;
use App\Controllers\Site\CarrinhoController;
// Painel do Cliente
use App\Controllers\Conta\ContaController;

// HOME
$router->get('/', [HomeController::class, 'index']);

// AUTH
$router->get('/login',     [AuthController::class, 'showLogin']);
$router->post('/login',    [AuthController::class, 'login']);
$router->get('/registrar', [AuthController::class, 'showRegister']);
$router->post('/registrar',[AuthController::class, 'register']);   // <- ADICIONADA
$router->get('/logout',    [AuthController::class, 'logout']);     // <- ADICIONADA

// SITE
$router->get('/contato', [ContatoController::class, 'show']); // exibe o formulário
$router->post('/contato', [ContatoController::class, 'send']); // processa o envio
$router->get('/carrinho', [CarrinhoController::class, 'index']);

// LEGADO: /preview/home -> /
$router->get('/preview/home', function (): void {
    header('Location: ' . \App\Core\Url::to('/'), true, 302);
    exit;
});

// HEALTH (opcionais em dev)
$router->get('/health/db', function (): void {
    try {
        \App\DAO\Database::getConnection();
        echo 'DB OK';
    } catch (\Throwable $e) {
        http_response_code(500);
        echo 'DB FAIL: ' . $e->getMessage();
    }
});

// (TEMPORÁRIA) checagem do autoload dos Models
$router->get('/health/autoload', function () {
    $ok = class_exists(\App\Model\Produto::class)
       && class_exists(\App\Model\Categoria::class)
       && class_exists(\App\Model\Marca::class)
       && class_exists(\App\Model\Unidade::class)
       && class_exists(\App\Model\Usuario::class);

    echo $ok ? 'AUTOLOAD OK' : 'AUTOLOAD FAIL';
});
//Painel do Cliente
$router->get('/conta',             [ContaController::class, 'dashboard']);
$router->get('/conta/pedidos',     [ContaController::class, 'pedidos']);
$router->get('/conta/dados',       [ContaController::class, 'dados']);
$router->get('/conta/enderecos',   [ContaController::class, 'enderecos']);

$router->get ('/conta/enderecos/novo',            [ContaController::class, 'novoEndereco']);
$router->post('/conta/enderecos/novo',            [ContaController::class, 'criarEndereco']);
$router->get ('/conta/enderecos/{id}/editar',     [ContaController::class, 'editarEndereco']);
$router->post('/conta/enderecos/{id}/editar',     [ContaController::class, 'atualizarEndereco']);
$router->post('/conta/enderecos/{id}/excluir',    [ContaController::class, 'excluirEndereco']);
$router->post('/conta/enderecos/{id}/principal',  [ContaController::class, 'definirPrincipal']);
