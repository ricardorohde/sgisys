<?php
session_start();
include '../model/imovel_condominio.php';

$imovel_condominio = new imovel_condominio();
echo json_encode($imovel_condominio->listar());

