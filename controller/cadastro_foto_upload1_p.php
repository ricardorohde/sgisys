<?php

session_start();

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->fotos != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.close("#");</script>';
}

include '../controller/cadastro.php';
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


echo '<br>+ ' . $_FILES['arquivo']['name'];

move_uploaded_file($tmp, $uploadfile);
cadastro_foto_gravar($_SESSION['tipo_cadastro'], $id, $arq);

echo '<script>window.open("../view/cadastro.php?id=' . $_POST['id'] . '","frmPrincipal");</script>';
echo '<script>window.close("#");</script>';
?>