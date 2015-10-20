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
    </head>
    <body>
        <form action="../controller/usuario_senha.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="upload">
                <h2>Trocar Minha Senha</h2>
                <br><div class="div-esq-ajax">Senha Atual : </div><input type="password" name="senha_atual" id="senha_atual" size="15" maxlength="15">
                <br><div class="div-esq-ajax">Nova Senha : </div><input type="password" name="nova_senha1" id="nova_senha1" size="15" maxlength="15">
                <br><div class="div-esq-ajax">Repetir : </div><input type="password" name="nova_senha2" id="nova_senha2" size="15" maxlength="15">
                <br><input type="submit" value="Alterar">
            </div>
        </form>
    </body>
</html>