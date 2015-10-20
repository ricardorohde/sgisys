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
        <link href="css/movimento.css" rel="stylesheet" />
        <script src="../controller/js/movimento.js"></script>
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
            <form action="../controller/movimento_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Movimento</h3>

                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('movimentos.php', '_self');">
                    <?php
                    echo '<input type="submit" value="Gravar" class="botao" onclick="form1.submit();">';
                    $id = '';
                    $data = date('d/m/Y');
                    $tipo = '';
                    $nome = '';
                    $historico = '';
                    $valor = '';

                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    }
                    if (empty($id)) {
                        $id = 'add';
                    } else {
                        include '../controller/movimento.php';

                        $movto = json_decode(movimento_carregar($id));

                        $data = data_decode($movto->data);
                        $tipo = $movto->tipo;
                        $nome = $movto->nome;
                        $historico = $movto->historico;
                        $sentido = $movto->sentido;
                        $valor = us_br($movto->valor);

                        $sen1 = $sen2 = '';
                        if ($sentido == 'Entrada') {
                            $sen1 = 'selected';
                        } else {
                            $sen2 = 'selected';
                        }
                    }

                    if ($id != 'add') {
                        if ($usu->financeiro == 'Sim') {
                            echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'movimento.php?id=add\', \'_self\');">';
                            echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                            echo '                window.open(\'../controller/movimento_exclui.php?id=' . $id . '\', \'_self\');';
                            echo '    }">';
                        }
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Dados do Movimento</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <fieldset>
                        <div class="form-varchar">Data
                            <br><input type="text" class="datepicker" name="data" id="data" size="10" maxlength="10" value="<?php echo $data; ?>">
                        </div>

                        <?php
                        if ($id == 'add') {
                            ?>
                            <div class="form-varchar">Conta
                                <br><select name="tipo" id="tipo" onchange="carrega_nome(this.value);">
                                    <option>Imobiliária</option>
                                    <option>Corretor</option>
                                    <option>Comprador</option>
                                    <option>Proprietário</option>
                                    <option>Usuário</option>
                                </select>
                            </div>
                            <div class="form-varchar">Nome
                                <br><select name="nome" id="nome">
                                </select>
                            </div>
                            <script>carrega_nome('Imobiliária');</script>
                            <?php
                        } else {
                            ?>
                            <div class="form-varchar">Nome
                                <br><input type="tipo" name="tipo" id="nome" size="20" maxlength="20" value="<?php echo $tipo; ?>" readonly>
                                <input type="text" name="nome" id="nome" size="50" maxlength="50" value="<?php echo $nome; ?>" readonly>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-varchar">Historico
                            <br><input type="text" name="historico" id="historico" size="30" maxlength="30" value="<?php echo $historico; ?>">
                        </div>
                        <div class="form-varchar">Sentido
                            <br><select name="sentido" id="sentido">
                                <option value="Entrada" <?php echo $sen1; ?>>Entrada (+)</option>
                                <option value="Saída" <?php echo $sen2; ?>>Saída (-)</option>
                            </select>
                        </div>
                        <div class="form-varchar">Valor
                            <br><input type="text" name="valor" id="valor" size="13" maxlength="13" value="<?php echo $valor; ?>" style="text-align: right;">
                        </div>
                        <div style="clear:both; width: 100%; height: 20px; "></div>
                    </fieldset>
                </div>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    </body>
</html>                                    