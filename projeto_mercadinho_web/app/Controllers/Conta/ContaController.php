<?php

declare(strict_types=1);

namespace App\Controllers\Conta;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Url;      // <-- importante
use App\DAO\Database;
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
        $this->render('conta/dados', ['user' => Auth::user()]);
    }

    public function enderecos(): void
    {
        $pdo = Database::getConnection();
        $u = Auth::user();

        $stmt = $pdo->prepare(
            'SELECT id, rotulo, nome, cep, logradouro, numero, complemento, bairro, cidade, uf, principal
             FROM endereco
             WHERE cliente_id = ?
             ORDER BY principal DESC, id DESC'
        );
        $stmt->execute([$u['id']]); // OBS: por ora usando id do usuário como cliente_id
        $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $this->render('conta/enderecos', compact('enderecos'));
    }

    /** FORM: novo endereço (GET) */
    public function novoEndereco(): void
    {
        $this->render('conta/enderecos_form', [
            'isEdit'    => false,
            'endereco'  => [],
            'actionUrl' => Url::to('/conta/enderecos/novo'), // <-- usar Url::to
        ]);
    }

    /** POST: criar endereço */
    public function criarEndereco(): void
    {
        $u = Auth::user();
        $in = $this->sanitize($_POST);
        if (!$this->validateCsrf($_POST)) return;

        [$ok, $data, $errors] = $this->validateEndereco($in);
        if (!$ok) {
            Flash::set('error', 'Verifique os campos destacados.');
            $this->render('conta/enderecos_form', [
                'isEdit'    => false,
                'endereco'  => $in,
                'errors'    => $errors,
                'actionUrl' => Url::to('/conta/enderecos/novo'),
            ]);
            return;
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            if ($data['principal'] === 1) {
                $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')
                    ->execute([$u['id']]);
            }

            $pdo->prepare('INSERT INTO endereco
                (cliente_id, rotulo, nome, cep, logradouro, numero, complemento, bairro, cidade, uf, principal)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([
                    $u['id'],
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
            $this->redirect('/conta/enderecos'); // <-- usar redirect
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Erro ao salvar endereço.');
            $this->render('conta/enderecos_form', [
                'isEdit'    => false,
                'endereco'  => $in,
                'errors'    => ['Falha interna ao salvar.'],
                'actionUrl' => Url::to('/conta/enderecos/novo'),
            ]);
        }
    }

    /** FORM: editar (GET) */
    public function editarEndereco(int $id): void
    {
        $u = Auth::user();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare('SELECT * FROM endereco WHERE id = ? AND cliente_id = ? LIMIT 1');
        $stmt->execute([$id, $u['id']]);
        $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$endereco) {
            Flash::set('error', 'Endereço não encontrado.');
            $this->redirect('/conta/enderecos');
        }

        $this->render('conta/enderecos_form', [
            'isEdit'    => true,
            'endereco'  => $endereco,
            'actionUrl' => Url::to("/conta/enderecos/{$id}/editar"),
        ]);
    }

    /** POST: atualizar */
    public function atualizarEndereco(int $id): void
    {
        $u = Auth::user();
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
                'actionUrl' => Url::to("/conta/enderecos/{$id}/editar"),
            ]);
            return;
        }

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            // garante pertencimento
            $stmt = $pdo->prepare('SELECT 1 FROM endereco WHERE id = ? AND cliente_id = ?');
            $stmt->execute([$id, $u['id']]);
            if (!$stmt->fetchColumn()) {
                throw new \RuntimeException('Endereço inválido.');
            }

            if ($data['principal'] === 1) {
                $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')
                    ->execute([$u['id']]);
            }

            $pdo->prepare('UPDATE endereco SET
                    rotulo = ?, nome = ?, cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, principal = ?
                WHERE id = ? AND cliente_id = ?')
                ->execute([
                    $data['rotulo'], $data['nome'], $data['cep'], $data['logradouro'], $data['numero'],
                    $data['complemento'], $data['bairro'], $data['cidade'], $data['uf'], $data['principal'],
                    $id, $u['id']
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

    /** POST: excluir */
    public function excluirEndereco(int $id): void
    {
        $u = Auth::user();
        if (!$this->validateCsrf($_POST)) return;

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM endereco WHERE id = ? AND cliente_id = ?');
        $ok = $stmt->execute([$id, $u['id']]);

        Flash::set($ok ? 'success' : 'error', $ok ? 'Endereço excluído.' : 'Não foi possível excluir.');
        $this->redirect('/conta/enderecos');
    }

    /** POST: definir como principal */
    public function definirPrincipal(int $id): void
    {
        $u = Auth::user();
        if (!$this->validateCsrf($_POST)) return;

        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            // valida pertencimento
            $stmt = $pdo->prepare('SELECT 1 FROM endereco WHERE id = ? AND cliente_id = ?');
            $stmt->execute([$id, $u['id']]);
            if (!$stmt->fetchColumn()) {
                throw new \RuntimeException('Endereço inválido.');
            }

            $pdo->prepare('UPDATE endereco SET principal = 0 WHERE cliente_id = ?')->execute([$u['id']]);
            $pdo->prepare('UPDATE endereco SET principal = 1 WHERE id = ? AND cliente_id = ?')->execute([$id, $u['id']]);

            $pdo->commit();
            Flash::set('success', 'Endereço definido como principal.');
        } catch (\Throwable $e) {
            $pdo->rollBack();
            Flash::set('error', 'Não foi possível definir como principal.');
        }
        $this->redirect('/conta/enderecos');
    }

    /* ================= Helpers ================= */

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
        $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

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

        // normaliza CEP para 00000-000
        $in['cep'] = preg_replace('/^(\d{5})-?(\d{3})$/', '$1-$2', $in['cep']);

        return [empty($errors), $in, $errors];
    }

    /** Valida token CSRF enviado pelo form (campo "csrf"). */
    private function validateCsrf(array $src): bool
    {
        $token = isset($src['csrf']) ? (string)$src['csrf'] : null;

        if (!\App\Core\Csrf::check($token)) {
            Flash::set('error', 'Sessão expirada. Recarregue a página e tente novamente.');
            $this->redirect('/conta/enderecos');
            return false;
        }
        return true;
    }

    /** Redirect que respeita o base path da aplicação. */
    private function redirect(string $path): void
    {
        header('Location: ' . Url::to($path), true, 302);
        exit;
    }
}
