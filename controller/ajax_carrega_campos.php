<?php
session_start();
include 'tabela.php';
$tipo = $_GET['tipo'];
$campo = $_GET['campo'];
$tabela_vinculo = json_decode(tabela_vinculo($tipo));
$saida = array();
foreach ($tabela_vinculo as $campo_vinculo) {
    $valor = $campo_vinculo->id;
    $texto = $campo_vinculo->$campo;
    $saida[] = array($valor, $texto);
}
echo json_encode($saida);
