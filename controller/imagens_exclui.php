<?php

session_start();

include 'site_imagem.php';
$ret = json_decode(site_imagem_listar());
$x = 0;
$imagem = new site_imagem();
foreach ($ret as $id) {
    $site_imagem = json_decode(site_imagem_carregar($id));
    $arq = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $site_imagem->img;
    if (file_exists($arq)) {
        if (unlink($arq)) {
            $x++;
        } else {
            echo "Erro ao excluir $arq";
        }
    } else {
        echo "<br>arquivo não encontrado : $arq";
    }
    $imagem->excluir($site_imagem->id);
}
echo "<script>alert('$x arquivo(s) excluido(s).');</script>";
echo "<script>window.open('../view/imagens.php','_self');</script>";
