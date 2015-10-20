<!DOCTYPE html>
<html>
    <head>
        <title>≡ Sistema SGI Plus Online - Esquecí minha senha...</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=1200, initial-scale=1" /> 
        <meta name="description" content="SGI Fácil Sistemas para Imobiliárias">
        <meta name="keywords" content="sistema de imobiliária,site,web,internet">
        <link href="favicon.ico" rel="shortcut icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="robots" content="index,follow" />
        <meta name="author" content="Carlos Renato Gaddini, http://sgifacil.com.br" />
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/forms.css" rel="stylesheet" />
        <link href="css/login.css" rel="stylesheet" />
    </script>
</head>
<body>
    <form action="../controller/esqueci.php" method="POST">
        <div class="forms form-login" style="box-shadow: 3px 5px 8px #000;left: 40px;margin-left: 0px;top: 120px; ">
            <div class="form-titulo">Recuperação de Senha</div>
            <div class="form-linha" style="margin-top: 50px;margin-left: 80px;">
                Código de Cliente SGI
                <br><input type="text" name="cliente" id="cliente" size="6" maxlength="4">
            </div>
            <div class="form-linha" style="margin-left: 80px;height: 40px;">
                Usuário
                <br><input type="text" name="usuario" id="usuario" size="15" maxlength="15">
            </div>
            <div class="form-linha" style="margin-left: 80px;height: 40px;">
                <input type="submit" name="bbb" id="bbb" value="enviar">
            </div>
            <p>Será enviado para o email do gerente um link para resetar a senha do usuário indicado.</p>
        </div>
    </form>
</body>
</html>
