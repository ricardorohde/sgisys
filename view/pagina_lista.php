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
        <script type="text/javascript" src="../controller/js/jscolor/jscolor.js"></script>
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/pagina_lista_grava.php" method="POST" name="form1" id="form1">
                <h3>Parâmetros de Listagem de Imóveis</h3>
                <div class="botoes-form">
                    <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                </div>
                <?php
                include '../controller/lista_config.php';
                $lista_config = json_decode(lista_config_carregar());
                ?>
                <div class="form-detalhe" id="form-detalhe"> 
                    <fieldset>
                        <legend>Conteúdo da Lista</legend>
                        <div class="form-varchar">Cor Fonte Padrão
                            <br><input  class="color" type="text" name="cor_fonte_padrao" id="cor_fonte_padrao" size="6" maxlength="6" value="<?php echo $lista_config->cor_fonte_padrao; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Cor Fonte Destacada
                            <br><input  class="color" type="text" name="cor_fonte_destacada" id="cor_fonte_destacada" size="6" maxlength="6" value="<?php echo $lista_config->cor_fonte_destacada; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Qtd Itens por Página
                            <br><select name="itenspp" id="itenspp">
                                <?php
                                $tmp = $lista_config->itenspp;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == '4') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == '8') {
                                    $sel2 = 'selected';
                                }
                                if ($tmp == '10') {
                                    $sel3 = 'selected';
                                }
                                if ($tmp == '15') {
                                    $sel3 = 'selected';
                                }
                                if ($tmp == '20') {
                                    $sel5 = 'selected';
                                }
                                echo '<option value="4" ' . $sel1 . '>4 imóveis por página</option>';
                                echo '<option value="8" ' . $sel2 . '>8 imóveis por página</option>';
                                echo '<option value="10" ' . $sel3 . '>10 imóveis por página</option>';
                                echo '<option value="15" ' . $sel4 . '>20 imóveis por página</option>';
                                echo '<option value="20" ' . $sel5 . '>20 imóveis por página</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Cor Fundo Imóvel
                            <br><input  class="color" type="text" name="cor_imovel_background" id="cor_imovel_background" size="6" maxlength="6" value="<?php echo $lista_config->cor_imovel_background; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Cor Fundo Filtros
                            <br><input  class="color" type="text" name="cor_filtro_background" id="cor_filtro_background" size="6" maxlength="6" value="<?php echo $lista_config->cor_filtro_background; ?>" required="required" style="border: 1px solid red;">
                        </div>
                    </fieldset>  
                </div>
            </form>
        </div>
    </body>
</html>