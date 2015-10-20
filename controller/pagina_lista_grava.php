<?php

session_start();

include '../controller/lista_config.php';

echo '<h3>Gravando...</h3>';

$campos = lista_config_campos();

for ($i = 0; $i < count($campos); $i++) {
    if (isset($_POST[$campos[$i]])) {
        $dados[$campos[$i]] = $_POST[$campos[$i]];
    } else {
        $dados[$campos[$i]] = '';
    }
}

lista_config_gravar($dados);

echo '<script>window.open(\'../view/pagina_lista.php\',\'_self\');</script>';
