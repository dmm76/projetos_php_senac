<?php
if (empty($_SESSION['idUsuario'])) {
    header('Location: /projeto_hospital/login.php'); exit;
}
