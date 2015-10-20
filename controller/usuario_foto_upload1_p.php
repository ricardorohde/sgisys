<?php

session_start();

if ($_SESSION['usuario_id'] != $_POST['id']) {
    include '../controller/usuario_acesso.php';
    $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
    if ($usu->usuario_alterar != 'Sim') {
        echo '<script>alert("Você não tem permissões para essa operação.")</script>';
        echo '<script>window.close("#");</script>';
    }
}
include '../controller/usuario.php';
$id = $_POST['id'];
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Enviando :';
$arq = md5(uniqid("")) . '.jpg';
$uploadfile = $uploaddir . $arq;
$tmp = $_FILES['arquivo']['tmp_name'];

echo $_FILES['arquivo']['size'] . ' bytes...';
if ($_FILES['arquivo']['size'] > 2000000) {
    die('Tamanho em Bytes excede 2Mbs');
}

if (empty($tmp)) {
    $arq = 'usuario_sem_foto.jpg';
} else {
    echo '<br>+ ' . $_FILES['arquivo']['name'];
    if (move_uploaded_file($tmp, $uploadfile)) {
        usuario_foto_gravar($id, $arq);
    } else {
        die('erro ao mover imagem.');
    }
}
echo '<script>window.open("../view/usuario.php?id=' . $_POST['id'] . '","frmPrincipal");</script>';
echo '<script>window.close("#");</script>';
?>