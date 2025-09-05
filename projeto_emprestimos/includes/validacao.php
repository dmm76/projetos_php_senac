<?php
if (empty($_SESSION['id_usuario'])) {
    header('Location: /projeto_emprestimo/login.php');
    exit;
}
