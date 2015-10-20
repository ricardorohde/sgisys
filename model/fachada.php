<?php

session_start();

include 'cadastro.php';
include 'imovel_foto.php';

$cadastro = new cadastro();

$cads = $cadastro->listar('imovel', $where, $order, $rows);

$imovel_foto = new imovel_foto();


foreach ($cads as $cad) {
    $imovel_foto->testa_fachada($cad);
}




        