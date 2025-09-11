<?php declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Url;
use App\DAO\Database;
use App\DAO\UsuarioDAO;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->render('site/auth/login', ['title' => 'Entrar']);
    }

    public function login(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Token inválido.');
            header('Location: ' . Url::to('/login')); exit;
        }

        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');

        if ($email === '' || $pass === '') {
            Flash::set('error', 'Informe e-mail e senha.');
            header('Location: ' . Url::to('/login')); exit;
        }

        if (Auth::attempt($email, $pass)) {
            header('Location: ' . Url::to('/admin')); exit;
        }

        Flash::set('error', 'Credenciais inválidas.');
        header('Location: ' . Url::to('/login')); exit;
    }

    public function showRegister(): void
    {
        $this->render('site/auth/registrar', ['title' => 'Criar conta']);
    }

    public function register(): void
    {
        if (!Csrf::check($_POST['csrf'] ?? null)) {
            Flash::set('error', 'Token inválido.');
            header('Location: ' . Url::to('/registrar')); exit;
        }

        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');
        $pass2 = (string)($_POST['password2'] ?? '');

        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '' || $pass !== $pass2) {
            Flash::set('error', 'Dados inválidos.');
            header('Location: ' . Url::to('/registrar')); exit;
        }

        $dao = new UsuarioDAO(Database::getConnection());
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $dao->create($nome, $email, $hash, 'cliente');

        Flash::set('success', 'Conta criada! Faça login.');
        header('Location: ' . Url::to('/login')); exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . Url::to('/')); exit;
    }
}
