<?php

session_start();

if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
} else {
    $hash = '';
}

if (empty($hash) || strlen($hash) < 50 || strlen($hash) > 150) {
    die('hash invalido ' . strlen($hash));
}

$tmp = explode('|', base64_decode($hash));

$data = $tmp[0];
$cliente = $tmp[1];
$uniq = $tmp[2];
$data2 = $tmp[3];
$ip = $tmp[4];
$data3 = $tmp[5];
$time = $tmp[6];

if (empty($data) || empty($cliente) || empty($uniq) || empty($data2) || empty($ip) || empty($data3) || empty($time)) {
    die('hash faltando componente');
}

if ($data != date('Ymd')) {
    die('hash expirado - solicite novo reset.');
}

$_SESSION['cliente_id'] = $cliente;

include '../model/usuario.php';

$usuario = new usuario();

$id = $usuario->carregar_reset($hash);

if (!$id) {
    die('hash expirado - solicite novo reset.');
}

$usuario->senha_gravar($id, '', '');

echo '<script>alert("Senha resetada.");</script>';
echo '<script>window.close("#");</script>';

