<?php
session_start();

$tipo_edita = '';
$campo_edita = '';
$id = '';

include './func.php';

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
}
if (isset($_REQUEST['tipo_edita'])) {
    $tipo_edita = $_REQUEST['tipo_edita'];
}

if (isset($_REQUEST['campo_edita'])) {
    $campo_edita = $_REQUEST['campo_edita'];
}
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
        <script src="../controller/js/cep.js"></script>
        <script src="../controller/js/cadastro.js"></script>
        <script src="../controller/js/calendar.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
            <?php
            include '../controller/usuario_acesso.php';
            $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
            $tmp = $tipo_edita . '_consultar';
            if ($usu->$tmp != 'Sim') {
                echo '<script>alert("Você não tem permissões para essa operação.");</script>';
                echo '<script>window.top.frmPrincipal.$(\'#quadro-edita\').fadeOut(500);window.top.frmPrincipal.$(\'#fundo-negro\').fadeOut(1000);</script>';
                exit();
            }
            include '../controller/tabela.php';

            $tabela = json_decode(tabela_campo($_SESSION['tipo_cadastro'], $campo_edita));

            include '../controller/cadastro.php';
            $camps = cadastro_campo($tipo_edita);
            for ($i = 0; $i < count($camps); $i++) {
                $$camps[$i] = '';
            }

            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $tipo_campos = json_decode(tipo_campo($tipo_edita));
                if ($cad = json_decode(cadastro_carregar($tipo_edita, $_REQUEST['id']))) {
                    for ($i = 0; $i < count($camps); $i++) {
                        $$camps[$i] = $cad->$camps[$i];
                        if ($tipo_campos[$i] == 'DECIMAL') {
                            $$camps[$i] = us_br($$camps[$i]);
                        } elseif ($tipo_campos[$i] == 'DATE') {
                            $$camps[$i] = data_decode($$camps[$i]);
                        }
                    }
                }
            }
            if (empty($id)) {
                $id = 'add';
            }
//
            echo '<form action="../controller/cadastro_grava2.php" method="POST" name="form1" id="form1">';
            echo '<div style="width: 100%;height: 20px;float: left;"></div>';
            echo '<input type="hidden" name="tipo_edita" value="' . $tipo_edita . '">';
            echo '<div style="width: 100%; height: auto;">';
