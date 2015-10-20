<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

ini_set('display_errors', 'on');

include '../model/conexao.php';

$usuario = $_GET['usuario'];
$data = $_GET['data'];
$hora = $_GET['hora'];
$compromisso = $_GET['compromisso'];

$conexao = new conexao();

$sql = " UPDATE agenda SET compromisso='$compromisso' WHERE usuario='$usuario' and data='$data' and hora='$hora' ";
$ret = $conexao->db->prepare($sql);
$ret->execute();

echo 'OK';
