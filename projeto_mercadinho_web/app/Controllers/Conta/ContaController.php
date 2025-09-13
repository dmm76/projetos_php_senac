<?php

declare(strict_types=1);

namespace App\Controllers\Conta;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Url;
use App\DAO\Database;
use App\Core\Csrf;
use PDO;

final class ContaController extends BaseContaController
{
    public function dashboard(): void
    {
        $this->render('conta/dashboard', ['user' => Auth::user()]);
    }

    public function pedidos(): void
    {
        $this->render('conta/pedidos');
    }

    public function dados(): void
    {
        $pdo = Database::getConnection();
        $u = Auth::user();

        // pega info do cliente (telefone, cpf, nascimento se quiser exibir)
        $cliente = [];
        $clienteId = Auth::clienteId(); // já cria se não existir
        if ($clienteId) {
            $st = $pdo->prepare('SELECT telefone, cpf, nascimento FROM cliente WHERE id = ?');
            $st->execute([$clienteId]);
            $cliente = $st->fetch(PDO::FETCH_ASSOC) ?: [];
        }

        $this->render('conta/dados', [
            'user'        => $u,
            'cliente'     => $cliente,
            'perfilAction' => Url::to('/conta/dados/perfil'),
            'senhaAction' => Url::to('/conta/dados/senha'),
        ]);
    }

