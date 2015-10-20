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
        <link href="css/config.css" rel="stylesheet" />
        <script src="../controller/js/ficha.js"></script>
    </head>
    <body>
        <?php
        $m = 'Tabelas';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Detalhes da Ficha de Impressão</h3>
            <?php
            include '../controller/ficha_config.php';
            $ficha_config = json_decode(ficha_config_carregar());

            $xlogo = $ficha_config->logo;
            if (empty($xlogo)) {
                $xlogo = 'sem_foto.jpg';
            }
            if (isset($_GET['logo'])) {
                $xlogo = $_GET['logo'];
            }
            $logo = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $xlogo;
            $link = '<a href="javascript: void(0);" onclick="window.open(\'ficha_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"  title="Clique aqui para Fazer Upload de uma imagem">';
            ?>
            <div class="form-detalhe" id="form-detalhe"> 
                <div class="form-textarea">Logo (Usar 150 x 60 Px)
                    <br><?php echo $link; ?><img src="<?php echo $logo; ?>" width="150" height="60"></a>
                    <input type="hidden" name="logo" id="logo" value="<?php echo $xlogo; ?>" onchange="grava_ficha();">
                </div>
                <div class="form-varchar">Linha (1)
                    <br><input type="text" name="texto1" id="texto1" size="100" maxlength="100" value="<?php echo $ficha_config->texto1; ?>" onchange="grava_ficha();">
                </div>
                <div class="form-varchar">Linha (2)
                    <br><input type="text" name="texto2" id="texto2" size="100" maxlength="100" value="<?php echo $ficha_config->texto2; ?>" onchange="grava_ficha();">
                </div>
            </div>
        </div>
    </body>
</html>