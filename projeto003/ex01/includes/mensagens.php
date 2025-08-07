<?php 
    if (isset($_GET['msg'])) {
        if($_GET['tipoMsg'] == 'sucesso'){
            echo '<div class="alert alert-primary" role="alert">
                    Sucesso! '.$_GET["msg"].'
                    </div>';
        }
    }

    if (isset($_GET['msg'])) {
        if($_GET['tipoMsg'] == 'erro'){
            echo '<div class="alert alert-danger" role="alert">
                    Erro! '.$_GET["msg"].'
                    </div>';
        }
    }

