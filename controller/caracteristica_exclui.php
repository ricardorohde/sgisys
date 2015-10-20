<?php

session_start();

include '../model/caracteristica.php';

$caracteristica = new caracteristica();

$caracteristica->excluir($_GET['id']);
echo '<script>window.open(\'../view/caracteristicas.php\',\'_self\');</script>';
