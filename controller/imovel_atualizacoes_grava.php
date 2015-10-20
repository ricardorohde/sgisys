<?php

session_start();

include '../model/ficha.php';
include 'ficha.php';
include 'funcoes.php';

echo '<h3>Gravando...</h3>';

$ficha = new ficha();

$ref = $_POST['ref'];
$data = data_encode($_POST['data']);
$solicitante = $_POST['solicitante'];
$descricao = $_POST['descricao'];
$situacao = $_POST['situacao'];

$ficha->gravar($ref, $data, $solicitante, $descricao, $situacao);
echo '<script>window.open(\'../view/imovel_atualizacoes.php?id=' . $ref . '\',\'_self\');</script>';

//echo '<a href="#" onclick="window.open(\'../view/ficha.php?id=' . $id . '\',\'_self\');">voltar</a>';
