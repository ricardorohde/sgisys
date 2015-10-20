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
            <form action="../controller/pagina_detalhe_grava.php" method="POST" name="form1" id="form1">
                <h3>Parâmetros dos Detalhes de Imóveis</h3>
                <div class="botoes-form">
                    <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                </div>
                <?php
                include '../controller/detalhe_config.php';
                $detalhe_config = json_decode(detalhe_config_carregar());
                ?>
                <div class="form-detalhe" id="form-detalhe"> 
                    <fieldset>
                        <legend>Conteúdo da Página</legend>
                        <div class="form-varchar">Cor Fonte Padrão
                            <br><input  class="color" type="text" name="cor_fonte_padrao" id="cor_fonte_padrao" size="6" maxlength="6" value="<?php echo $detalhe_config->cor_fonte_padrao; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Cor Fonte Título
                            <br><input  class="color" type="text" name="cor_fonte_titulo" id="cor_fonte_titulo" size="6" maxlength="6" value="<?php echo $detalhe_config->cor_fonte_titulo; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Cor Fonte Destacada
                            <br><input  class="color" type="text" name="cor_fonte_destacada" id="cor_fonte_destacada" size="6" maxlength="6" value="<?php echo $detalhe_config->cor_fonte_destacada; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Mostrar Galeria de Fotos
                            <br><select name="mostrar_galeria" id="mostrar_galeria">
                                <?php
                                $tmp = $detalhe_config->mostrar_galeria;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sempre mostrar') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Sempre mostrar</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Mostrar Descrição do Imóvel
                            <br><select name="mostrar_descricao" id="mostrar_descricao">
                                <?php
                                $tmp = $detalhe_config->mostrar_descricao;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sempre mostrar') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Sempre mostrar</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Mostrar Características do Imóvel
                            <br><select name="mostrar_caracteristica" id="mostrar_caracteristica">
                                <?php
                                $tmp = $detalhe_config->mostrar_caracteristica;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sempre mostrar') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Sempre mostrar</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Mostrar Mapa do Bairro
                            <br><select name="mostrar_mapa" id="mostrar_mapa">
                                <?php
                                $tmp = $detalhe_config->mostrar_mapa;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sempre mostrar') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Sempre mostrar</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Config Imóveis Semelhantes
                            <br><select name="imoveis_semelhantes" id="imoveis_semelhantes">
                                <?php
                                $tmp = $detalhe_config->imoveis_semelhantes;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Inteligente') {
                                    $sel2 = 'selected';
                                }
                                if ($tmp == 'Destaques') {
                                    $sel3 = 'selected';
                                }
                                if ($tmp == 'Aleatório') {
                                    $sel4 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Inteligente</option>';
                                echo '<option ' . $sel3 . '>Destaques</option>';
                                echo '<option ' . $sel4 . '>Aleatório</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Mostrar Endereço do Imóvel (sem o número)
                            <br><select name="mostrar_endereco" id="mostrar_endereco">
                                <?php
                                $tmp = $detalhe_config->mostrar_endereco;
                                $sel1 = $sel2 = '';
                                if ($tmp == 'Sim') {
                                    $sel2 = 'selected';
                                }
                                if ($tmp == 'Não') {
                                    $sel1 = 'selected';
                                }
                                echo '<option ' . $sel1 . ' value="Não">Não</option>';
                                echo '<option ' . $sel2 . ' value="Sim">Sim se tiver</option>';
                                ?>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Quadro de Solicitação de Contato</legend>
                        <div class="form-varchar">Mostrar Formulário
                            <br><select name="mostrar_contato" id="mostrar_contato">
                                <?php
                                $tmp = $detalhe_config->mostrar_contato;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não mostrar') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sempre mostrar') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não mostrar</option>';
                                echo '<option ' . $sel2 . '>Sempre mostrar</option>';
                                ?>
                            </select>
                        </div>
                        <div class="form-varchar">Cor Fundo
                            <br><input  class="color" type="text" name="detalhe_contato_background" id="detalhe_contato_background" size="6" maxlength="6" value="<?php echo $detalhe_config->detalhe_contato_background; ?>" required="required" style="border: 1px solid red;">
                        </div>
                        <div class="form-varchar">Cor Título
                            <br><input  class="color" type="text" name="detalhe_titulo_background" id="detalhe_titulo_background" size="6" maxlength="6" value="<?php echo $detalhe_config->detalhe_titulo_background; ?>" required="required" style="border: 1px solid red;">
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Página do Corretor</legend>
                        <div class="form-varchar">Exibir
                            <br><select name="pagina_corretor" id="pagina_corretor">
                                <?php
                                $tmp = $detalhe_config->pagina_corretor;
                                $sel1 = $sel2 = $sel3 = $sel4 = '';
                                if ($tmp == 'Não') {
                                    $sel1 = 'selected';
                                }
                                if ($tmp == 'Sim') {
                                    $sel2 = 'selected';
                                }
                                echo '<option ' . $sel1 . '>Não</option>';
                                echo '<option ' . $sel2 . '>Sim</option>';
                                ?>
                            </select>
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>
    </body>
</html>