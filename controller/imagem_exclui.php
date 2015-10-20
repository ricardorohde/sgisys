<?php

session_start();

include '../model/site_imagem.php';

$imagem = new site_imagem();

$imagem->excluir($_GET['id']);
echo '<script>window.open(\'../view/imagens.php\',\'_self\');</script>';
