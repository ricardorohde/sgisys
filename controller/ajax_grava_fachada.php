<?php

session_start();

include '../model/imovel_foto.php';

$imovel_foto = new imovel_foto();

if (!isset($_GET['id'])) {
    die('faltando parametro');
}

if ($imovel_foto->grava_fachada($_GET['id'])) {
    echo 'Fachada Gravada.';
} else {
    echo 'Não foi possivel gravar';
}
