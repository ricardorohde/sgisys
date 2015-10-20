<?php

session_start();

$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Enviando :';
$arq = md5(uniqid("")) . '.jpg';
$uploadfile = $uploaddir . $arq;
$tmp = $_FILES['arquivo']['tmp_name'];

echo $_FILES['arquivo']['size'] . ' bytes...';
if ($_FILES['arquivo']['size'] > 1000000) {
    die('Tamanho em Bytes excede 1Mb');
}

if (empty($tmp)) {
    $arq = 'sem_foto.jpg';
} else {
    echo '<br>+ ' . $_FILES['arquivo']['name'];
    if (move_uploaded_file($tmp, $uploadfile)) {
        include '../model/conexao.php';
        $conexao = new conexao();

        $sql = " UPDATE ficha_config SET logo='$arq'";
        $ret = $conexao->db->prepare($sql);
        $ret->execute();
    } else {
        die('erro ao mover imagem.');
    }
}
echo '<script>window.open("../view/ficha_config.php?logo=' . $arq . '","frmPrincipal");</script>';
echo '<script>window.close("#");</script>';
?>