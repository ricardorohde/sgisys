<?php

session_start();

include '../controller/site_config.php';

echo '<h3>Gravando...</h3>';

$campos = site_config_campos();

for ($i = 0; $i < count($campos); $i++) {
    if (isset($_POST[$campos[$i]])) {
        $dados[$campos[$i]] = $_POST[$campos[$i]];
    } else {
        $dados[$campos[$i]] = '';
    }
}

site_config_gravar($dados);

echo '<script>window.open(\'../view/pagina_home.php\',\'_self\');</script>';
