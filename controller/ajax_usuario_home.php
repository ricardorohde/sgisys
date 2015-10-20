<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

ini_set('display_errors', 'on');

include '../model/conexao.php';

$home = $_GET['home'];

$conexao = new conexao();

$sql = " UPDATE usuario SET home='$home' WHERE id='" . $_SESSION['usuario_id'] . "' ";

$ret = $conexao->db->prepare($sql);
$ret->execute();
