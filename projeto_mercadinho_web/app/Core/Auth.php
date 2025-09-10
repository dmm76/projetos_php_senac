<?php declare(strict_types=1);

namespace App\Core;

use App\DAO\Database;

final class Auth
{
    /** Tenta autenticar e popular a sessão */
    public static function attempt(string $email, string $pass): bool
    {
        $pdo = Database::getConnection();

        $st = $pdo->prepare(
            "SELECT id, nome, email, senha_hash, perfil, ativo
               FROM usuario
              WHERE email = :email
              LIMIT 1"
        );
        $st->execute([':email' => $email]);
        $u = $st->fetch(\PDO::FETCH_ASSOC);

        if (!$u || (int)$u['ativo'] !== 1) return false;
        if (!password_verify($pass, (string)$u['senha_hash'])) return false;

        $_SESSION['user_id'] = (int)$u['id'];
        $_SESSION['nome']    = (string)$u['nome'];
        $_SESSION['email']   = (string)$u['email'];
        $_SESSION['perfil']  = (string)$u['perfil']; // admin/gerente/operador/cliente
        return true;
    }

    /** Desloga */
    public static function logout(): void
    {
        session_destroy();
    }

    /** Está logado? */
    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    /** Dados básicos do usuário logado */
    public static function user(): ?array
    {
        if (!self::check()) return null;
        return [
            'id'     => (int)($_SESSION['user_id'] ?? 0),
            'nome'   => (string)($_SESSION['nome']   ?? ''),
            'email'  => (string)($_SESSION['email']  ?? ''),
            'perfil' => (string)($_SESSION['perfil'] ?? ''),
        ];
    }

    /** Verifica se tem um dos perfis */
    public static function hasRole(string ...$roles): bool
    {
        if (!self::check()) return false;
        $perfil = (string)($_SESSION['perfil'] ?? '');
        return in_array($perfil, $roles, true);
    }

    /** Exige login (senão, redireciona) */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            Flash::set('error', 'Você precisa entrar para acessar esta área.');
            header('Location: ' . Url::to('/login')); exit;
        }
    }

    /** Exige perfil admin (ajuste roles se quiser permitir gerente/operador) */
    public static function requireAdmin(): void
    {
        if (!self::hasRole('admin')) {
            Flash::set('error', 'Acesso restrito a administradores.');
            header('Location: ' . Url::to('/login')); exit;
        }
    }
}
