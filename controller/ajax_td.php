<?php

session_start();
include '../model/conexao.php';

$id = $_REQUEST['id'];
$valor = $_REQUEST['valor'];

$con = new conexao();
$sql = " UPDATE publicar SET tipo_anuncio='$valor' WHERE id='$id' ";
$con->db->query($sql);

echo 'OK';