<?php
session_start();

ini_set('register_globals', 0);

include 'func.php';

if (isset($_GET['tipo_cadastro'])) {
    $tipo_cadastro = $_GET['tipo_cadastro'];
    $_SESSION['tipo_cadastro'] = $tipo_cadastro;
}



include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
$tmp = $_SESSION['tipo_cadastro'] . '_consultar';
if ($usu->$tmp != 'Sim') {
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
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
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="../controller/js/cep.js"></script>
        <script src="../controller/js/cadastro.js"></script>
        <script src="../controller/js/calendar.js"></script>
        <script type="text/javascript">
            function form_campos() {

                for (i = 0; i < form1.length; i++) {
                    campo = form1[i].id;
                    if (campo != '') {
                        if (document.getElementById(campo).required != '' && document.getElementById(campo).value == '') {
                            alert("Preencher TODOS campos obrigatórios.");
                            return false;
                        }
                    }
                }

                return true;
            }

        </script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Cadastros';
        include 'menu.php';
        $name_tipo_cadastro = '';
        if (isset($_SESSION['id_cadastro'])) {
            $tcad = json_decode(tipo_cadastro_carregar($_SESSION['id_cadastro']));
            $name_tipo_cadastro = $tcad->tipo;
            $cor = $tcad->cor;
            echo '<style>';
            echo '.botao {';
            echo '  background: ' . $cor . '!important;';
            echo '  border: 2px solid ' . $cor . '!important;';
            echo '}';
            echo '</style>';
            $tipo_cadastro = $_SESSION['tipo_cadastro'];
            $_SESSION['nome_tipo_cadastro'] = $name_tipo_cadastro;
        }
        if (isset($_GET['tipo_cadastro'])) {
            $tipo_cadastro = $_GET['tipo_cadastro'];
        }
        if ($tipo_cadastro != 'imovel') {
            include '../controller/cadastro.php';
            $camps = cadastro_campo($tipo_cadastro);
            for ($i = 0; $i < count($camps); $i++) {
                $$camps[$i] = '';
            }
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $tipo_campos = json_decode(tipo_campo($tipo_edita));
                if ($cad = json_decode(cadastro_carregar($tipo_cadastro, $_REQUEST['id']))) {
                    for ($i = 0; $i < count($camps); $i++) {
                        $$camps[$i] = $cad->$camps[$i];
                        if ($tipo_campos[$i] == 'DECIMAL') {
                            $$camps[$i] = us_br($$camps[$i]);
                        } elseif ($tipo_campos[$i] == 'DATE') {
                            $$camps[$i] = data_decode($$camps[$i]);
                        }
                    }
                }
                $id = $_GET['id'];
            }
        } else {
            $id = $_GET['id'];

            include '../model/cadastro.php';
            include '../view/func.php';
            $cadastro = new cadastro();
            $camps = $cadastro->campo_imovel();
            $dados = $cadastro->carregar('imovel', $id);
            foreach ($camps as $camp => $value) {
                $$camp = $dados[$camp];
            }

            if (isset($_GET['tipo_nome'])) {
                $tipo_nome = $_GET['tipo_nome'];
            }

            if (empty($tipo_nome)) {
                echo '<script>alert("Erro : Tipo de Imóvel não carregado.");</script>';
            }
        }
        $id = $_GET['id'];
        ?>
        <div id = "conteudo">
            <form action = "../controller/cadastro_grava.php" method = "POST" name = "form1" id = "form1" onsubmit = "return form_campos();">
                <h3>Cadastro de <?php echo $name_tipo_cadastro; ?></h3>
                <div class="botoes-form" style="float:left;height:auto;">
                    <?php
                    echo '<input type="button"  value="Voltar" class="botao" onclick="window.open(\'cadastros.php\', \'_self\');">';
                    $tmp = $_SESSION['tipo_cadastro'] . '_alterar';
                    if ($usu->$tmp == 'Sim') {
                        echo '<input type="submit" value="Gravar" class="botao" onclick="form1.submit();">';
                        echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'cadastro.php?id=add&tipo_nome=' . $tipo_nome . '\', \'_self\');">';
                    }
                    if ($id != 'add' && $usu->$tmp == 'Sim') {
                        $tmp = $_SESSION['tipo_cadastro'] . '_excluir';
                        if ($usu->$tmp == 'Sim') {
                            echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                            echo '                window.open(\'../controller/cadastro_exclui.php?id=' . $id . '\', \'_self\');';
                            echo '    }">';
                        }
                        if ($tipo_cadastro == 'imovel') {
                            echo '<input type = "button" value = "Imprimir" class = "botao" onclick = "if (confirm(\'Imprimir mostrando o nome do proprietário?\')) { window.open(\'cadastro_imprime_ficha.php?id=' . $id . '&prop=S\',\'_blank\', \'width=800,height=800,menubar=no,status=no\');} else { window.open(\'cadastro_imprime_ficha.php?id=' . $id . '\',\'_blank\', \'width=800,height=800,menubar=no,status=no\');}">';
                            echo '<input type = "button" value = "Características" class = "botao" onclick = "caracteristicas();">';
                            echo '<input type = "button" value = "Portais" class = "botao" onclick = "portais();">';
                        } else {
                            echo '<input type = "button" value = "Imprimir" class = "botao" onclick = "window.open(\'cadastro_imprime_ficha.php?id=' . $id . '\',\'_blank\', \'width=800,height=800,menubar=no,status=no\');">';
                        }
                    }
                    ?>
                </div>

                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Ficha de Cadastro de Cliente</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <?php
                    include '../controller/tabela.php';
                    $camps_tabela = json_decode(tabela_carregar($tipo_cadastro));
                    $grupo = '';
                    if ($id != 'add') {
                        if ($tipo_cadastro == 'imovel') {
                            echo '<div class="botao-extra">';
                            echo '  <input type = "button" value = "Atualizações" class = "botao" onclick = "atualizacoes();">';
                            echo '</div>';
                        } elseif ($tipo_cadastro == 'comprador') {
                            echo '<div class="botao-extra">';
                            echo '  <input type = "button" value = "Ofertas" class = "botao" onclick = "ofertas();">';
                            echo '</div>';
                        }
                    }
                    echo '<fieldset>';
                    $x = 0;
                    foreach ($camps_tabela as $camp_tabela) {
                        if ($x == 0 && $tipo_cadastro == 'imovel' && $id != 'add') {
                            echo '<div class="cadastro-referencia">Referência | <input type="text" value="' . str_pad($id, 8, '0', 0) . '" size="8" readonly style="text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                        }
                        if (!empty($camp_tabela->campo_grupo)) {
                            $camp = $camp_tabela->campo;
                            $func = $camp_tabela->funcao2;
                            if (!empty($func)) {
                                $func = 'onchange="' . $func . '"';
                            }
                            $func2 = $camp_tabela->funcao;
                            $readonly = '';
                            $required = '';
                            if (isset($camp_tabela->somente_leitura)) {
                                if ($camp_tabela->somente_leitura == 'S') {
                                    $readonly = 'readonly';
                                }
                            }
                            if (isset($camp_tabela->obrigatorio)) {
                                if ($camp_tabela->obrigatorio == 'S') {
                                    $required = 'required="required" style="border: 1px solid red;"';
                                }
                            }
                            if ($grupo != $camp_tabela->campo_grupo) {
                                $grupo = $camp_tabela->campo_grupo;
                                if ($x > 0) {
                                    echo '</fieldset><fieldset>';
                                }
                                echo '<legend>' . $grupo . '</legend>';
                            }
                            if (empty($camp_tabela->tabela_vinculo)) {
                                if (empty($camp_tabela->campo_opcao)) {
                                    if ($camp_tabela->campo_tipo == 'ID') {
                                        echo '  <input type="hidden" name="id" id="id" value="' . $id . '">';
                                    } elseif ($camp_tabela->campo_tipo == 'VARCHAR') {
                                        echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                        echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                        if ($camp_tabela->campo == 'video_youtube' && !empty($$camp)) {
                                            if (strpos($$camp, 'youtube.com')) {
                                                echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $$camp) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                            } else {
                                                echo '&nbsp;(!) Endereço do Youtube inválido. Utilizar http://www.youtube.com...';
                                            }
                                        }
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'DATE') {
                                        echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                        echo '  <br><input type="text"  class="datepicker" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . data_decode($$camp) . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'INT') {
                                        echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                        echo '  <br><input type="number" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . ' onblur="if (this.value === \'\'){ this.value=\'0\' }">';
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'TEXT') {
                                        echo '<div class="form-textarea">' . $camp_tabela->campo_nome;
                                        echo '  <br><textarea name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" rows="15" cols="50" ' . $readonly . ' ' . $required . ' ' . $func . '>' . $$camp . '</textarea>';
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'DECIMAL') {
                                        echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                        echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '"';
                                        if ($$camp > 0) {
                                            echo ' value="' . number_format($$camp, 2, ',', '.') . '" ';
                                        } else {
                                            echo ' value="0,00" ';
                                        }
                                        echo $readonly . ' ' . $required . ' ' . $func . ' style="text-align:right;"  onblur="if (this.value === \'\'){ this.value=\'0,00\' }">';
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'CHECKBOX') {
                                        echo '<div class="form-varchar">';
                                        $sel = '';
                                        if ($$camp == 'S') {
                                            $sel = 'checked';
                                        }
                                        echo '  <br><input type="checkbox" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" value="S" ' . $sel . ' ' . $func . '> ' . $camp_tabela->campo_nome;
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'FOTO') {
                                        include '../controller/imovel_foto.php';
                                        $fotos = json_decode(imovel_foto_listar($id));
                                        if (empty($fotos)) {
                                            $foto1 = 'sem_foto.jpg';
                                        } else {
                                            $foto = json_decode(imovel_foto_carregar($fotos[0]));
                                            $foto1 = $foto->foto;
                                        }
                                        echo '<input type="hidden" name="foto" id="foto" value="' . $foto1 . '">';
                                        if ($id != 'add') {
                                            echo '  <div id="foto-detalhe" onclick="mostra_fotos();">';
                                        } else {
                                            echo '  <div id="foto-detalhe" onclick="alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                        }
                                        echo '      <img src="../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width="460" title="Clique aqui para Abrir a Galeria de Fotos">';
                                        echo '  </div>';
                                    } elseif ($camp_tabela->campo_tipo == 'FOTOP') {
                                        echo '<input type="hidden" name="foto" id="foto" value="' . $foto . '">';
                                        echo '<div id="foto-detalhe-p" ';
                                        if ($id != 'add') {
                                            echo ' onclick="window.open(\'cadastro_foto_upload_p.php?id=' . $id . '\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"';
                                        }
                                        echo ' >';
                                        echo '  <img src="../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto . '" width="100" title="Clique aqui para Fazer Upload de uma foto">';
                                        echo '</div>';
                                    } elseif ($camp_tabela->campo_tipo == 'DATALIST') {
                                        echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                        echo '  <br><input list="data_' . $camp_tabela->campo . '" type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                        echo '  <datalist id="data_' . $camp_tabela->campo . '"></datalist>';
                                        echo '</div>';
                                        if (!empty($func2)) {
                                            echo '<script>' . $func2 . '</script>';
                                        }
                                    } elseif ($camp_tabela->campo_tipo == 'SCRIPT') {
                                        echo '<script src="../controller/js/' . $func . '"></script>';
                                    } elseif ($camp_tabela->campo_tipo == 'OCORRENCIA') {
                                        if ($id != 'add') {
                                            echo '<iframe name="frmOcorrs" src="ocorrencias.php?ref=' . $id . '" width="1120" height="900" frameborder="no"></iframe>';
                                        }
                                    }
                                    $x++;
                                } else {
                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                    $prevalor = '';
                                    if ($camp_tabela->campo_tipo == 'DATALIST') {
                                        echo '  <br><input list="data_' . $camp_tabela->campo . '" type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                        echo '  <datalist id="data_' . $camp_tabela->campo . '">';
                                    } else {
                                        echo '  <br><select name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" ' . $func . ' ' . $required . ' >';
                                        if (empty($required)) {
                                            echo '      <option></option>';
                                        }
                                    }
                                    $vinculo_tabela = explode('|', $camp_tabela->campo_opcao);
                                    foreach ($vinculo_tabela as $camp_vinculo) {
                                        $sel = '';
                                        if ($$camp == $camp_vinculo) {
                                            $sel = 'selected';
                                        }
                                        echo '      <option value="' . $camp_vinculo . '" ' . $sel . '>' . $camp_vinculo . '</option>';
                                        if (empty($prevalor)) {
                                            $prevalor = $camp_vinculo;
                                        }
                                    }
                                    if ($camp_tabela->campo_tipo == 'DATALIST') {
                                        echo '  </datalist>';
                                    } else {
                                        echo '  </select>';
                                    }
                                    if (!empty($func2)) {
                                        echo '<script>' . $func2 . '</script>';
                                    }
                                    if (!empty($required) && empty($$camp)) {
                                        echo '<script>document.getElementById(\'' . $camp_tabela->campo . '\').value="' . $prevalor . '";</script>';
                                    }
                                    echo '</div>';
                                    $x++;
                                }
                            } else {
                                $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
                                $tmp = $camp_tabela->tabela_vinculo . '_consultar';
                                echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                if (trim($usu->$tmp) == 'Sim') {
                                    if ($camp_tabela->campo_tipo == 'DATALIST') {
                                        echo '  <br><input list="data_' . $camp_tabela->campo . '" type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                        echo '  <datalist id="data_' . $camp_tabela->campo . '">';
                                    } else {
                                        echo '  <br><select name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" ' . $func . ' ' . $required . ' >';
                                        if (empty($required)) {
                                            echo '      <option></option>';
                                        }
                                    }
                                    $vinculo_tabela = json_decode(tabela_vinculo($camp_tabela->tabela_vinculo, $camp_tabela->tabela_texto));
                                    foreach ($vinculo_tabela as $camp_vinculo) {
                                        $valor = $camp_tabela->tabela_valor;
                                        $texto = $camp_tabela->tabela_texto;
                                        if (!empty($camp_vinculo->$valor)) {
                                            $sel = '';
                                            if ($$camp == $camp_vinculo->$valor) {
                                                $sel = 'selected';
                                            }
                                            echo '      <option value="' . $camp_vinculo->$valor . '" ' . $sel . '>' . $camp_vinculo->$texto . '</option>';
                                        }
                                    }
                                    if ($camp_tabela->campo_tipo == 'DATALIST') {
                                        echo '  </datalist>';
                                    } else {
                                        echo '  </select>';
                                    }
                                    if ($camp_tabela->campo == 'proprietario' || $camp_tabela->campo == 'captador1' || $camp_tabela->campo == 'captador2') {
                                        $camp_id = $$camp;
                                        if (empty($camp_id)) {
                                            $camp_id = 'add';
                                        }
                                        echo '&nbsp;<a href="javascript: void(0);" onclick="quadro_edita(\'' . $camp_tabela->tabela_vinculo . '\',\'' . $camp_tabela->campo . '\',document.getElementById(\'' . $camp_tabela->campo . '\').value);" title="Clique para + editar"><img src="../img/edit_add.png" width="20"></a>';
                                    }
                                    if (!empty($func2)) {
                                        echo '<script>' . $func2 . '</script>';
                                    }
                                } else {
                                    $tmp = tabela_carregar_campo($camp_tabela->tabela_vinculo, $camp_tabela->tabela_texto, $$camp);
                                    echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="25" value="' . $tmp . '" readonly class="desativado">';
                                }
                                echo '</div>';
                                $x++;
                            }
                        }
                    }
                    echo '</fieldset>';
                    ?>
                    <div style="clear:both; width: 100%;height: 20px;"></div>
                </div>
                <div style="clear:both; width: 100%;height: 20px;"></div>
        </div>
    </form>
</div>
<div id="quadro-edita">
    <div class="form-titulo" style="background: orange;">
        Editar Cadastro
    </div>';
    <div style="float:left;margin: 10px;">
        <iframe src="about:blank" width="900" height="550" frameborder="no" name="frmEdita"></iframe>
    </div>

</div>
<div id="fundo-negro" onclick="oculta_fotos('<?php echo $_GET['id']; ?>');"></div>
<?php
if ($_SESSION['tipo_cadastro'] == 'imovel') {
    echo '<div class="div-flutuante" id="div-fotos">';
    echo '    <div class="form-titulo">';
    echo '        Fotos do Imóvel';
    echo '        <div style="position: absolute;top: 5px;right: 25px;"><a href="javascript: void(0);" onclick="oculta_fotos(\'' . $id . '\');">[ Fechar X ]</a></div>';
    echo '    </div>';
    echo '    <div style="width: 100%;height: 35px;float: left;"></div>';
    echo '    <iframe width="100%" height="100%" frameborder="no" scrolling="vertical" src="cadastro_foto_upload.php?id=' . $id . '"></iframe>';
    echo '</div>';
    $kad = new cadastro();
}
?>
</div>

</body>
</html>
