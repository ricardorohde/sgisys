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
        <form action="../controller/pagina_home_upload1.php" method="post" enctype="multipart/form-data">
            <div class="upload">
                <h2>Enviar Imagem (1 apenas)</h2>
                <em>Enviar o arquivo (.jpg @ 2Mbytes Max) : </em>
                <br><input type="file" name="arquivo">
                <br><input type="submit" value="enviar">
            </div>
        </form>
        <br>
        <br>
    </body>
</html>