    private function idFromRequest(): int
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            Flash::set('error', 'ID inválido.');
            $this->redirect('/conta/enderecos');
            exit;
        }
        return $id;
    }

    public function enderecos(): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        $stmt = $pdo->prepare(
            'SELECT id, rotulo, nome, cep, logradouro, numero, complemento, bairro, cidade, uf, principal
             FROM endereco
             WHERE cliente_id = ?
             ORDER BY principal DESC, id DESC'
        );
        $stmt->execute([$clienteId]);
        $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $this->render('conta/enderecos', compact('enderecos'));
    }

    public function novoEndereco(): void
    {
        $this->render('conta/enderecos_form', [
            'isEdit'    => false,
            'endereco'  => [],
            // passe o PATH cru; o view aplica Url::to(...)
            'actionUrl' => '/conta/enderecos/novo',
        ]);
    }

    public function criarEndereco(): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        $in = $this->sanitize($_POST);
        if (!$this->validateCsrf($_POST)) return;

        [$ok, $data, $errors] = $this->validateEndereco($in);
        if (!$ok) {
            Flash::set('error', 'Verifique os campos destacados.');
            $this->render('conta/enderecos_form', [
                'isEdit'    => false,
                'endereco'  => $in,
                'errors'    => $errors,
                'actionUrl' => '/conta/enderecos/novo',
            ]);
            return;
        }

        $pdo->beginTransaction();
        try {
            if ($data['principal'] === 1) {
                $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')
                    ->execute([$clienteId]);
            }

            $pdo->prepare('INSERT INTO endereco
                (cliente_id, rotulo, nome, cep, logradouro, numero, complemento, bairro, cidade, uf, principal)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([
                    $clienteId,
                    $data['rotulo'],
                    $data['nome'],
                    $data['cep'],
                    $data['logradouro'],
                    $data['numero'],
                    $data['complemento'],
                    $data['bairro'],
                    $data['cidade'],
                    $data['uf'],
                    $data['principal'],
                ]);

            $pdo->commit();
            Flash::set('success', 'Endereço cadastrado com sucesso.');
            $this->redirect('/conta/enderecos');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Erro ao salvar endereço.');
            $this->render('conta/enderecos_form', [
                'isEdit'    => false,
                'endereco'  => $in,
                'errors'    => ['Falha interna ao salvar.'],
                'actionUrl' => '/conta/enderecos/novo',
            ]);
        }
    }

    public function editarEnderecoQuery(): void
    {
        $this->editarEndereco($this->idFromRequest());
    }

    public function atualizarEnderecoQuery(): void
    {
        $this->atualizarEndereco($this->idFromRequest());
    }

    public function excluirEnderecoQuery(): void
    {
        $this->excluirEndereco($this->idFromRequest());
    }

    public function definirPrincipalQuery(): void
    {
        $this->definirPrincipal($this->idFromRequest());
    }

    public function editarEndereco(int $id): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        $stmt = $pdo->prepare('SELECT * FROM endereco WHERE id = ? AND cliente_id = ? LIMIT 1');
        $stmt->execute([$id, $clienteId]);
        $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$endereco) {
            Flash::set('error', 'Endereço não encontrado.');
            $this->redirect('/conta/enderecos');
        }

        $this->render('conta/enderecos_form', [
            'isEdit'    => true,
            'endereco'  => $endereco,
            'actionUrl' => '/conta/enderecos/editar', // <-- FIXO
        ]);
    }

    public function atualizarEndereco(int $id): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        $in = $this->sanitize($_POST);
        if (!$this->validateCsrf($_POST)) return;

        [$ok, $data, $errors] = $this->validateEndereco($in);
        if (!$ok) {
            Flash::set('error', 'Verifique os campos destacados.');
            $in['id'] = $id;
            $this->render('conta/enderecos_form', [
                'isEdit'    => true,
                'endereco'  => $in,
                'errors'    => $errors,
                'actionUrl' => '/conta/enderecos/editar',
            ]);
            return;
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT 1 FROM endereco WHERE id = ? AND cliente_id = ?');
            $stmt->execute([$id, $clienteId]);
            if (!$stmt->fetchColumn()) {
                throw new \RuntimeException('Endereço inválido.');
            }

            if ($data['principal'] === 1) {
                $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')
                    ->execute([$clienteId]);
            }

            $pdo->prepare('UPDATE endereco SET
                    rotulo = ?, nome = ?, cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, principal = ?
                WHERE id = ? AND cliente_id = ?')
                ->execute([
                    $data['rotulo'],
                    $data['nome'],
                    $data['cep'],
                    $data['logradouro'],
                    $data['numero'],
                    $data['complemento'],
                    $data['bairro'],
                    $data['cidade'],
                    $data['uf'],
                    $data['principal'],
                    $id,
                    $clienteId
                ]);

            $pdo->commit();
            Flash::set('success', 'Endereço atualizado com sucesso.');
            $this->redirect('/conta/enderecos');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Erro ao atualizar endereço.');
            $this->redirect('/conta/enderecos');
        }
    }

    public function excluirEndereco(int $id): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        if (!$this->validateCsrf($_POST)) return;

        $stmt = $pdo->prepare('DELETE FROM endereco WHERE id = ? AND cliente_id = ?');
        $ok = $stmt->execute([$id, $clienteId]);

        Flash::set($ok ? 'success' : 'error', $ok ? 'Endereço excluído.' : 'Não foi possível excluir.');
        $this->redirect('/conta/enderecos');
    }

    public function definirPrincipal(int $id): void
    {
        $pdo = Database::getConnection();
        $clienteId = $this->clienteIdOrFail();

        if (!$this->validateCsrf($_POST)) return;

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT 1 FROM endereco WHERE id = ? AND cliente_id = ?');
            $stmt->execute([$id, $clienteId]);
            if (!$stmt->fetchColumn()) {
                throw new \RuntimeException('Endereço inválido.');
            }

            $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')->execute([$clienteId]);
            $pdo->prepare('UPDATE endereco SET principal = 1 WHERE id = ? AND cliente_id = ?')->execute([$id, $clienteId]);

            $pdo->commit();
            Flash::set('success', 'Endereço definido como principal.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Não foi possível definir como principal.');
        }
        $this->redirect('/conta/enderecos');
    }

    /* ================= Helpers ================= */

    private function clienteIdOrFail(): int
    {
        $id = Auth::clienteId();
        if ($id !== null) return $id;
        Flash::set('error', 'Seu cadastro de cliente não foi localizado.');
        $this->redirect('/conta/dados');
        exit;
    }

    /** @param array<string,mixed> $src */
    private function sanitize(array $src): array
    {
        $f = fn($k, $d = '') => trim((string)($src[$k] ?? $d));
        return [
            'rotulo'      => $f('rotulo'),
            'nome'        => $f('nome'),
            'cep'         => strtoupper($f('cep')),
            'logradouro'  => $f('logradouro'),
            'numero'      => $f('numero'),
            'complemento' => $f('complemento'),
            'bairro'      => $f('bairro'),
            'cidade'      => $f('cidade'),
            'uf'          => strtoupper($f('uf')),
            'principal'   => isset($src['principal']) ? 1 : 0,
        ];
    }

    /** @return array{0:bool,1:array<string,mixed>,2:array<int,string>} */
    private function validateEndereco(array $in): array
    {
        $errors = [];
        $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

        if ($in['nome'] === '')        $errors[] = 'Nome é obrigatório.';
        if ($in['cep'] === '')         $errors[] = 'CEP é obrigatório.';
        if ($in['logradouro'] === '')  $errors[] = 'Logradouro é obrigatório.';
        if ($in['numero'] === '')      $errors[] = 'Número é obrigatório.';
        if ($in['bairro'] === '')      $errors[] = 'Bairro é obrigatório.';
        if ($in['cidade'] === '')      $errors[] = 'Cidade é obrigatória.';
        if (!in_array($in['uf'], $ufs, true)) $errors[] = 'UF inválida.';
        if ($in['cep'] !== '' && !preg_match('/^\d{5}-?\d{3}$/', $in['cep'])) {
            $errors[] = 'CEP inválido (use 00000-000).';
        }
        $in['cep'] = preg_replace('/^(\d{5})-?(\d{3})$/', '$1-$2', $in['cep']);

        return [empty($errors), $in, $errors];
    }

    private function validateCsrf(array $src, string $fallbackPath = '/conta/enderecos'): bool
    {
        $token = isset($src['csrf']) ? (string)$src['csrf'] : null;
        if (!\App\Core\Csrf::check($token)) {
            Flash::set('error', 'Sessão expirada. Recarregue a página e tente novamente.');
            $this->redirect($fallbackPath);
            return false;
        }
        return true;
    }

    /** POST: salvar nome + telefone */
    public function salvarPerfil(): void
    {
        if (!$this->validateCsrf($_POST, '/conta/dados')) return;

        $pdo = Database::getConnection();
        $u = Auth::user();
        $clienteId = Auth::clienteId(); // pode ser null se não for cliente

        $nome = trim((string)($_POST['nome'] ?? ''));
        $tel  = trim((string)($_POST['telefone'] ?? ''));

        $errs = [];
        if ($nome === '') $errs[] = 'Informe seu nome.';
        if ($tel !== '' && !preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $tel)) {
            $errs[] = 'Telefone inválido.';
        }
        if ($errs) {
            Flash::set('error', implode(' ', $errs));
            $this->redirect('/conta/dados');
            return;
        }

        $pdo->beginTransaction();
        try {
            // atualiza nome do usuário
            $pdo->prepare('UPDATE usuario SET nome = ? WHERE id = ?')->execute([$nome, $u['id']]);

            // atualiza telefone do cliente (se houver registro cliente)
            if ($clienteId) {
                $pdo->prepare('UPDATE cliente SET telefone = ? WHERE id = ?')
                    ->execute([$tel !== '' ? $tel : null, $clienteId]);
            }

            $pdo->commit();
            // reflete no session
            $_SESSION['user']['nome'] = $nome;
            Flash::set('success', 'Dados atualizados.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Não foi possível salvar seus dados.');
        }
        $this->redirect('/conta/dados');
    }

    /** POST: alterar senha */
    public function atualizarSenha(): void
    {
        if (!$this->validateCsrf($_POST, '/conta/dados')) return;

        $pdo = Database::getConnection();
        $u = Auth::user();

        $atual = (string)($_POST['senha_atual'] ?? '');
        $s1    = (string)($_POST['senha'] ?? '');
        $s2    = (string)($_POST['senha2'] ?? '');

        if ($s1 !== $s2) {
            Flash::set('error', 'As senhas não conferem.');
            $this->redirect('/conta/dados');
            return;
        }
        if (strlen($s1) < 6) {
            Flash::set('error', 'A nova senha deve ter ao menos 6 caracteres.');
            $this->redirect('/conta/dados');
            return;
        }

        $st = $pdo->prepare('SELECT senha_hash FROM usuario WHERE id = ?');
        $st->execute([$u['id']]);
        $hash = (string)$st->fetchColumn();

        if (!$hash || !password_verify($atual, $hash)) {
            Flash::set('error', 'Senha atual incorreta.');
            $this->redirect('/conta/dados');
            return;
        }

        $ok = $pdo->prepare('UPDATE usuario SET senha_hash = ? WHERE id = ?')
            ->execute([password_hash($s1, PASSWORD_DEFAULT), $u['id']]);

        Flash::set($ok ? 'success' : 'error', $ok ? 'Senha atualizada.' : 'Não foi possível atualizar a senha.');
        $this->redirect('/conta/dados');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . Url::to($path), true, 302);
        exit;
    }
}
