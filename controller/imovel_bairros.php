<?php

include '../model/imovel_bairros.php';

$imovel_bairros = new imovel_bairros();

echo json_encode($imovel_bairros->listar($_GET['q']));


