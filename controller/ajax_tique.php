<?php

session_start();
include 'portal.php';

$dados = array();

$dados['codigo_cliente'] = $_REQUEST['codigo_cliente'];
$dados['usuario'] = $_REQUEST['usuario'];
$dados['senha'] = $_REQUEST['senha'];
$dados['url'] = $_REQUEST['url'];
if (strrpos($dados['url'], '/') != (strlen($dados['url']) - 1)) {
    $dados['url'].= '/';
}
$dados['usuario_ftp'] = $_REQUEST['usuario_ftp'];
$dados['senha_ftp'] = $_REQUEST['senha_ftp'];
$dados['endereco_ftp'] = $_REQUEST['endereco_ftp'];
$dados['enviar_endereco'] = $_REQUEST['enviar_endereco'];

echo portal_gravar('TIQUE', $dados);
