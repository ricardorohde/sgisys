<?php

session_start();

include '../model/usuario.php';

$usuario = new usuario();

$usuario->excluir($_GET['id']);
echo '<script>window.open(\'../view/usuarios.php\',\'_self\');</script>';
