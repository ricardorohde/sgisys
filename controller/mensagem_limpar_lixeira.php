<?php

session_start();

include '../model/mensagem.php';
include 'mensagem.php';

$mensagem = new mensagem();
$ret = json_decode(mensagem_listar('Lixeira', $_SESSION['usuario_id']));
foreach ($ret as $id) {
    $mensagem->excluir($id);
}
echo '<script>window.open(\'../view/mensagem.php?pasta=Lixeira\',\'_self\');</script>';
