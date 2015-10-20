<?php

session_start();

include '../model/mensagem.php';

$mensagem = new mensagem();

$mensagem->excluir($_GET['id']);
echo '<script>window.open(\'../view/mensagem.php?pasta=Lixeira\',\'_self\');</script>';
