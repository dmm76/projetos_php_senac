<?php declare(strict_types=1);

use App\Controllers\Site\HomeController;
use App\Controllers\Site\AuthController;
use App\Controllers\Site\PreviewController;
use App\DAO\Database;

$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/registrar', [AuthController::class, 'showRegister']);
$router->post('/registrar', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/preview/home', [PreviewController::class, 'home']);
$router->get('/health/db', function (): void {
    try { Database::getConnection(); echo 'DB OK'; }
    catch (\Throwable $e) { http_response_code(500); echo 'DB FAIL: ' . $e->getMessage(); }
});
