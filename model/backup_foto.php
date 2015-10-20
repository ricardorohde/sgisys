<?php

session_start();

include 'conexao.php';
include '../controller/funcoes.php';

ini_set('display_errors', 'on');

$conexao = new conexao();

$pasta_backup = '../site/fotos/' . $_SESSION['cliente_id'] . '/backup';
if (!file_exists($pasta_backup)) {
    if (mkdir($pasta_backup)) {
        echo 'Pasta de Backup criada';
    } else {
        echo 'Erro ao criar pasta backup';
    }
}

$pasta_fotos = '../site/fotos/' . $_SESSION['cliente_id'];

$fotos = glob($pasta_fotos . '/*.*');

foreach ($fotos as $foto) {
    $foto = str_replace($pasta_fotos . '/', '', $foto);
    echo '<br>Foto: ' . $foto;

    $sql = " SELECT id FROM imovel_foto WHERE foto='$foto' ";
    $ret = $conexao->db->prepare($sql);
    $ret->execute();

    if ($ret->rowCount() == 1) {
        echo ' - Ok';
    } else {
        echo ' - Nao encontrada.';
        if (rename($pasta_fotos . '/' . $foto, $pasta_backup . '/' . $foto)) {
            echo ' - Ok foto enviada para backup.';
        } else {
            echo ' - ERRO Nao foi possivel mover.';
        }
    }
}