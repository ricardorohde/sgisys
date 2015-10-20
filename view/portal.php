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
    </head>
    <body>
        <?php
        $m = 'Portais';
        include 'menu.php';

        $nome = $_GET['nome'];

        include '../controller/portal.php';
        $port = json_decode(portal_carregar($nome));
        ?>
        <div id="conteudo">

            <h3>Publicar em <?php echo $port->nome_completo; ?> </h3>

            <?php
            if ($nome == 'FACEBOOK') {
                echo '<a href="http://facebook.com" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_facebook.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="http://sgifbapp.com.br/adm.php?cliente_id=' . $_SESSION['cliente_id'] . '" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'IMOVELWEB') {
                echo '<a href="http://imovelweb.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_imovelweb.jpg" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="imovelweb.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'VIVAREAL') {
                echo '<a href="http://vivareal.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_vivareal.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="vivareal.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == '123I') {
                echo '<a href="http://123i.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_123i.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="123i.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'ZAP') {
                echo '<a href="http://zap.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_zap.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="zap.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'SPIMOVEL') {
                echo '<a href="http://grupospimovel.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_spimovel.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="spimovel.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'TIQUE') {
                echo '<a href="http://www.tiqueimoveis.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_tiqueimoveis.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="tique.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'CRECI') {
                echo '<a href="http://www.portalcreci.org.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_portalcreci.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="creci.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'MERCADOLIVRE') {
                echo '<a href="http://www.mercadolivre.com.br" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_mercadolivre.png" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="mercadolivre.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'JAU') {
                echo '<a href="http://www.casajau.com.br/" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_jau.png" style="margin-left: 40px;" width="40"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="jau.php" frameborder="no" width="100%" height="600"></iframe>';
            } elseif ($nome == 'YMOVEIS') {
                echo '<a href="http://ymoveis.com/" target="frmPortal"><img src="http://sgiplus.com.br/img/logo_ymoveis.jpg" style="margin-left: 40px;" width="150"></a>';
                echo '<iframe name="frmPortal" id="frmPortal" src="ymoveis.php" frameborder="no" width="100%" height="600"></iframe>';
            }
            ?>

        </div>
    </body>
</html>