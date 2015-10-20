<?php

session_start();

include '../model/usuario.php';

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->usuario_alterar != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
}

echo '<h3>Gravando...</h3>';

$usuario = new usuario();

$nome = $_POST['nome'];
if ($_POST['id'] == 'add') {
    $id = $usuario->inserir($nome);
    $dados['id'] = $id;
} else {
    $id = $_POST['id'];
}

$dados['acesso'] = $_POST['acesso'];
$dados['home'] = $_POST['home'];
$dados['foto'] = $_POST['foto'];
$dados['seg_sex_hi'] = $_POST['seg_sex_hi'];
$dados['seg_sex_hf'] = $_POST['seg_sex_hf'];
$dados['sab_hi'] = $_POST['sab_hi'];
$dados['sab_hf'] = $_POST['sab_hf'];
$dados['dom_hi'] = $_POST['dom_hi'];
$dados['dom_hf'] = $_POST['dom_hf'];
$dados['ip1'] = $_POST['ip1'];
$dados['ip2'] = $_POST['ip2'];
//$dados['home1'] = $_POST['home1'];
//$dados['home2'] = $_POST['home2'];
//$dados['home3'] = $_POST['home3'];


$usuario->gravar($id, $dados);
echo '<script>window.open(\'../view/usuarios.php\',\'_self\');</script>';
//echo '<a href="#" onclick="window.open(\'../view/usuario.php?id=' . $id . '\',\'_self\');">voltar</a>';
