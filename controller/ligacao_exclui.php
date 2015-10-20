<?php

session_start();

include '../model/ligacao.php';

$ligacao = new ligacao();

$ligacao->excluir($_GET['id']);
echo '<script>window.open(\'../view/ligacoes.php\',\'_self\');</script>';
