<?php
include_once("includes/conexao.php");
class Usuario
{
    private $bd;
    public function __construct(Database $bd)
    {
        $this->bd = $bd;
    }

    public function inserir(array $data)
    {
        $idUsuario = $data['idUsuario'];
        $cadastro = date('Y-m-d H:i:s');
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];
        $nivel = $data['nivel'];

        if ($idUsuario == 0) {
            $sql = "INSERT INTO usuarios(cadastro, nome, email, senha, nivel)
                    VALUES ('$cadastro', '$nome', '$email', '$senha', '$nivel')";

            return $this->bd->query($sql);
        } else {
            $sql = "UPDATE usuarios SET nome = '{$nome}', email = '$email', senha = '$senha', nivel = '$nivel'
                    WHERE idUsuario = '{$idUsuario}'";

            return $this->bd->query($sql);
        }
    }

    public function listar()
    {
        $sql = "SELECT * FROM usuarios
            ORDER BY idUsuario ASC";

        //essa linha pega todos os dados vindos do banco e insere em resultado
        $resultado = $this->bd->query($sql);

        $rows = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function buscar($idUsuario)
    {
        $sql = "SELECT * FROM usuarios WHERE idUsuario = '{$idUsuario}'";
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc();
    } 

    public function deletar($idUsuario)
    {
        $id = (int)$idUsuario;
        $sql = "DELETE FROM usuarios where idUsuario = {$id}";
        return $this->bd->query($sql);
    }

    //public function login($email, $senha){
    //    $sql = "SELECT email, senha, nivel FROM usuarios
    //            WHERE email = '{$email}' AND senha = '{$senha}'";
//
  //      $resultado = $this->bd->query($sql);
    //    return $resultado->fetch_assoc();
      //  
     //   return $resultado;
     //   
   // }
   public function login($email, $senha)
{
    $email = trim((string)$email);
    $senha = (string)$senha;

    // acessa a instância real do mysqli
    $mysqli = $this->bd->conexao;
    if (!($mysqli instanceof mysqli)) {
        return false;
    }

    // consulta segura por e-mail
    $stmt = $mysqli->prepare(
        "SELECT idUsuario, nome, email, senha, nivel
         FROM usuarios
         WHERE email = ?
         LIMIT 1"
    );
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado ? $resultado->fetch_assoc() : null;
    $stmt->close();

    if (!$usuario) {
        return false; // e-mail não encontrado
    }

    // verifica senha: funciona tanto com hash (password_hash) quanto texto puro (não recomendado)
    $hashInfo = password_get_info((string)$usuario['senha']);
    $ok = !empty($hashInfo['algo'])
        ? password_verify($senha, $usuario['senha'])
        : hash_equals((string)$usuario['senha'], $senha);

    if (!$ok) {
        return false; // senha inválida
    }

    unset($usuario['senha']); // não expor a senha
    return $usuario;
}


}
