<?php

include '../model/imovel_subtipo_nome.php';

$imovel_subtipo_nome = new imovel_subtipo_nome();

echo json_encode($imovel_subtipo_nome->listar($_GET['q']));


