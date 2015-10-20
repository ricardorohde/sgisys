<?php

session_start();
ini_set('display_errors', 'on');
ini_set('max_execution_time', '600');
set_time_limit(600);

if (isset($_SESSION['qtd_mensagens'])) {
    $dif = ($_SESSION['qtd_mensagens'] - $_SESSION['qtd_ant_mensagens']);
    if ($dif > 0) {
        if ($dif == 1) {
            echo 'SGI Fácil : Você recebeu 1 nova mensagem';
        } else {
            echo 'SGI Fácil : Você recebeu ' . $_SESSION['qtd_mensagens'] . ' novas mensagens';
        }
        $_SESSION['qtd_ant_mensagens'] = $_SESSION['qtd_mensagens'];
    }
}