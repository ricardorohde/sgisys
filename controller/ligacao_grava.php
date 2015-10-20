<?php

session_start();

include '../view/func.php';
echo '<h3>Gravando...</h3>';

include '../model/ligacao.php';
$ligacao = new ligacao();

$dados = array();
if ($_POST['id'] == 'add') {
    $id = $ligacao->inserir();
    $dados['id'] = $id;
} else {
    $id = $_POST['id'];
}

$dados['atendente'] = $_SESSION['usuario_id'];
$dados['data'] = data_encode($_POST['data']);
$dados['hora'] = $_POST['hora'];
$dados['assunto'] = $_POST['assunto'];
$dados['departamento'] = $_POST['departamento'];
$dados['mensagem'] = filtra_campo($_POST['mensagem']);
$dados['avisar'] = $_POST['avisar'];
        
$ligacao->gravar($id, $dados);
echo '<script>window.open(\'../view/ligacoes.php\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/ligacao.php?id=' . $id . '\',\'_self\');">voltar</a>';
