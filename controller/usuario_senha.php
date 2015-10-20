<?php

session_start();
header("Content-type: text/html; charset=UTF-8");
include 'usuario.php';
$id = $_REQUEST['id'];
$senha_atual = $_REQUEST['senha_atual'];
$nova_senha1 = $_REQUEST['nova_senha1'];
$nova_senha2 = $_REQUEST['nova_senha2'];

$erro = '';
if (empty($senha_atual)) {
    usuario_grava_senha($id, $senha_atual, $nova_senha1);
    $erro = 'Senha Resetada para 123456.';
} else {
    if ($nova_senha1 != $nova_senha2) {
        $erro .= ' A senhas novas não coincidem. Por favor digite 2x iguais.';
    } elseif ($senha_atual == $nova_senha1) {
        $erro .= ' A senha nova deve ser diferente da atual.';
    } else {
        if (usuario_grava_senha($id, $senha_atual, $nova_senha1)) {
            $erro .= ' Senha alterada.';
        } else {
            $erro .= 'Senha não pode ser alterada. Senha atual correta?';
        }
    }
}
echo '<script>alert("' . $erro . '");</script>';
echo '<script>window.close("#");</script>';
?>