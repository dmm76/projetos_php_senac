<?php

if(!isset($_SESSION['idUsuario'])){
    header("Location: login.php?Você precisa esta logadoooo!");
    exit();
}