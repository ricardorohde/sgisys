<?php

session_start();
include '../controller/imovel_foto.php';
$id = $_POST['id'];
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Enviando :';
for ($i = 0; $i < count($_FILES['arquivo']['name']); $i++) {
    $arq = md5(uniqid("")) . '.jpg';
    $uploadfile = $uploaddir . $arq;
    $tmp = $_FILES['arquivo']['tmp_name'][$i];
    echo '<br>+ ' . $_FILES['arquivo']['name'][$i];
    echo $_FILES['arquivo']['size'][$i] . ' bytes...';
    if ($_FILES['arquivo']['size'][$i] > 2000000) {
        echo 'Tamanho em Bytes excede 2Mbs';
    } elseif (move_uploaded_file($tmp, $uploadfile)) {
        imovel_foto_gravar($id, $arq);
        echo '...OK';
    } else {
        echo " : Arquivo " . $_FILES['arquivo']['name'][$i] . " não enviado";
    }
}
echo '<script>window.open("../view/foto_upload.php?id=' . $id . '","_self");</script>';
?>