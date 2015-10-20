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
 <!--       <link href="css/pagina.css" rel="stylesheet" />-->
        <script src="ckeditor/ckeditor.js"></script>
        <script>
            function form_campos() {

                return true;
            }
        </script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Config';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/pagina_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Páginas de Conteúdo</h3>

                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('paginas.php', '_self');">
                    <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                    <?php
                    $id = '';
                    $opcao = '';
                    $titulo = '';

                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    }
                    if (empty($id)) {
                        $id = 'add';
                    } else {
                        include '../controller/pagina.php';

                        $pagina = json_decode(pagina_carregar($id));

                        $opcao = $pagina->opcao;
                        $titulo = $pagina->titulo;
                        $xpagina = $pagina->pagina;
                        $conteudo = $pagina->conteudo;
                    }

                    if ($id != 'add') {
                        echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'pagina.php?id=add\', \'_self\');">';
                        echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                        echo '                window.open(\'../controller/pagina_exclui.php?id=' . $id . '\', \'_self\');';
                        echo '    }">';
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Conteúdo da Página</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    ID desta Página : <input type="text" name="id" id="id" value="<?php echo $id; ?>" readonly>
                    <div class="form-varchar">Opção no Menu
                        <br><input type="text" name="opcao" id="opcao" size="15" maxlength="15" value="<?php echo $opcao; ?>" required="required" style="border: 1px solid red;">
                    </div>
                    <div class="form-varchar">Título no Navegador
                        <br><input type="text" name="titulo" id="titulo" size="100" maxlength="100" value="<?php echo $titulo; ?>" required="required" style="border: 1px solid red;">
                    </div>
                    <div class="form-varchar">Página Destino
                        <br><input type="text" name="pagina" id="pagina" size="100" maxlength="200" value="<?php echo $xpagina; ?>" required="required" style="border: 1px solid red;">
                    </div>
                    <br>
                    <textarea cols="90" id="xconteudo" name="xconteudo" rows="10"><?php echo $conteudo; ?></textarea>
                    <script>
                        CKEDITOR.config.autoGrow_onStartup = true;
                        CKEDITOR.replace('xconteudo');
                        CKEDITOR.config.width = 980;
                        CKEDITOR.config.height = 480;
                    </script>
                    
                    <div style="clear:both; width: 100%; height: 20px; "></div>
                </div>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>                                    