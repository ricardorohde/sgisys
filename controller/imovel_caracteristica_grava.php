<?php

session_start();

include '../model/imovel_caracteristica.php';
include 'caracteristica.php';

echo '<h3>Gravando...</h3>';

$imovel_caracteristica = new imovel_caracteristica();
$caracteristica = new caracteristica();

$dados = array();

$ref = $_POST['ref'];

$aux = json_decode(caracteristica_listar());
foreach ($aux as $id) {
    $dados[$id] = $_POST[$id];
}

$imovel_caracteristica->gravar($ref, $dados);
echo '<script>window.open(\'../view/imovel_caracteristica.php?id='.$ref.'\',\'_self\');</script>';

//echo '<a href="#" onclick="window.open(\'../view/imovel_caracteristica.php?id=' . $id . '\',\'_self\');">voltar</a>';
