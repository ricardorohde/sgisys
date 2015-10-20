<?php

session_start();

$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->fotos != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.close("#");</script>';
}


include 'imovel_foto.php';

$id = $_POST['id'];
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<br>Exluindo :';

foreach ($_POST['foto'] as $foto) {

    if (file_exists($uploaddir . $foto)) {
        echo '<br>- ' . $foto;
        unlink($uploaddir . $foto);

        imovel_foto_excluir($foto);
    }
}
echo '<script>window.open("../view/foto_upload.php?id=' . $id . '","_self");</script>';
?>
