<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

ini_set('display_errors', 'on');

include '../model/conexao.php';

$tipo_nome = $_GET['tipo_nome'];
$subtipo_nome = $_GET['subtipo_nome'];
$cidade = $_GET['cidade'];
$bairro = $_GET['bairro'];
$localizacao = $_GET['localizacao'];
$condominio = $_GET['condominio'];
$edificio = $_GET['edificio'];
$endereco = $_GET['endereco'];
$q = $_GET['q'];

$conexao = new conexao();

$sql = " SELECT DISTINCT($q) FROM imovel WHERE $q != ''  ";

if (!empty($tipo_nome)) {
    $sql .= " and tipo_nome = '$tipo_nome' ";
}
if (!empty($subtipo_nome)) {
    $sql .= " and subtipo_nome = '$subtipo_nome' ";
}
if (!empty($cidade)) {
    $sql .= " and cidade = '$cidade' ";
}
if (!empty($bairro)) {
    $sql .= " and bairro = '$bairro' ";
}
if (!empty($localizacao)) {
    $sql .= " and localizacao = '$localizacao' ";
}
if (!empty($condominio)) {
    $sql .= " and condominio = '$condominio' ";
}
if (!empty($edificio)) {
    $sql .= " and edificio = '$edificio' ";
}
if (!empty($endereco)) {
    $sql .= " and endereco = '$endereco' ";
}
$sql .= " ORDER BY $q ";
$ret = $conexao->db->prepare($sql);
$ret->execute();
$saida = array();
while ($row = $ret->fetch()) {
    if (!empty($row[0])) {
        $saida[] = $row[0];
    }
}
echo json_encode($saida);
