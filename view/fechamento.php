<?php
session_start();
include '../controller/funcoes.php';
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
        <link href="css/fechamento.css" rel="stylesheet" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="../controller/js/fechamento.js"></script>
        <script>
            function form_campos() {

                return true;
            }
        </script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Financeiro';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/fechamento_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Fechamento</h3>

                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('fechamentos.php', '_self');">
                    <?php
                    echo '<input type="submit" value="Gravar" class="botao" onclick="form1.submit();" style="display: none;">';
                    $id = '';
                    $proposta = '';
                    $data_proposta = '';
                    $validade_proposta = '';
                    $valor_proposta = '';
                    $data = date('d/m/Y');
                    $corretor1 = '';
                    $corretor2 = '';
                    $corretor3 = '';
                    $corretor4 = '';
                    $corretor5 = '';
                    $comissao1 = '';
                    $comissao2 = '';
                    $comissao3 = '';
                    $comissao4 = '';
                    $comissao5 = '';
                    $valor_comissao1 = '';
                    $valor_comissao2 = '';
                    $valor_comissao3 = '';
                    $valor_comissao4 = '';
                    $valor_comissao5 = '';
                    $comissao_imobiliaria = '';
                    $valor_comissao_imobiliaria = '';
                    $data_pagamento_imobiliaria = '';
                    $forma_pagamento1 = '';
                    $forma_pagamento2 = '';
                    $forma_pagamento3 = '';
                    $valor_pagamento1 = '';
                    $valor_pagamento2 = '';
                    $valor_pagamento3 = '';
                    $data_pagamento1 = '';
                    $data_pagamento2 = '';
                    $data_pagamento3 = '';
                    $comprador = '';
                    $proprietario = '';
                    $endereco = '';
                    $ref = '';
                    $valor = '';

                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    }
                    if (empty($id)) {
                        $id = 'add';
                    } else {
                        include '../controller/fechamento.php';

                        $fechto = json_decode(fechamento_carregar($id));

                        $proposta = $fechto->proposta;
                        $data_proposta = data_decode($fechto->data_proposta);
                        $validade_proposta = data_decode($fechto->validade_proposta);
                        $valor_proposta = us_br($fechto->valor_proposta);

                        $data = data_decode($fechto->data);

                        $corretor1 = $fechto->corretor1;
                        $corretor2 = $fechto->corretor2;
                        $corretor3 = $fechto->corretor3;
                        $corretor4 = $fechto->corretor4;
                        $corretor5 = $fechto->corretor5;
                        $comissao1 = $fechto->comissao1;
                        $comissao2 = $fechto->comissao2;
                        $comissao3 = $fechto->comissao3;
                        $comissao4 = $fechto->comissao4;
                        $comissao5 = $fechto->comissao5;
                        $valor_comissao1 = us_br($fechto->valor_comissao1);
                        $valor_comissao2 = us_br($fechto->valor_comissao2);
                        $valor_comissao3 = us_br($fechto->valor_comissao3);
                        $valor_comissao4 = us_br($fechto->valor_comissao4);
                        $valor_comissao5 = us_br($fechto->valor_comissao5);
                        $comissao_imobiliaria = $fechto->comissao_imobiliaria;
                        $valor_comissao_imobiliaria = us_br($fechto->valor_comissao_imobiliaria);
                        $data_pagamento_imobiliaria = data_decode($fechto->data_pagamento_imobiliaria);

                        $forma_pagamento1 = $fechto->forma_pagamento1;
                        $forma_pagamento2 = $fechto->forma_pagamento2;
                        $forma_pagamento3 = $fechto->forma_pagamento3;
                        $valor_pagamento1 = us_br($fechto->valor_pagamento1);
                        $valor_pagamento2 = us_br($fechto->valor_pagamento2);
                        $valor_pagamento3 = us_br($fechto->valor_pagamento3);
                        $data_pagamento1 = data_decode($fechto->data_pagamento1);
                        $data_pagamento2 = data_decode($fechto->data_pagamento2);
                        $data_pagamento3 = data_decode($fechto->data_pagamento3);

                        $comprador = $fechto->comprador;
                        $proprietario = $fechto->proprietario;
                        $endereco = $fechto->endereco;
                        $ref = $fechto->ref;
                        $valor = us_br($fechto->valor);
                    }

                    if ($id != 'add') {
                        if ($usu->financeiro == 'Sim') {
                            echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'fechamento.php?id=add\', \'_self\');">';
                            echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                            echo '                window.open(\'../controller/fechamento_exclui.php?id=' . $id . '\', \'_self\');';
                            echo '    }">';
                        }
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Negociação de Fechamento</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <fieldset>
                        <legend>Proposta</legend>
                        <div class="form-varchar">Proposta #
                            <br><input type="number" name="proposta" id="proposta" size="6" maxlength="6" value="<?php echo $proposta; ?>">
                            <input type="button" id="btnPesquisar" value="Pesquisar" onclick="busca_proposta();" style="float: left;">
                        </div>
                        <div id="proposta-dados1" class="proposta-dados">
                            <div class="form-varchar">Data Emissão Proposta
                                <br><input type="text" name="data_proposta" id="data_proposta" size="10" maxlength="10" value="<?php echo $ref; ?>" readonly>
                            </div>
                            <div class="form-varchar">Validade Proposta
                                <br><input type="text" name="validade_proposta" id="validade_proposta" size="10" maxlength="10" value="<?php echo $validade_proposta; ?>" readonly>
                            </div>
                            <div class="form-varchar">Valor Proposta
                                <br><input type="text" name="valor_proposta" id="valor_proposta" size="13" maxlength="13" value="<?php echo $valor_proposta; ?>" readonly style="text-align: right;">
                            </div>
                            <div class="form-varchar">
                                <br>
                                <input type="button" id="btnNovaPesquisa" value="Nova Pesquisa" onclick="nova_pesquisa();" style="float: left;">
                                <input type="button" id="btnSelecionar" value="Selecionar Proposta" onclick="seleciona_proposta();" style="float: left;">
                            </div>
                        </div>
                    </fieldset>
                    <script>document.getElementById('proposta').focus();</script>
                    <div id="proposta-dados2" class="proposta-dados">
                        <fieldset>
                            <legend>Dados Principais</legend>
                            <div class="form-varchar">Data Fechamento
                                <br><input type="text" class="datepicker" name="data" id="data" size="10" maxlength="10" value="<?php echo $data; ?>">
                            </div>
                            <div class="form-varchar">Referência
                                <br><input type="text" name="ref" id="ref" size="6" maxlength="6" value="<?php echo $ref; ?>" readonly>
                            </div>
                            <div class="form-varchar">Imóvel Endereço
                                <br><input type="text" name="endereco" id="endereco" size="50" maxlength="20" value="<?php echo $endereco; ?>" readonly>
                            </div>
                            <div class="form-varchar">Valor Fechado
                                <br><input type="text" name="valor" id="valor" size="13" maxlength="13" value="<?php echo $valor; ?>" style="text-align: right;">
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Interessados</legend>
                            <div class="form-varchar">Proprietário
                                <br><input type="text" name="proprietario" id="proprietario" size="50" maxlength="50" value="<?php echo $proprietario; ?>">
                            </div>
                            <div class="form-varchar">Comprador
                                <br><input type="text" name="comprador" id="comprador" size="50" maxlength="50" value="<?php echo $comprador; ?>">
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Negociação</legend>
                            <div class="form-varchar">Forma Pagamento #1
                                <br><input type="text" name="forma_pagamento1" id="forma_pagamento1" size="30" maxlength="30" value="<?php echo $forma_pagamento1; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_pagamento1" id="valor_pagamento1" size="13" maxlength="13" value="<?php echo $valor_pagamento1; ?>" style="text-align: right;">
                            </div>
                            <div class="form-varchar">Data
                                <br><input type="text" class="datepicker" name="data_pagamento1" id="data_pagamento1" size="10" maxlength="10" value="<?php echo $data_pagamento1; ?>">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Forma Pagamento #2
                                <br><input type="text" name="forma_pagamento2" id="forma_pagamento2" size="30" maxlength="30" value="<?php echo $forma_pagamento2; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_pagamento2" id="valor_pagamento2" size="13" maxlength="13" value="<?php echo $valor_pagamento2; ?>" style="text-align: right;">
                            </div>
                            <div class="form-varchar">Data
                                <br><input type="text" class="datepicker" name="data_pagamento2" id="data_pagamento2" size="10" maxlength="10" value="<?php echo $data_pagamento2; ?>">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Forma Pagamento #3
                                <br><input type="text" name="forma_pagamento3" id="forma_pagamento3" size="30" maxlength="30" value="<?php echo $forma_pagamento3; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_pagamento3" id="valor_pagamento3" size="13" maxlength="13" value="<?php echo $valor_pagamento3; ?>" style="text-align: right;">
                            </div>
                            <div class="form-varchar">Data
                                <br><input type="text" class="datepicker" name="data_pagamento3" id="data_pagamento3" size="10" maxlength="10" value="<?php echo $data_pagamento3; ?>">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                        </fieldset>
                        <fieldset>
                            <legend>Corretores</legend>
                            <div class="form-varchar">Corretor #1
                                <br><select name="corretor1" id="corretor1">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-varchar">% Comissão
                                <br><input type="text" name="comissao1" id="comissao1" size="6" maxlength="6" value="<?php echo $comissao1; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao1" id="valor_comissao1" size="13" maxlength="13" value="<?php echo $valor_comissao1; ?>" style="text-align: right;">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Corretor #2
                                <br><select name="corretor2" id="corretor2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-varchar">% Comissão
                                <br><input type="text" name="comissao2" id="comissao2" size="6" maxlength="6" value="<?php echo $comissao2; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao2" id="valor_comissao2" size="13" maxlength="13" value="<?php echo $valor_comissao2; ?>" style="text-align: right;">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Corretor #3
                                <br><select name="corretor3" id="corretor3">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-varchar">% Comissão
                                <br><input type="text" name="comissao3" id="comissao3" size="6" maxlength="6" value="<?php echo $comissao3; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao3" id="valor_comissao3" size="13" maxlength="13" value="<?php echo $valor_comissao3; ?>" style="text-align: right;">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Corretor #4
                                <br><select name="corretor4" id="corretor4">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-varchar">% Comissão
                                <br><input type="text" name="comissao4" id="comissao4" size="6" maxlength="6" value="<?php echo $comissao4; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao4" id="valor_comissao4" size="13" maxlength="13" value="<?php echo $valor_comissao4; ?>" style="text-align: right;">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">Corretor #5
                                <br><select name="corretor5" id="corretor5">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-varchar">% Comissão
                                <br><input type="text" name="comissao5" id="comissao5" size="6" maxlength="6" value="<?php echo $comissao5; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao5" id="valor_comissao5" size="13" maxlength="13" value="<?php echo $valor_comissao5; ?>" style="text-align: right;">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                            <div class="form-varchar">% Imobiliária
                                <br><input type="text" name="comissao_imobiliaria" id="comissao_imobiliaria" size="6" maxlength="6" value="<?php echo $comissao_imobiliaria; ?>">
                            </div>
                            <div class="form-varchar">Valor
                                <br><input type="text" name="valor_comissao_imobiliaria" id="valor_comissao_imobiliaria" size="13" maxlength="13" value="<?php echo $valor_comissao_imobiliaria; ?>" style="text-align: right;">
                            </div>
                            <div class="form-varchar">Data Pagamento
                                <br><input type="text" class="datepicker" name="data_pagamento_imobiliaria" id="data_pagamento_imobiliaria" size="10" maxlength="10" value="<?php echo $data_pagamento_imobiliaria; ?>">
                            </div>
                            <div style="clear:both; width: 100%; height: 5px; "></div>
                        </fieldset>
                    </div>
                    <div style="clear:both; width: 100%; height: 20px; "></div>
                </div>
            </form>
        </div>
    </body>
</html>                                    