//  
            $idd = $id;
            if ($idd == 'add') {
                $idd = '';
            }
            echo '<input type="button" value="Voltar" class="botao" onclick="edita_voltar(\'' . $tipo_edita . '\',\'' . $campo_edita . '\',\'' . $idd . '\',\'' . $tabela->tabela_texto . '\');">';
            if ($id != 'add') {
                $tmp = $tipo_edita . '_alterar';
                if ($usu->$tmp == 'Sim') {
                    echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'cadastro_edita.php?tipo_edita=' . $tipo_edita . '&campo_edita=' . $campo_edita . '&id=add\', \'_self\');">';
                }
            }
            $tmp = $tipo_edita . '_alterar';
            if ($usu->$tmp == 'Sim') {
                echo '<input type="submit" value="Gravar" class="botao" onclick="form1.submit();">';
            }
            echo '<input type="button" value="Procurar" class="botao" onclick="window.open(\'cadastro_lista.php?tipo_edita=' . $tipo_edita . '&campo_edita=' . $campo_edita . '&campo_valor=' . $tabela->tabela_texto . '&id=' . $idd . '\',\'_self\');">';
            echo '  <input type="hidden" name="tipo_edita" value="' . $tipo_edita . '">';
            echo '  <input type="hidden" name="campo_edita" value="' . $campo_edita . '">';
            include '../controller/tabela.php';
            $camps_tabela = json_decode(tabela_carregar($tipo_edita));
            $grupo = '';
            echo '<fieldset>';
            $x = 0;
            foreach ($camps_tabela as $campo_tabela) {
                if (!empty($campo_tabela->campo_grupo)) {
                    $campo = $campo_tabela->campo;
                    $funcao = $campo_tabela->funcao;
                    $funcao2 = $campo_tabela->funcao2;
                    $somente_leitura = '';
                    $obrigatorio = '';
                    if (isset($campo_tabela->somente_leitura)) {
                        if ($campo_tabela->somente_leitura == 'S') {
                            $somente_leitura = 'readonly';
                        }
                    }
                    if (isset($campo_tabela->obrigatorio)) {
                        if ($campo_tabela->obrigatorio == 'S') {
                            $obrigatorio = 'required="required" style="border: 1px solid red;"';
                        }
                    }
                    if ($grupo != $campo_tabela->campo_grupo) {
                        $grupo = $campo_tabela->campo_grupo;
                        if ($x > 0) {
                            echo '</fieldset><fieldset>';
                        }
                        echo '<legend>' . $grupo . '</legend>';
                    }
                    if (empty($campo_tabela->tabela_vinculo)) {
                        if (empty($campo_tabela->campo_opcao)) {
                            if ($campo_tabela->campo_tipo == 'ID') {
                                echo '  <input type="hidden" name="id" id="id" value="' . $id . '">';
                            } elseif ($campo_tabela->campo_tipo == 'VARCHAR') {
                                echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                                echo '  <br><input type="text" name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" size="' . $campo_tabela->campo_tamanho . '" maxlength="' . $campo_tabela->campo_max . '" value="' . $$campo . '" ' . $somente_leitura . ' ' . $obrigatorio . ' ' . $funcao . '>';
                                echo '</div>';
                            } elseif ($campo_tabela->campo_tipo == 'DATE') {
                                echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                                echo '  <br><input type="text"  class="datepicker" name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" size="' . $campo_tabela->campo_tamanho . '" maxlength="' . $campo_tabela->campo_max . '" value="' . $$campo . '" ' . $somente_leitura . ' ' . $obrigatorio . ' ' . $funcao . '>';
                                echo '</div>';
                            } elseif ($campo_tabela->campo_tipo == 'INT') {
                                echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                                echo '  <br><input type="number" name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" size="' . $campo_tabela->campo_tamanho . '" maxlength="' . $campo_tabela->campo_max . '" value="' . $$campo . '" ' . $somente_leitura . ' ' . $obrigatorio . ' ' . $funcao . '>';
                                echo '</div>';
                            } elseif ($campo_tabela->campo_tipo == 'TEXT') {
                                echo '<div class="form-textarea">' . $campo_tabela->campo_nome;
                                echo '  <br><textarea name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" rows="5" cols="50" ' . $somente_leitura . ' ' . $obrigatorio . ' ' . $funcao . '>' . $$campo . '</textarea>';
                                echo '</div>';
                            } elseif ($campo_tabela->campo_tipo == 'DECIMAL') {
                                echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                                echo '  <br><input type="text" name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" size="' . $campo_tabela->campo_tamanho . '" maxlength="' . $campo_tabela->campo_max . '"';
                                if ($$campo > 0) {
                                    echo ' value="' . number_format($$campo, 2, ',', '.') . '" ';
                                } else {
                                    echo ' value="0,00" ';
                                }
                                echo $somente_leitura . ' ' . $obrigatorio . ' ' . $funcao . '>';
                                echo '</div>';
                            } elseif ($campo_tabela->campo_tipo == 'CHECKBOX') {
                                echo '<div class="form-varchar">';
                                $sel = '';
                                if ($$campo == 'S') {
                                    $sel = 'checked';
                                }
                                echo '  <br><input type="checkbox" name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" value="S" ' . $sel . ' ' . $funcao . '> ' . $campo_tabela->campo_nome;
                                echo '</div>';
                            }
                            $x++;
                        } else {
                            echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                            echo '  <br><select name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" ' . $funcao . ' ' . $obrigatorio . ' >';
                            if (empty($obrigatorio)) {
                                echo '      <option></option>';
                            }
                            $tabela_vinculo = explode('|', $campo_tabela->campo_opcao);
                            foreach ($tabela_vinculo as $campo_vinculo) {
                                $sel = '';
                                if ($$campo == $campo_vinculo) {
                                    $sel = 'selected';
                                }
                                echo '      <option value="' . $campo_vinculo . '" ' . $sel . '>' . $campo_vinculo . '</option>';
                            }
                            echo '  </select>';
                            if (!empty($funcao2)) {
                                echo '<script>' . $funcao2 . '</script>';
                            }
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="form-varchar">' . $campo_tabela->campo_nome;
                        echo '  <br><select name="' . $campo_tabela->campo . '" id="' . $campo_tabela->campo . '" ' . $funcao . ' ' . $obrigatorio . ' >';
                        if (empty($obrigatorio)) {
                            echo '      <option></option>';
                        }
                        $tabela_vinculo = json_decode(tabela_vinculo($campo_tabela->tabela_vinculo));
                        foreach ($tabela_vinculo as $campo_vinculo) {
                            $valor = $campo_tabela->tabela_valor;
                            $texto = $campo_tabela->tabela_texto;
                            if (!empty($campo_vinculo->$valor)) {
                                $sel = '';
                                if ($$campo == $campo_vinculo->$valor) {
                                    $sel = 'selected';
                                }
                                echo '      <option value="' . $campo_vinculo->$valor . '" ' . $sel . '>' . $campo_vinculo->$texto . '</option>';
                            }
                        }
                        echo '  </select>';
                        if (!empty($funcao2)) {
                            echo '<script>' . $funcao2 . '</script>';
                        }
                        echo '</div>';
                    }
                }
            }
            echo '</fieldset>';
            echo '</div>';
            ?>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>