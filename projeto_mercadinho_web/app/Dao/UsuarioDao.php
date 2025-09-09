<?php declare(strict_types=1);
namespace App\DAO;

use App\Models\Usuario;
use PDO;

final class UsuarioDAO
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?Usuario
    {
        $st = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :e LIMIT 1');
        $st->execute([':e'=>$email]);
        /** @var array{id:int|string,nome:string,email:string,senha_hash:string,perfil:string,ativo:int|string}|false $r */
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if ($r === false) { return null; }
        return new Usuario(
            (int)$r['id'], (string)$r['nome'], (string)$r['email'],
            (string)$r['senha_hash'], (string)$r['perfil'], (int)$r['ativo']
        );
    }

    public function create(string $nome, string $email, string $senhaHash, string $perfil='cliente'): int
    {
        $st = $this->pdo->prepare('INSERT INTO usuario (nome,email,senha_hash,perfil,ativo) VALUES (:n,:e,:s,:p,1)');
        $st->execute([':n'=>$nome, ':e'=>$email, ':s'=>$senhaHash, ':p'=>$perfil]);
        return (int)$this->pdo->lastInsertId();
    }
}
