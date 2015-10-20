<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

ini_set('display_errors', 'on');

include '../model/conexao.php';

$logo = $_GET['logo'];
$texto1 = $_GET['texto1'];
$texto2 = $_GET['texto2'];

$conexao = new conexao();

$sql = " UPDATE ficha_config SET logo='$logo',texto1='$texto1',texto2='$texto2'";
$ret = $conexao->db->prepare($sql);
$ret->execute();

echo 'OK';