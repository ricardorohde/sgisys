<?php
session_start();

include '../model/debug.php';

$id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="../view/css/fontes.css" rel="stylesheet" />
        <link href="../view/css/base.css" rel="stylesheet" />
        <link href="../view/css/forms.css" rel="stylesheet" />
        <link href="../view/css/fotos.css" rel="stylesheet" />
        <script src="../controller/js/fotos.js"></script>
    </head>
    <body>
        <br>
        <form action="../controller/foto_grava.php" method="POST"> 
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
            include '../controller/imovel_foto.php';
            $ret = json_decode(imovel_foto_listar($id));
            echo '<div class="galeria">';
            echo '  <div id="foto-grande"></div>';
            if (!empty($ret)) {
                foreach ($ret as $index) {
                    $foto = json_decode(imovel_foto_carregar($index));
                    echo '<div class="foto-album"><center>';
                    echo '  <a href="javascript: void(0);" onclick="mostra_foto(\'../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto->foto . '\');"><img src="../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto->foto . '" width="150"></a>';
                    echo '  <br><input type="checkbox" name="foto[]" value="' . $foto->foto . '">';
                    echo '</center></div>';
                }
            } else {
                echo '<p>Nenhuma foto enviada.';
            }
            echo '</div>';
            if (!empty($ret)) {
                echo '<div style="clear: both; width: 100%; height: 20px;"></div>';
                echo '<p align="center"><input type="submit" name="bbb" value="Apagar Fotos Selecionadas"></p>';
            }
            ?>

        </form>
        <div style="clear: both; width: 100%; height: 10px;"></div>
        <hr>
        <form action="../controller/foto_upload1.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="upload">
                <img src="http://sgiplus.com.br/img/foto_recomenda.png">
                <h2>Enviar Fotos</h2>
                <em>Enviar o arquivo .jpg (Recomendado 2048x1536px QXGA 3MP de até 2Mbytes Max): </em>
                <br><input type="file" multiple="multiple" name="arquivo[]" id="fotos">
                <br><input type="submit" value="enviar fotos">
            </div>
        </form>
        <br>
        <br>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    </body>
</html>