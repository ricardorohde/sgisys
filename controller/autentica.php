<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

$_SESSION['cliente_id'] = str_pad((filter_input(INPUT_POST, 'cliente', FILTER_DEFAULT) + 0), 4, '0', 0);

include '../model/usuario.php';

$form_usuario = filter_input(INPUT_POST, 'usuario', FILTER_DEFAULT);
$form_senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

//if (strtoupper($form_usuario) != 'ADMIN') {
//    die('Servidor em manutencao');
//}
    
    
$usuario = new usuario;
$ret = $usuario->autenticar($form_usuario, $form_senha);
if ($ret == 'OK') {
    echo '<meta charset="UTF-8">';
    if ($form_senha == '123456') {
        echo '<script>alert("Sua senha ainda é a padrão, por favor troque agora sua senha por uma mais segura.");</script>';
        echo '<script>window.open("../view/admin.php?senha=S","_self");</script>';
    } else {
        echo '<script>window.open("../view/admin.php","_self");</script>';
    }
} else {
    echo '<meta charset="UTF-8">';
    echo '<script>alert("' . $ret . '");</script>';
    echo '<script>window.open("../view/login.php","_self");</script>';
}



