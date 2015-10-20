<?php

session_start();
include '../controller/site_imagem.php';
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Enviando :';
$arq = md5(uniqid("")) . '.jpg';
$nome = $_FILES['arquivo']['name'];
$uploadfile = $uploaddir . $arq;
$tmp = $_FILES['arquivo']['tmp_name'];


echo $_FILES['arquivo']['size'] . ' bytes...';
if ($_FILES['arquivo']['size'] > 2000000) {
    die('Tamanho em Bytes excede 2Mbs');
}


echo '<br>+ ' . $nome;

move_uploaded_file($tmp, $uploadfile);
site_imagem_gravar($arq, $nome);

echo '<script>window.open("../view/pagina_home.php","frmPrincipal");</script>';
echo '<script>window.close("#");</script>';
?>