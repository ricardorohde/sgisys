<?php
session_start();
$ref = $_GET['id'];
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
        <link href="css/cadastro.css" rel="stylesheet" />
        <script src="../controller/js/cadastro.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <form action="../controller/imovel_portal_grava.php" method="POST" name="form1" id="form1">
            <input type="hidden" name="ref" id="ref" value="<?php echo $ref; ?>">
            <div style="width: 100%;height: 50px;float: left;"></div>
            <input type="button"  value="Voltar" class="botao" onclick="window.open('cadastro.php?id=<?php echo $ref; ?>', 'frmPrincipal');">
            <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
            <br>
            <br>
            <p>Portais Disponíveis</p>
            <?php
            include '../controller/portal.php';
            $aux = json_decode(portal_listar());
            foreach ($aux as $id) {
                $port = json_decode(portal_carregar($id));
                $sel = '';
                if (publicar_procurar($ref, $id)) {
                    $sel = 'checked';
                }
                echo '<div class="caracteristica"><input type="checkbox" name="' . $id . '" value="S" ' . $sel . '> ' . substr($port->nome_completo, 0, 25) . '</div>';
            }
            ?>
        </form>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>
