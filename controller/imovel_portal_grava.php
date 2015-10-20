<?php

session_start();

include '../model/portal.php';
include 'portal.php';

echo '<h3>Gravando...</h3>';

$portal = new portal();

$dados = array();

$ref = $_POST['ref'];

$aux = json_decode(portal_listar());
foreach ($aux as $id) {
    $dados[$id] = $_POST[$id];
}

$portal->publicar_gravar($ref, $dados);
echo '<script>window.open(\'../view/imovel_portais.php?id='.$ref.'\',\'_self\');</script>';

//echo '<a href="#" onclick="window.open(\'../view/portal.php?id=' . $id . '\',\'_self\');">voltar</a>';
