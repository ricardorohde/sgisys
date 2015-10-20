<?php
session_start();

include '../model/debug.php';

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
    </head>
    <body>
        <form action="../controller/site_imagem_upload1.php" method="post" enctype="multipart/form-data">
            <div class="upload">
                <h2>Enviar Fotos</h2>
                <em>Enviar o arquivo (.jpg 1024x768px @ 2Mbytes Max): </em>
                <br><input type="file" multiple="multiple" name="arquivo[]" id="fotos">
                <br><input type="submit" value="enviar fotos">
            </div>
        </form>
        <br>
        <br>
    </body>
</html>