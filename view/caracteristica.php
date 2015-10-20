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
        <link href="css/caracteristica.css" rel="stylesheet" />
        <script>
            function form_campos() {

                return true;
            }
        </script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Portais';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/caracteristica_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Cadastro de Características</h3>

                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('caracteristicas.php', '_self');">
                    <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                    <?php
                    $id = '';
                    $nome = '';
                    $foto = '';
                    $acesso = '';

                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    }
                    if (empty($id)) {
                        $id = 'add';
                    } else {
                        include '../controller/caracteristica.php';

                        $caracteristica = json_decode(caracteristica_carregar($id));

                        $nome = $caracteristica->nome;
                    }

                    if (empty($foto)) {
                        $foto = 'caracteristica_sem_foto.jpg';
                    }

                    if ($id != 'add') {
                        echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'caracteristica.php?id=add\', \'_self\');">';
                        echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                        echo '                window.open(\'../controller/caracteristica_exclui.php?id=' . $id . '\', \'_self\');';
                        echo '    }">';
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Ficha de Cadastro</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <div class="form-varchar">Nome
                        <br><input type="text" name="nome" id="nome" size="32" maxlength="30" value="<?php echo $nome; ?>" required="required" style="border: 1px solid red;" >
                    </div>
                    <div style="clear:both; width: 100%; height: 20px; "></div>
                </div>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>                                    