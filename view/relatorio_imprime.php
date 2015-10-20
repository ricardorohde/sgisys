<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');
ini_set('register_globals', 'off');
ini_set('display_errors', 'on');

include 'func.php';

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));

include '../controller/usuario.php';

$usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));

if ($usuario->acesso < 20) {
    echo '<script>alert("Apenas Gerente pode exportar.");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}

if (isset($_POST['tipo_relatorio'])) {
    $tipo_relatorio = $_POST['tipo_relatorio'];
} else {
    $tipo_relatorio = '';
}

if ($tipo_relatorio == '1') {
    include 'relatorio_imprime1.php';
} elseif ($tipo_relatorio == '2') {
    include 'relatorio_imprime2.php';
}

