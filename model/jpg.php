<?php

session_start();

include 'conexao.php';
include '../controller/funcoes.php';

ini_set('display_errors', 'on');

$conexao = new conexao();

$sql = " SELECT * FROM imovel_foto";
$ret = $conexao->db->prepare($sql);
$ret->execute();

if (isset($_GET['comando'])) {
    $comando = $_GET['comando'];
} else {
    $comando = '';
}

$pasta = '../site/fotos/' . $_SESSION['cliente_id'] . '/';

if ($comando == 'MIN') {
    $res = '';
    while ($row = $ret->fetch()) {

        $foto = str_replace('JPG', 'jpg', $row['foto']);
        $sql = ' UPDATE imovel_foto SET foto="' . $foto . '" WHERE id=' . $row['id'] . ' LIMIT 1';
        $conexao->db->query($sql);

        if ($res != $row['ref']) {
            $res = $row['ref'];
            $sql = ' UPDATE imovel SET foto="' . $foto . '" WHERE id=' . $row['ref'] . ' LIMIT 1';
            $conexao->db->query($sql);
            echo '<p>' . $sql . '</p>';
        }
        $arq = $pasta . $row['foto'];
        echo '<br>' . $x . ' : ';
        if (file_exists($arq)) {
            if (rename($arq, $pasta . $foto)) {
                $x++;
                echo ' de ' . $arq . ' para ' . $pasta . $foto;
            } else {
                echo ' Erro ao Mover ' . $arq . ' para ' . $pasta . $foto;
            }
        } else {
            echo ' Nao encontrado ' . $arq;
        }
    }
}

if ($comando == 'MAX') {
    $res = '';
    while ($row = $ret->fetch()) {

        $foto = str_replace('jpg', 'JPG', $row['foto']);
        $sql = ' UPDATE imovel_foto SET foto="' . $foto . '" WHERE id=' . $row['id'] . ' LIMIT 1';
        $conexao->db->query($sql);

        if ($res != $row['ref']) {
            $res = $row['ref'];
            $sql = ' UPDATE imovel SET foto="' . $foto . '" WHERE id=' . $row['ref'] . ' LIMIT 1';
            $conexao->db->query($sql);
            echo '<p>' . $sql . '</p>';
        }
        $arq = $pasta . $row['foto'];
        echo '<br>' . $x . ' : ';
        if (file_exists($arq)) {
            if (rename($arq, $pasta . $foto)) {
                $x++;
                echo ' de ' . $arq . ' para ' . $pasta . $foto;
            } else {
                echo ' Erro ao Mover ' . $arq . ' para ' . $pasta . $foto;
            }
        } else {
            echo ' Nao encontrado ' . $arq;
        }
    }
}