<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Configurações do Seu Site</h3>
        </div>
    </body>
</html>


