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
        <link href="../view/css/fotos.css" rel="stylesheet" />
        <script src="../controller/js/fotos.js"></script>
    </head>
    <body>
        <form action="../controller/ficha_upload1.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="upload">
                <h2>Enviar Imagem Logo</h2>
                <em>Enviar o arquivo (.jpg 150x60px @ 1Mbyte Max) : </em>
                <br><input type="file" name="arquivo" id="fotos">
                <br><input type="submit" value="enviar">
            </div>
        </form>
    </body>
</html>