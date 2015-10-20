<?php

session_start();

include '../model/fechamento.php';

$fechamento = new fechamento();

$fechamento->excluir($_GET['id']);
echo '<script>window.open(\'../view/fechamentos.php\',\'_self\');</script>';
