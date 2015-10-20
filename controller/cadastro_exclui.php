<?php

session_start();

include '../model/cadastro.php';

$cadastro = new cadastro();

$cadastro->excluir($_SESSION['tipo_cadastro'],$_GET['id']);
echo '<script>window.open(\'../view/cadastros.php\',\'_self\');</script>';
