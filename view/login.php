<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    if (!empty($_SESSION['usuario_id'])) {
        echo '<script>window.open("admin.php","_self");</script>';
        exit();
    }
} else {
    $_SESSION['cliente_id'] = '';
    $_SESSION['usuario_id'] = '';
    unset($_SESSION['cliente_id']);
    unset($_SESSION['usuario_id']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>≡ Sistema SGI Plus Online</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=1200, initial-scale=1" /> 
        <meta name="description" content="SGI Fácil Sistemas para Imobiliárias">
        <meta name="keywords" content="sistema de imobiliária,site,web,internet">
        <link href="favicon.ico" rel="shortcut icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="robots" content="index,follow" />
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/forms.css" rel="stylesheet" />
        <link href="css/login.css" rel="stylesheet" />
        <script>
            if (window.location != window.top.location) {
                window.open(window.location, '_top');
            }
        </script>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-29728591-2', 'sgiplus.com.br');
            ga('send', 'pageview');

        </script>
    </head>
    <body>
        <img src="http://sgiplus.com.br/view/img/casinha_grama.jpeg" width="100%" style="z-index: 100; position: absolute; top: 0; left: 0;">
        <form action="../controller/autentica.php" method="POST">
            <div class="forms form-login" style="box-shadow: 3px 5px 8px #000;left: 20%;margin-left: 0; ">
                <div class="form-titulo">≡ Sistema SGI Plus Online</div>
                <div class="form-linha" style="margin-top: 50px;margin-left: 80px;">
                    Código de Cliente SGI
                    <br><input type="text" name="cliente" id="cliente" size="6" maxlength="4">
                </div>
                <div class="form-linha" style="margin-left: 80px;height: 40px;">
                    Usuário
                    <br><input type="text" name="usuario" id="usuario" size="15" maxlength="15">
                </div>
                <div class="form-linha" style="margin-left: 80px;height: 40px;">
                    Senha
                    <br><input type="password" name="senha" id="senha" size="15" maxlength="15">
                </div>
                <div class="form-linha" style="margin-left: 80px;height: 40px;">
                    <input type="submit" name="bbb" id="bbb" value="entrar">
                </div>
                <div class="form-linha" style="margin-left: 80px;height: 20px;">
                    <a href="javascript: void(0);" onclick="window.open('esqueci.php', 'esqueci', 'width=400,height=350,top=100,left=100,statusbar=no,address=no');">Esqueci minha senha</a>
                </div>
            </div>
        </form>
        <div style="position:absolute;right: 20%;bottom: 5%;background: white;border-radius: 5px; z-index: 110; padding: 10px;border: 4px solid #ccc;box-shadow: 3px 5px 8px #000;">
            <a href="http://www.sgifacil.com.br" title="Desenvolvido por SGI Fácil">
                <img src="http://sgiplus.com.br/view/img/logo_sgi.png" width="100">
            </a>
        </div>
        <div style="position:absolute;left: 20%;bottom: 5%;background: #99cc33;border-radius: 5px; z-index: 110; padding: 10px;border: 4px solid #ccc; cursor: pointer;box-shadow: 3px 5px 8px #000;" onclick="window.open('http://sgiplus.com.br:2095', '_self');">
            <img src="http://sgiplus.com.br/view/img/mensagem.png" width="20"> Clique aqui para acessar os e-mails (webmail) SGI Fácil
        </div>
    </body>
</html>
