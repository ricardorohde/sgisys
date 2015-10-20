<?php

session_start();

include '../model/mensagem.php';

echo '<h3>Enviando...</h3>';
$mover = '';
if (isset($_POST['mover'])) {
    $mover = $_POST['mover'];
}
$mensagem = new mensagem();
if (empty($mover)) {
    if (isset($_POST['confirmar'])) {
        $confirmar = $_POST['confirmar'];
    } else {
        $confirmar = 'N';
    }

    $mensagem->enviar($_SESSION['usuario_id'], $_POST['para'], $_POST['assunto'], $_POST['mensagem'], $confirmar);
    echo '<script>window.open(\'../view/mensagem.php?pasta=Mensagens enviadas\',\'frmPrincipal\');</script>';
} else {
    $mensagem->mover($_POST['id'], $mover);
    echo '<script>window.open(\'../view/mensagem.php?pasta=' . $_POST['pasta'] . '\',\'frmPrincipal\');</script>';
}

//echo '<a href="#" onclick="window.open(\'../view/mensagem.php?id=' . $id . '\',\'_self\');">voltar</a>';
