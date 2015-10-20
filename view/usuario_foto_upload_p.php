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
        <form action="../controller/usuario_foto_upload1_p.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="upload">
                <h2>Enviar Foto (1 apenas)</h2>
                <em>Enviar o arquivo (.jpg 150x100px @ 2Mbytes Max) : </em>
                <br><input type="file" name="arquivo" id="fotos">
                <br><input type="submit" value="envia foto">
            </div>
        </form>
        <br>
        <en>Para excluir a foto, basta clicar em <strong>envia foto</strong> sem ter selecionado nenhuma (vazio).</en>
        <br>
    </body>
</html>