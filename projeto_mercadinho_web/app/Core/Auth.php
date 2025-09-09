<?php declare(strict_types=1);

namespace App\Core;

use App\DAO\Database;
use App\DAO\UsuarioDAO;
use App\Models\Usuario;

final class Auth
{
    public static function user(): ?Usuario
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() instanceof Usuario;
    }

    public static function attempt(string $email, string $password): bool
    {
        $dao = new UsuarioDAO(Database::getConnection());
        $u = $dao->findByEmail($email);
        if (!$u || $u->ativo !== 1) return false;

        if (password_verify($password, $u->senhaHash)) {
            $_SESSION['user'] = $u;
            session_regenerate_id(true);
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }

    public static function requireAdmin(): void
    {
        $u = self::user();
        if (!$u || !in_array($u->perfil, ['admin','gerente'], true)) {
            Flash::set('error', 'Acesso restrito.');
            header('Location: ' . Url::to('/login'));
            exit;
        }
    }
}
