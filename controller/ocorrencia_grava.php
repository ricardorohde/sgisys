<?php

session_start();

include '../view/func.php';
echo '<h3>Gravando...</h3>';

include '../model/ocorrencia.php';
$ocorrencia = new ocorrencia();

$dados = array();
if ($_POST['id'] == 'add') {
    $id = $ocorrencia->inserir();
} else {
    $id = $_POST['id'];
}

$dados['data'] = data_encode($_POST['data']);
$dados['hora'] = $_POST['hora'];
$dados['tipo'] = $_POST['tipo'];
$dados['de'] = $_POST['de'];
$dados['para'] = $_POST['para'];
$dados['agenda_data'] = data_encode($_POST['agenda_data']);
$dados['agenda_hora'] = $_POST['agenda_hora'];
$dados['historico'] = $_POST['historico'];
$dados['status'] = $_POST['status'];
$dados['avisar_data'] = data_encode($_POST['avisar_data']);
$dados['avisar_hora'] = $_POST['avisar_hora'];
$dados['avisar_resolvido'] = $_POST['avisar_resolvido'];
$dados['avisar_email'] = $_POST['avisar_email'];
        
$ocorrencia->gravar($id, $dados);
echo '<script>window.open(\'../view/ocorrencias.php\',\'frmOcorrs\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/ocorrencia.php?id=' . $id . '\',\'_self\');">voltar</a>';
