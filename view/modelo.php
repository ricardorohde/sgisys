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
        <link href="css/forms.css" rel="stylesheet" />
        <link href="css/modelo.css" rel="stylesheet" />
        <script type="text/javascript" src="../controller/js/modelo.js"></script>
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';

        include '../controller/site_config.php';
        $site_config = json_decode(site_config_carregar());

        $mod = $site_config->modelo;
        $sel1 = $sel2 = '';

        if ($mod == 'a') {
            $sel1 = 'checked';
        }
        if ($mod == 'b') {
            $sel2 = 'checked';
        }
        ?>
        <div id="conteudo">

            <h3>Escolha o Modelo de Seu Site</h3>
            <div class="modelo-site">
                <center>
                    <strong>Modelo A</strong>
                    <br><img src="img/sitea.png" width="200" style="cursor:pointer;" title="usar este modelo" onclick="grava_modelo('a');">
                    <p><input type="radio" name="modelo_site" id="a" value="a" <?php echo $sel1; ?> ></p>
                </center>
            </div>
            <div class="modelo-site">
                <center>
                    <strong>Modelo B</strong>
                    <br><img src="img/siteb.png" width="200" style="cursor:pointer;" title="usar este modelo" onclick="grava_modelo('b');">
                    <p><input type="radio" name="modelo_site" id="b" value="b" <?php echo $sel2; ?> ></p>
                </center>
            </div>
        </div>
    </body>
</html>