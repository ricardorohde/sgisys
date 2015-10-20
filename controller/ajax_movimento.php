<?php

session_start();

$tipo = $_GET['tipo'];

$ret = array();
if ($tipo == 'Imobiliária') {
    include 'site_config.php';
    $aux = json_decode(site_config_carregar());
    $ret[] = $aux->nome;
} elseif ($tipo == 'Corretor') {
    include 'cadastro.php';
    $aux = json_decode(cadastro_listar('corretor', $where, $order, $rows));
    foreach ($aux as $id) {
        $cad = json_decode(cadastro_carregar('corretor', $id));
        $ret[] = $cad->nome;
    }
} elseif ($tipo == 'Comprador') {
    include 'cadastro.php';
    $aux = json_decode(cadastro_listar('comprador', $where, $order, $rows));
    foreach ($aux as $id) {
        $cad = json_decode(cadastro_carregar('comprador', $id));
        $ret[] = $cad->nome;
    }
} elseif ($tipo == 'Proprietário') {
    include 'cadastro.php';
    $aux = json_decode(cadastro_listar('proprietario', $where, $order, $rows));
    foreach ($aux as $id) {
        $cad = json_decode(cadastro_carregar('proprietario', $id));
        $ret[] = $cad->nome;
    }
} elseif ($tipo == 'Usuário') {
    include 'usuario.php';
    $aux = json_decode(usuario_listar());
    foreach ($aux as $id) {
        $cad = json_decode(usuario_carregar($id));
        $ret[] = $cad->nome;
    }
}

echo json_encode($ret);
