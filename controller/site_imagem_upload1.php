<?php

session_start();
include '../controller/site_imagem.php';
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Enviando :';
for ($i = 0; $i < count($_FILES['arquivo']['name']); $i++) {
    $arq = md5(uniqid("")) . '.jpg';
    $uploadfile = $uploaddir . $arq;
    $tmp = $_FILES['arquivo']['tmp_name'][$i];
    echo '<br>+ ' . $_FILES['arquivo']['name'][$i];
    if (move_uploaded_file($tmp, $uploadfile)) {
        site_imagem_gravar($arq, $_FILES['arquivo']['name'][$i]);
        echo '...OK';
    } else {
        echo " : Arquivo " . $_FILES['arquivo']['name'][$i] . " não enviado";
    }
}

echo '<script>window.open("../view/imagens.php","frmPrincipal");</script>';
echo '<script>window.close("#");</script>';
?>