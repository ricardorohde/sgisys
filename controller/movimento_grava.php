<?php

session_start();

include '../model/movimento.php';
include 'funcoes.php';

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->financeiro != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
    exit();
}

echo '<h3>Gravando...</h3>';

$movimento = new movimento();

if ($_POST['id'] == 'add') {
    $id = $movimento->inserir();
} else {
    $id = $_POST['id'];
}

$data = data_encode($_POST['data']);
$tipo = $_POST['tipo'];
$nome = $_POST['nome'];
$historico = $_POST['historico'];
$sentido = $_POST['sentido'];
$valor = br_us($_POST['valor']);


$movimento->gravar($id, $data, $tipo, $nome, $historico, $sentido, $valor);
echo '<script>window.open(\'../view/movimento.php?id=' . $id . '\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/movimento.php?id=' . $id . '\',\'_self\');">voltar</a>';
