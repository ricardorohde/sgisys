<?php
session_start();

ini_set('register_globals', 0);

include 'func.php';

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
            include '../model/imovel_foto.php';
            include '../view/func.php';


            $imovel_foto = new imovel_foto();
            $imovel_foto->testa_fachada($id);

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
					if ($id != 'add' && $tipo_cadastro == 'imovel') {
						echo '<input type = "button" value = "Enviar por email" class = "botao" onclick = "document.getElementById(\'envia-email\').style.display=\'block\';">';
					}
                    ?>
                </div>
                <?php
                if ($tipo_cadastro != 'imovel') {
                    ?>
                    <div class="form-detalhe" id="form-detalhe">
                        <div class="form-titulo">Ficha de Cadastro</div>
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
                            } elseif ($tipo_cadastro == 'comprador' && !empty($nome)) {
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
                                                    echo '&nbsp; Utilizar http://www.youtube.com...';
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
                                            echo '<iframe src="http://www.terra.com.br" width="1000" height="500"></iframe>';
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
                                    if (trim($usu->$tmp) == 'Sim' || $_SESSION['usuario_nome'] == 'ADMIN') {
                                        if ($camp_tabela->campo_tipo == 'DATALIST') {
                                            echo '  <br><input list="data_' . $camp_tabela->campo . '" type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                            echo '  <datalist id="data_' . $camp_tabela->campo . '">';
                                        } else {
                                            echo '  <br><select name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" ' . $func . ' ' . $required . ' >';
                                            if (empty($required)) {
                                                echo '      <option></option>';
                                            }
                                        }
                                        $vinculo_tabela = json_decode(tabela_vinculo_unico($camp_tabela->tabela_vinculo, $camp_tabela->tabela_valor));

                                        foreach ($vinculo_tabela as $vinculo_id) {
                                            $idx = $camp_tabela->tabela_valor;
                                            $vinculo_idx = $vinculo_id->$idx;

                                            if ($vinculo_idx) {
                                                $tmp = (array) json_decode(cadastro_listar($camp_tabela->tabela_vinculo, " and $camp_tabela->tabela_valor='$vinculo_idx' ", $order, $rows));
                                                $camp_vinculo = tabela_carregar_campo($camp_tabela->tabela_vinculo, $camp_tabela->tabela_texto, $tmp[0]);
                                                $sel = '';
                                                if ($$camp == $vinculo_idx) {
                                                    $sel = 'selected';
                                                }
                                                echo '      <option value="' . $vinculo_idx . '" ' . $sel . '>' . $camp_vinculo . '</option>';
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


                    <?php
                } else { //imóvel
                    if ($tipo_nome == 'Casas') {
                        ?>
                        <div class="form-detalhe" id="form-detalhe" style="height: 1060px;">
                            <div class="imovel2-foto">
                                <?php
                                $foto1 = $foto;
                                if (empty($foto1)) {
                                    $foto1 = 'sem_foto.jpg';
                                } else {
                                    if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                        $foto1 = 'sem_foto.jpg';
                                    }
                                }
                                echo '<input type = "hidden" name = "foto" id = "foto" value="' . $foto1 . '">';
                                echo '<input type = "hidden" name = "tipo_nome" id="tipo_nome" value="' . $tipo_nome . '">';
                                if ($id != 'add') {
                                    echo ' <div id="foto2-detalhe" onclick = "mostra_fotos();">';
                                } else {
                                    echo ' <div id="foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                }
                                echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width="365" height="365" style="margin-top: -5px;margin-left: -5px;" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                ?>
                            </div>
                            <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                            <div style="width: 100%;height: 50px;float: left;"></div>
                            <?php
                            if ($id != 'add') {
                                echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                            }
                            echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';

                            echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                            echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                            echo ' <datalist id = "data_localizacao">';
                            include '../model/conexao.php';
                            $conexao = new conexao();
                            $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                            while ($row = $ret->fetch()) {
                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                            }
                            echo ' </datalist>';
                            echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                            echo ' <datalist id = "data_condominio">';
                            include '../model/conexao.php';
                            $conexao = new conexao();
                            $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                            while ($row = $ret->fetch()) {
                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                            }
                            echo ' </datalist>';


                            echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                            echo ' <datalist id = "data_cep">';
                            include '../model/conexao.php';
                            $conexao = new conexao();
                            $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                            while ($row = $ret->fetch()) {
                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                            }
                            echo ' </datalist>';
                            echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                            echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                            echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                            echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                            echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                            echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';

                            echo ' <div class = "form2-varchar">Tipo do imóvel';
                            echo ' <br><select name = "subtipo_nome" id = "subtipo_nome">';
                            echo ' <option>Térrea</option>';
                            echo ' <option>Semi-Térrea</option>';
                            echo ' <option>Sobrado Padrão</option>';
                            echo ' <option>Sobrado em Condomínio</option>';
                            echo ' <option>Assobradado</option>';
                            echo ' <option>Geminado</option>';
                            echo ' <option>Duplex</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Complemento<br><input type = "text" name = "complemento" id = "complemento" size = "25" value = "' . $complemento . '"></div>';
                            echo ' <div class = "form2-varchar">Área Terreno<br><input type = "text" name = "area_terreno" id = "area_terreno" size = "10" value = "' . $area_terreno . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Área Construída<br><input type = "text" name = "area_construida" id = "area_construida" size = "10" value = "' . $area_construida . '" style = "text-align: right;"></div>';

                            //xxx
                            echo ' <div class = "form2-varchar">Mt Frente<br><input type = "text" name = "m2_frente" id = "m2_frente" size = "10" value = "' . $m2_frente . '"></div>';
                            echo ' <div class = "form2-varchar">Mt Fundo<br><input type = "text" name = "fundos" id = "fundos" size = "10" value = "' . $fundos . '"></div>';
                            echo ' <div class = "form2-varchar">Mt Profundidade<br><input type = "text" name = "profundidade" id = "profundidade" size = "10" value = "' . $profundidade . '"></div>';


                            //
                            echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';
                            echo ' <div class = "form2-varchar">Finalidade de Uso';
                            echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 100px;">';
                            echo ' <option></option>';
                            echo ' <option>Residencial</option>';
                            echo ' <option>Comercial</option>';
                            echo ' <option>Industrial</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Obra';
                            echo ' <br><select name = "obra" id = "obra" style = "width: 100px;">';
                            echo ' <option></option>';
                            echo ' <option>Pronta</option>';
                            echo ' <option>Parada</option>';
                            echo ' <option>Em construção</option>';
                            echo ' </select>';
                            echo ' </div>';

                            echo ' <div class = "form2-varchar">Estado da construção';
                            echo ' <br><select name = "estado_construcao" id = "estado_construcao" style = "width: 100px;">';
                            echo ' <option></option>';
                            echo ' <option>Excelente</option>';
                            echo ' <option>Bom</option>';
                            echo ' <option>Regular</option>';
                            echo ' <option>Ruim</option>';
                            echo ' <option>Necessita Reforma</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Amb./Sala<br><input type = "text" name = "sala" id = "sala" size = "7" value = "' . $sala . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Dormitório<br><input type = "text" name = "dormitorio" id = "dormitorio" size = "7" value = "' . $dormitorio . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Suíte(s)<br><input type = "text" name = "suite" id = "suite" size = "7" value = "' . $suite . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Banheiro(s)<br><input type = "text" name = "banheiro" id = "banheiro" size = "7" value = "' . $banheiro . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Garagem<br><input type = "text" name = "garagem" id = "garagem" size = "7" value = "' . $garagem . '" style = "text-align: right;"></div>';


                            echo ' <div class = "form2-varchar">Situação do imóvel';
                            echo ' <br><select name = "situacao" id = "situacao">';
                            echo ' <option>Pendente</option>';
                            echo ' <option>Ativo</option>';
                            echo ' <option>Suspenso</option>';
                            echo ' <option>Vendido</option>';
                            echo ' <option>Vendido (Imobiliaria)</option>';
                            echo ' <option>Locado</option>';
                            echo ' <option>Administrado</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "30" value = "' . $chaves . '"></div>';
                            echo ' <div class = "form2-varchar">Ano Construção<br><input type = "text" name = "ano_construcao" id = "ano_construcao" size = "4" value = "' . $ano_construcao . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Ano Reforma<br><input type = "text" name = "ano_reforma" id = "ano_reforma" size = "4" value = "' . $ano_reforma . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Escritório<br><input type = "text" name = "escritorio" id = "escritorio" size = "10" value = "' . $escritorio . '"></div>';
                            echo ' <div class = "form2-varchar">Piscina<br><input type = "text" name = "piscina" id = "piscina" size = "10" value = "' . $piscina . '"></div>';
                            echo '</fieldset>';


                            echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                            echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                            echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                            echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_terreno\',\'valor_venda\');"></div>';
                            echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                            echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_terreno\',\'valor_metro\');"></div>';
                            echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                            echo ' <datalist id = "data_permuta">';
                            echo ' <option>CASA</option>';
                            echo ' <option>CARRO</option>';
                            echo ' <option>CASA+CARRO</option>';
                            echo ' </datalist>';
                            echo ' <div class = "form2-varchar">Publicar Internet?';
                            echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                            echo ' <option>Não</option>';
                            echo ' <option>Sim</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Destaque/Oferta?';
                            echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                            echo ' <option>Não</option>';
                            echo ' <option>Sim</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo '</fieldset>';
                            echo '<fieldset style = "position: absolute;top: 410px;left:380px;width: 740px;height: 180px;">';
                            echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "8" cols = "45">' . $observacao . '</textarea></div>';
                            echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "8" cols = "65">' . $descricao . '</textarea></div>';
                            echo '</fieldset>';


                            ///
                            echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                            echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                            echo ' <div class = "form2-varchar">Frente Para';
                            echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                            echo ' <option></option>';
                            echo ' <option>Norte</option>';
                            echo ' <option>Sul</option>';
                            echo ' <option>Leste</option>';
                            echo ' <option>Oeste</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                            echo ' <div class = "form2-varchar">Lançamento?';
                            echo ' <br><select name = "lancamento" id = "lancamento" style = "width: 120px;">';
                            echo ' <option>Não</option>';
                            echo ' <option>Sim</option>';
                            echo ' </select>';
                            echo ' </div>';
                            echo ' <div class = "form2-varchar">Data Lançamento<br><input type = "text" class = "datepicker" name = "data_lancamento" id = "data_lancamento" size = "12" value = "' . data_decode($data_lancamento) . '"></div>';
                            echo ' <div class = "form2-varchar">Construtora<br><input type = "text" name = "construtora" id = "construtora" size = "30" value = "' . $construtora . '"></div>';
                            echo ' <div class = "form2-varchar">Nunca Usado?';
                            echo ' <br><select name = "nunca_usado" id = "nunca_usado" style = "width: 120px;">';
                            echo ' <option>Não</option>';
                            echo ' <option>Sim</option>';
                            echo ' </select>';
                            echo ' </div>';


                            echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                            if (strpos($video_youtube, 'youtube.com')) {
                                echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                            } else {
                                echo '&nbsp; Utilizar http://www.youtube.com...';
                            }
                            echo ' </div>';

                            echo '</fieldset>';



                            ///
                        } elseif ($tipo_nome == 'Apartamentos') {
                            ?>
                            <div class="form-detalhe" id="form-detalhe" style="height: 1060px;">
                                <div class="imovel2-foto">
                                    <?php
                                    $foto1 = $foto;
                                    if (empty($foto1)) {
                                        $foto1 = 'sem_foto.jpg';
                                    } else {
                                        if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                            $foto1 = 'sem_foto.jpg';
                                        }
                                    }
                                    echo '<input type = "hidden" name = "foto" id = "foto" value = "' . $foto1 . '">';
                                    echo '<input type = "hidden" name = "tipo_nome" id = "tipo_nome" value = "' . $tipo_nome . '">';
                                    if ($id != 'add') {
                                        echo ' <div id = "foto2-detalhe" onclick = "mostra_fotos();">';
                                    } else {
                                        echo ' <div id = "foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                    }
                                    echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width = "382" height = "366" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                    ?>
                                </div>
                                <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                                <div style="width: 100%;height: 50px;float: left;"></div>
                                <?php
                                if ($id != 'add') {
                                    echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                                }
                                echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';
                                echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                                echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                                echo ' <datalist id = "data_localizacao">';
                                include '../model/conexao.php';
                                $conexao = new conexao();
                                $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                                while ($row = $ret->fetch()) {
                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                }
                                echo ' </datalist>';
                                echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                                echo ' <datalist id = "data_condominio">';
                                include '../model/conexao.php';
                                $conexao = new conexao();
                                $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                                while ($row = $ret->fetch()) {
                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                }
                                echo ' </datalist>';


                                echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                                echo ' <datalist id = "data_cep">';
                                include '../model/conexao.php';
                                $conexao = new conexao();
                                $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                                while ($row = $ret->fetch()) {
                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                }
                                echo ' </datalist>';
                                echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                                echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                                echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                                echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                                echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                                echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';



                                echo ' <div class = "form2-varchar">Edifício<br><input type = "text" list = "data_edificio" name = "edificio" id = "edificio" size = "93" value = "' . $edificio . '"></div>';
                                echo ' <datalist id = "data_edificio">';
                                include '../model/conexao.php';
                                $conexao = new conexao();
                                $ret = $conexao->db->query("SELECT DISTINCT(edificio) FROM imovel");
                                while ($row = $ret->fetch()) {
                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                }
                                echo ' </datalist>';
                                echo ' <div class = "form2-varchar">N.Apto.<br><input type = "text" name = "numero_apartamento" id = "numero_apartamento" size = "15" value = "' . $numero_apartamento . '"></div>';
                                echo ' <div class = "form2-varchar">Andar<br><input type = "text" name = "andar" id = "andar" size = "16" value = "' . $andar . '"></div>';


                                echo ' <div class = "form2-varchar">Área Útil<br><input type = "text" name = "area_util" id = "area_util" size = "10" value = "' . $area_util . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Área Total<br><input type = "text" name = "area_total" id = "area_total" size = "10" value = "' . $area_total . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Aptos por Andar<br><input type = "text" name = "aps_por_andar" id = "aps_por_andar" size = "10" value = "' . $aps_por_andar . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Vagas p/Visites.<br><input type = "text" name = "vagas_visitante" id = "vagas_visitante" size = "10" value = "' . $vagas_visitante . '"></div>';
                                echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "40" value = "' . $chaves . '"></div>';



                                echo ' <div class = "form2-varchar">Tipo do imóvel';
                                echo ' <br><select name = "subtipo_nome" id = "subtipo_nome" style = "width: 140px;">';
                                echo ' <option>Cobertura</option>';
                                echo ' <option>Padrão</option>';
                                echo ' <option>Cobertura Triplex</option>';
                                echo ' <option>Kitchenette/Studio</option>';
                                echo ' <option>Kitchenette/Residencia</option>';
                                echo ' <option>Loft</option>';
                                echo ' <option>Flat</option>';
                                echo ' <option>Cobertura/Alto padrão</option>';
                                echo ' <option>Altíssimo padrão</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Obra';
                                echo ' <br><select name = "obra" id = "obra" style = "width: 140px;">';
                                echo ' <option></option>';
                                echo ' <option>Pronta</option>';
                                echo ' <option>Parada</option>';
                                echo ' <option>Em construção</option>';
                                echo ' </select>';
                                echo ' </div>';


                                echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';
                                echo ' <div class = "form2-varchar">Finalidade de Uso';
                                echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 170px;">';
                                echo ' <option>Residencial</option>';
                                echo ' <option>Comercial</option>';
                                echo ' <option>Industrial</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Estado da construção';
                                echo ' <br><select name = "estado_construcao" id = "estado_construcao" style = "width: 170px;">';
                                echo ' <option></option>';
                                echo ' <option>Excelente</option>';
                                echo ' <option>Bom</option>';
                                echo ' <option>Regular</option>';
                                echo ' <option>Ruim</option>';
                                echo ' <option>Necessita Reforma</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Amb./Sala<br><input type = "text" name = "sala" id = "sala" size = "10" value = "' . $sala . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Dormitório<br><input type = "text" name = "dormitorio" id = "dormitorio" size = "10" value = "' . $dormitorio . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Suíte(s)<br><input type = "text" name = "suite" id = "suite" size = "10" value = "' . $suite . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Banheiro(s)<br><input type = "text" name = "banheiro" id = "banheiro" size = "10" value = "' . $banheiro . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Garagem<br><input type = "text" name = "garagem" id = "garagem" size = "10" value = "' . $garagem . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Situação do imóvel';
                                echo ' <br><select name = "situacao" id = "situacao" style = "width: 120px;">';
                                echo ' <option>Pendente</option>';
                                echo ' <option>Ativo</option>';
                                echo ' <option>Suspenso</option>';
                                echo ' <option>Vendido</option>';
                                echo ' <option>Vendido (Imobiliaria)</option>';
                                echo ' <option>Locado</option>';
                                echo ' <option>Administrado</option>';
                                echo ' </select>';
                                echo ' </div>';

                                echo ' <div class = "form2-varchar">Escritório<br><input type = "text" name = "escritorio" id = "escritorio" size = "10" value = "' . $escritorio . '"></div>';
                                echo ' <div class = "form2-varchar">Piscina<br><input type = "text" name = "piscina" id = "piscina" size = "10" value = "' . $piscina . '"></div>';

                                echo '</fieldset>';
                                echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                                echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                                echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                                echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_total\',\'valor_venda\');"></div>';
                                echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                                echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_total\',\'valor_metro\');"></div>';
                                echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                                echo ' <datalist id = "data_permuta">';
                                echo ' <option>CASA</option>';
                                echo ' <option>CARRO</option>';
                                echo ' <option>CASA+CARRO</option>';
                                echo ' </datalist>';
                                echo ' <div class = "form2-varchar">Publicar Internet?';
                                echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                                echo ' <option>Não</option>';
                                echo ' <option>Sim</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Destaque/Oferta?';
                                echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                                echo ' <option>Não</option>';
                                echo ' <option>Sim</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo '</fieldset>';
                                echo '<fieldset style = "position: absolute;top: 460px;left:380px;width: 740px;height: 130px;">';
                                echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "6" cols = "45">' . $observacao . '</textarea></div>';
                                echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "6" cols = "45">' . $descricao . '</textarea></div>';
                                echo '</fieldset>';

                                ///
                                echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                                echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                                echo ' <div class = "form2-varchar">Frente Para';
                                echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                                echo ' <option></option>';
                                echo ' <option>Norte</option>';
                                echo ' <option>Sul</option>';
                                echo ' <option>Leste</option>';
                                echo ' <option>Oeste</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                                echo ' <div class = "form2-varchar">Lançamento?';
                                echo ' <br><select name = "lancamento" id = "lancamento" style = "width: 120px;">';
                                echo ' <option>Não</option>';
                                echo ' <option>Sim</option>';
                                echo ' </select>';
                                echo ' </div>';
                                echo ' <div class = "form2-varchar">Data Lançamento<br><input type = "text" class = "datepicker" name = "data_lancamento" id = "data_lancamento" size = "12" value = "' . data_decode($data_lancamento) . '"></div>';
                                echo ' <div class = "form2-varchar">Construtora<br><input type = "text" name = "construtora" id = "construtora" size = "30" value = "' . $construtora . '"></div>';
                                echo ' <div class = "form2-varchar">Nunca Usado?';
                                echo ' <br><select name = "nunca_usado" id = "nunca_usado" style = "width: 120px;">';
                                echo ' <option>Não</option>';
                                echo ' <option>Sim</option>';
                                echo ' </select>';
                                echo ' </div>';


                                echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                                if (strpos($video_youtube, 'youtube.com')) {
                                    echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                } else {
                                    echo '&nbsp; Utilizar http://www.youtube.com...';
                                }
                                echo ' </div>';

                                echo '</fieldset>';



                                ///
                            } elseif ($tipo_nome == 'Terrenos') {
                                ?>
                                <div class="form-detalhe" id="form-detalhe" style="height: 1060px;">
                                    <div class="imovel2-foto">
                                        <?php
                                        $foto1 = $foto;
                                        if (empty($foto1)) {
                                            $foto1 = 'sem_foto.jpg';
                                        } else {
                                            if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                                $foto1 = 'sem_foto.jpg';
                                            }
                                        }
                                        echo '<input type = "hidden" name = "foto" id = "foto" value = "' . $foto1 . '">';
                                        echo '<input type = "hidden" name = "tipo_nome" id = "tipo_nome" value = "' . $tipo_nome . '">';
                                        if ($id != 'add') {
                                            echo ' <div id = "foto2-detalhe" onclick = "mostra_fotos();">';
                                        } else {
                                            echo ' <div id = "foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                        }
                                        echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width = "382" height = "366" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                        ?>
                                    </div>
                                    <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                                    <div style="width: 100%;height: 50px;float: left;"></div>
                                    <?php
                                    if ($id != 'add') {
                                        echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                                    }
                                    echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';
                                    echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                                    echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                                    echo ' <datalist id = "data_localizacao">';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo ' </datalist>';
                                    echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                                    echo ' <datalist id = "data_condominio">';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo ' </datalist>';


                                    echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                                    echo ' <datalist id = "data_cep">';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo ' </datalist>';
                                    echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                                    echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                                    echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                                    echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                                    echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                                    echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';

                                    echo ' <div class = "form2-varchar">Quadra<br><input type = "text" name = "quadra" id = "quadra" size = "10" value = "' . $quadra . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Lote<br><input type = "text" name = "lote" id = "lote" size = "10" value = "' . $lote . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Metragem<br><input type = "text" name = "metragem" id = "metragem" size = "10" value = "' . $metragem . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Área Terreno<br><input type = "text" name = "area_terreno" id = "area_terreno" size = "10" value = "' . $area_terreno . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Área Construída<br><input type = "text" name = "area_construida" id = "area_construida" size = "10" value = "' . $area_construida . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Finalidade de Uso';
                                    echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 100px;">';
                                    echo ' <option></option>';
                                    echo ' <option>Residencial</option>';
                                    echo ' <option>Comercial</option>';
                                    echo ' <option>Industrial</option>';
                                    echo ' </select>';
                                    echo ' </div>';

                                    //xxx
                                    echo ' <div class = "form2-varchar">Mt Frente<br><input type = "text" name = "m2_frente" id = "m2_frente" size = "10" value = "' . $m2_frente . '"></div>';
                                    echo ' <div class = "form2-varchar">Mt Fundo<br><input type = "text" name = "fundos" id = "fundos" size = "10" value = "' . $fundos . '"></div>';
                                    echo ' <div class = "form2-varchar">Mt Profundidade<br><input type = "text" name = "profundidade" id = "profundidade" size = "10" value = "' . $profundidade . '"></div>';


                                    echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';

                                    echo ' <div class = "form2-varchar">Topografia';
                                    echo ' <br><select name = "topografia" id = "topografia" style = "width: 150px;">';
                                    echo ' <option></option>';
                                    echo ' <option>Aclive</option>';
                                    echo ' <option>Declive</option>';
                                    echo ' <option>Leve Aclive</option>';
                                    echo ' <option>Leve Declive</option>';
                                    echo ' <option>Plano</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Zoneamento';
                                    echo ' <br><select name = "zoneamento" id = "zoneamento" style = "width: 120px;">';
                                    echo ' <option></option>';
                                    echo ' <option>Comercial</option>';
                                    echo ' <option>Industrial</option>';
                                    echo ' <option>Residencial</option>';
                                    echo ' <option>ZUP-01</option>';
                                    echo ' <option>ZUP-03</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Tipo do imóvel';
                                    echo ' <br><select name = "subtipo_nome" id = "subtipo_nome" style = "width: 100px;">';
                                    echo ' <option>Padrão</option>';
                                    echo ' <option>Loteamento/Condomínio Fechado</option>';
                                    echo ' <option>Loteamento</option>';
                                    echo ' <option>Industrial</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Situação do imóvel';
                                    echo ' <br><select name = "situacao" id = "situacao" style = "width: 120px;">';
                                    echo ' <option>Pendente</option>';
                                    echo ' <option>Ativo</option>';
                                    echo ' <option>Suspenso</option>';
                                    echo ' <option>Vendido</option>';
                                    echo ' <option>Vendido (Imobiliaria)</option>';
                                    echo ' <option>Locado</option>';
                                    echo ' <option>Administrado</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "90" value = "' . $chaves . '"></div>';

                                    echo '</fieldset>';
                                    echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                                    echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                                    echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                                    echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_terreno\',\'valor_venda\');"></div>';
                                    echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                                    echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_terreno\',\'valor_metro\');"></div>';
                                    echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                                    echo ' <datalist id = "data_permuta">';
                                    echo ' <option>CASA</option>';
                                    echo ' <option>CARRO</option>';
                                    echo ' <option>CASA+CARRO</option>';
                                    echo ' </datalist>';
                                    echo ' <div class = "form2-varchar">Publicar Internet?';
                                    echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                                    echo ' <option>Não</option>';
                                    echo ' <option>Sim</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Destaque/Oferta?';
                                    echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                                    echo ' <option>Não</option>';
                                    echo ' <option>Sim</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo '</fieldset>';
                                    echo '<fieldset style = "position: absolute;top: 410px;left:380px;width: 740px;height: 180px;">';
                                    echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "8" cols = "45">' . $observacao . '</textarea></div>';
                                    echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "8" cols = "45">' . $descricao . '</textarea></div>';
                                    echo '</fieldset>';

                                    ///
                                    echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                                    echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                                    echo ' <div class = "form2-varchar">Frente Para';
                                    echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                                    echo ' <option></option>';
                                    echo ' <option>Norte</option>';
                                    echo ' <option>Sul</option>';
                                    echo ' <option>Leste</option>';
                                    echo ' <option>Oeste</option>';
                                    echo ' </select>';
                                    echo ' </div>';
                                    echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                                    echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                                    if (strpos($video_youtube, 'youtube.com')) {
                                        echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                    } else {
                                        echo '&nbsp; Utilizar http://www.youtube.com...';
                                    }
                                    echo ' </div>';

                                    echo '</fieldset>';



                                    ///
                                } elseif ($tipo_nome == 'Galpões') {
                                    ?>
                                    <div class="form-detalhe" id="form-detalhe" style="height: 1060px;">
                                        <div class="imovel2-foto">
                                            <?php
                                            $foto1 = $foto;
                                            if (empty($foto1)) {
                                                $foto1 = 'sem_foto.jpg';
                                            } else {
                                                if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                                    $foto1 = 'sem_foto.jpg';
                                                }
                                            }
                                            echo '<input type = "hidden" name = "foto" id = "foto" value = "' . $foto1 . '">';
                                            echo '<input type = "hidden" name = "tipo_nome" id = "tipo_nome" value = "' . $tipo_nome . '">';
                                            if ($id != 'add') {
                                                echo ' <div id = "foto2-detalhe" onclick = "mostra_fotos();">';
                                            } else {
                                                echo ' <div id = "foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                            }
                                            echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width = "382" height = "366" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                            ?>
                                        </div>
                                        <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                                        <div style="width: 100%;height: 50px;float: left;"></div>
                                        <?php
                                        if ($id != 'add') {
                                            echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                                        }
                                        echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';
                                        echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                                        echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                                        echo ' <datalist id = "data_localizacao">';
                                        include '../model/conexao.php';
                                        $conexao = new conexao();
                                        $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                                        while ($row = $ret->fetch()) {
                                            echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                        }
                                        echo ' </datalist>';
                                        echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                                        echo ' <datalist id = "data_condominio">';
                                        include '../model/conexao.php';
                                        $conexao = new conexao();
                                        $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                                        while ($row = $ret->fetch()) {
                                            echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                        }
                                        echo ' </datalist>';


                                        echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                                        echo ' <datalist id = "data_cep">';
                                        include '../model/conexao.php';
                                        $conexao = new conexao();
                                        $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                                        while ($row = $ret->fetch()) {
                                            echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                        }
                                        echo ' </datalist>';
                                        echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                                        echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                                        echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                                        echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                                        echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                                        echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';

                                        echo ' <div class = "form2-varchar">N.Galpão<br><input type = "text" name = "numero_galpao" id = "numero_galpao" size = "10" value = "' . $numero_galpao . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Terreno<br><input type = "text" name = "area_terreno" id = "area_terreno" size = "10" value = "' . $area_terreno . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Construída<br><input type = "text" name = "area_construida" id = "area_construida" size = "10" value = "' . $area_construida . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Escritório<br><input type = "text" name = "area_escritorio" id = "area_escritorio" size = "10" value = "' . $area_escritorio . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Galpão<br><input type = "text" name = "area_galpao" id = "area_galpao" size = "10" value = "' . $area_galpao . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Pé Direito<br><input type = "text" name = "pe_direito" id = "pe_direito" size = "10" value = "' . $pe_direito . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Fabril<br><input type = "text" name = "area_fabril" id = "area_fabril" size = "10" value = "' . $area_fabril . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Vão Livre<br><input type = "text" name = "vao_livre" id = "vao_livre" size = "10" value = "' . $vao_livre . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Área Pátio<br><input type = "text" name = "area_patio" id = "area_patio" size = "10" value = "' . $area_patio . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Metragem<br><input type = "text" name = "metragem" id = "metragem" size = "10" value = "' . $metragem . '" style = "text-align: right;"></div>';


                                        //xxx
                                        echo ' <div class = "form2-varchar">Mt Frente<br><input type = "text" name = "m2_frente" id = "m2_frente" size = "10" value = "' . $m2_frente . '"></div>';
                                        echo ' <div class = "form2-varchar">Mt Fundo<br><input type = "text" name = "fundos" id = "fundos" size = "10" value = "' . $fundos . '"></div>';
                                        echo ' <div class = "form2-varchar">Mt Profundidade<br><input type = "text" name = "profundidade" id = "profundidade" size = "10" value = "' . $profundidade . '"></div>';


                                        echo ' <div class = "form2-varchar">Finalidade de Uso';
                                        echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 130px;">';
                                        echo ' <option></option>';
                                        echo ' <option>Comercial</option>';
                                        echo ' <option>Industrial</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Obra';
                                        echo ' <br><select name = "obra" id = "obra" style = "width: 100px;">';
                                        echo ' <option></option>';
                                        echo ' <option>Pronta</option>';
                                        echo ' <option>Parada</option>';
                                        echo ' <option>Em construção</option>';
                                        echo ' </select>';
                                        echo ' </div>';

                                        echo ' <div class = "form2-varchar">Topografia';
                                        echo ' <br><select name = "topografia" id = "topografia" style = "width: 150px;">';
                                        echo ' <option></option>';
                                        echo ' <option>Aclive</option>';
                                        echo ' <option>Declive</option>';
                                        echo ' <option>Leve Aclive</option>';
                                        echo ' <option>Leve Declive</option>';
                                        echo ' <option>Plano</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Zoneamento';
                                        echo ' <br><select name = "zoneamento" id = "zoneamento" style = "width: 120px;">';
                                        echo ' <option></option>';
                                        echo ' <option>Comercial</option>';
                                        echo ' <option>Industrial</option>';
                                        echo ' <option>Residencial</option>';
                                        echo ' <option>ZUP-01</option>';
                                        echo ' <option>ZUP-03</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Situação do imóvel';
                                        echo ' <br><select name = "situacao" id = "situacao" style = "width: 120px;">';
                                        echo ' <option>Pendente</option>';
                                        echo ' <option>Ativo</option>';
                                        echo ' <option>Suspenso</option>';
                                        echo ' <option>Vendido</option>';
                                        echo ' <option>Vendido (Imobiliaria)</option>';
                                        echo ' <option>Locado</option>';
                                        echo ' <option>Administrado</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Tipo do imóvel';
                                        echo ' <br><select name = "subtipo_nome" id = "subtipo_nome" style = "width: 100px;">';
                                        echo ' <option>Industrial</option>';
                                        echo ' <option>Comercial</option>';
                                        echo ' <option>Empresarial</option>';
                                        echo ' </select>';
                                        echo ' </div>';

                                        echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';

                                        echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "80" value = "' . $chaves . '"></div>';


                                        echo '</fieldset>';
                                        echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                                        echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                                        echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                                        echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_terreno\',\'valor_venda\');"></div>';
                                        echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                                        echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_terreno\',\'valor_metro\');"></div>';
                                        echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                                        echo ' <datalist id = "data_permuta">';
                                        echo ' <option>CASA</option>';
                                        echo ' <option>CARRO</option>';
                                        echo ' <option>CASA+CARRO</option>';
                                        echo ' </datalist>';
                                        echo ' <div class = "form2-varchar">Publicar Internet?';
                                        echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                                        echo ' <option>Não</option>';
                                        echo ' <option>Sim</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Destaque/Oferta?';
                                        echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                                        echo ' <option>Não</option>';
                                        echo ' <option>Sim</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo '</fieldset>';
                                        echo '<fieldset style = "position: absolute;top: 410px;left:380px;width: 740px;height: 180px;">';
                                        echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "8" cols = "45">' . $observacao . '</textarea></div>';
                                        echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "8" cols = "75">' . $descricao . '</textarea></div>';
                                        echo '</fieldset>';

                                        ///
                                        echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                                        echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                                        echo ' <div class = "form2-varchar">Força Trifásico<br><input type = "text" name = "forca_tri" id = "forca_tri" size = "10" value = "' . $forca_tri . '">HP</div>';
                                        echo ' <div class = "form2-varchar">Cabine Primária<br><input type = "text" name = "cabine_primaria" id = "cabine_primaria" size = "10" value = "' . $cabine_primaria . '" style = "text-align: right;">Kva</div>';
                                        echo ' <div class = "form2-varchar">Frente Para';
                                        echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                                        echo ' <option></option>';
                                        echo ' <option>Norte</option>';
                                        echo ' <option>Sul</option>';
                                        echo ' <option>Leste</option>';
                                        echo ' <option>Oeste</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                                        echo ' <div class = "form2-varchar">Lançamento?';
                                        echo ' <br><select name = "lancamento" id = "lancamento" style = "width: 120px;">';
                                        echo ' <option>Não</option>';
                                        echo ' <option>Sim</option>';
                                        echo ' </select>';
                                        echo ' </div>';
                                        echo ' <div class = "form2-varchar">Data Lançamento<br><input type = "text" class = "datepicker" name = "data_lancamento" id = "data_lancamento" size = "12" value = "' . data_decode($data_lancamento) . '"></div>';
                                        echo ' <div class = "form2-varchar">Construtora<br><input type = "text" name = "construtora" id = "construtora" size = "30" value = "' . $construtora . '"></div>';
                                        echo ' <div class = "form2-varchar">Nunca Usado?';
                                        echo ' <br><select name = "nunca_usado" id = "nunca_usado" style = "width: 120px;">';
                                        echo ' <option>Não</option>';
                                        echo ' <option>Sim</option>';
                                        echo ' </select>';
                                        echo ' </div>';



                                        echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                                        if (strpos($video_youtube, 'youtube.com')) {
                                            echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                        } else {
                                            echo '&nbsp; Utilizar http://www.youtube.com...';
                                        }
                                        echo ' </div>';

                                        echo '</fieldset>';



                                        ///
                                    } elseif ($tipo_nome == 'Comercial') {
                                        ?>
                                        <div class="form-detalhe" id="form-detalhe" style="height: 1040px;">
                                            <div class="imovel2-foto">
                                                <?php
                                                $foto1 = $foto;
                                                if (empty($foto1)) {
                                                    $foto1 = 'sem_foto.jpg';
                                                } else {
                                                    if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                                        $foto1 = 'sem_foto.jpg';
                                                    }
                                                }
                                                echo '<input type = "hidden" name = "foto" id = "foto" value = "' . $foto1 . '">';
                                                echo '<input type = "hidden" name = "tipo_nome" id = "tipo_nome" value = "' . $tipo_nome . '">';
                                                if ($id != 'add') {
                                                    echo ' <div id = "foto2-detalhe" onclick = "mostra_fotos();">';
                                                } else {
                                                    echo ' <div id = "foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                                }
                                                echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width = "382" height = "366" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                                ?>
                                            </div>
                                            <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                                            <div style="width: 100%;height: 50px;float: left;"></div>
                                            <?php
                                            if ($id != 'add') {
                                                echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                                            }
                                            echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';
                                            echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                                            echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                                            echo ' <datalist id = "data_localizacao">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                                            echo ' <datalist id = "data_condominio">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';


                                            echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                                            echo ' <datalist id = "data_cep">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                                            echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                                            echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                                            echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                                            echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                                            echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';

                                            echo ' <div class = "form2-varchar">Torre<br><input type = "text" list = "data_torre" name = "torre" id = "torre" size = "60" value = "' . $torre . '"></div>';
                                            echo ' <datalist id = "data_torre">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT DISTINCT(torre) FROM imovel");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' <div class = "form2-varchar">Cj./Loja/Sl<br><input type = "text" name = "conjunto" id = "conjunto" size = "10" value = "' . $conjunto . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Andar<br><input type = "text" name = "andar" id = "andar" size = "10" value = "' . $andar . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Área Terreno<br><input type = "text" name = "area_terreno" id = "area_terreno" size = "10" value = "' . $area_terreno . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Área Útil<br><input type = "text" name = "area_util" id = "area_util" size = "10" value = "' . $area_util . '" style = "text-align: right;"></div>';

                                            //xxx
                                            echo ' <div class = "form2-varchar">Mt Frente<br><input type = "text" name = "m2_frente" id = "m2_frente" size = "10" value = "' . $m2_frente . '"></div>';
                                            echo ' <div class = "form2-varchar">Mt Fundo<br><input type = "text" name = "fundos" id = "fundos" size = "10" value = "' . $fundos . '"></div>';
                                            echo ' <div class = "form2-varchar">Mt Profundidade<br><input type = "text" name = "profundidade" id = "profundidade" size = "10" value = "' . $profundidade . '"></div>';



                                            echo ' <div class = "form2-varchar">Garagem<br><input type = "text" name = "garagem" id = "garagem" size = "10" value = "' . $garagem . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Banheiro<br><input type = "text" name = "banheiro" id = "banheiro" size = "10" value = "' . $banheiro . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Pé Direito<br><input type = "text" name = "pe_direito" id = "pe_direito" size = "10" value = "' . $pe_direito . '" style = "text-align: right;"></div>';



                                            echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';
                                            echo ' <div class = "form2-varchar">Finalidade de Uso';
                                            echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 130px;">';
                                            echo ' <option></option>';
                                            echo ' <option>Comercial</option>';
                                            echo ' <option>Industrial</option>';
                                            echo ' </select>';
                                            echo ' </div>';

                                            echo ' <div class = "form2-varchar">Obra';
                                            echo ' <br><select name = "obra" id = "obra" style = "width: 100px;">';
                                            echo ' <option></option>';
                                            echo ' <option>Pronta</option>';
                                            echo ' <option>Parada</option>';
                                            echo ' <option>Em construção</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Topografia';
                                            echo ' <br><select name = "topografia" id = "topografia" style = "width: 150px;">';
                                            echo ' <option></option>';
                                            echo ' <option>Aclive</option>';
                                            echo ' <option>Declive</option>';
                                            echo ' <option>Leve Aclive</option>';
                                            echo ' <option>Leve Declive</option>';
                                            echo ' <option>Plano</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Zoneamento';
                                            echo ' <br><select name = "zoneamento" id = "zoneamento" style = "width: 120px;">';
                                            echo ' <option></option>';
                                            echo ' <option>Comercial</option>';
                                            echo ' <option>Industrial</option>';
                                            echo ' <option>Residencial</option>';
                                            echo ' <option>ZUP-01</option>';
                                            echo ' <option>ZUP-03</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Situação do imóvel';
                                            echo ' <br><select name = "situacao" id = "situacao" style = "width: 120px;">';
                                            echo ' <option>Pendente</option>';
                                            echo ' <option>Ativo</option>';
                                            echo ' <option>Suspenso</option>';
                                            echo ' <option>Vendido</option>';
                                            echo ' <option>Vendido (Imobiliaria)</option>';
                                            echo ' <option>Locado</option>';
                                            echo ' <option>Administrado</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Tipo do imóvel';
                                            echo ' <br><select name = "subtipo_nome" id = "subtipo_nome" style = "width: 180px;">';
                                            echo ' <option>Espaço Box/Garagem</option>';
                                            echo ' <option>Prédio</option>';
                                            echo ' <option>Conjunto/Sala</option>';
                                            echo ' <option>Casa Comercial</option>';
                                            echo ' <option>Loja de Shopping / Centro Comercial</option>';
                                            echo ' <option>Loja/Salão</option>';
                                            echo ' <option>Galpão/Depósito/Barração</option>';
                                            echo ' <option>Hotel</option>';
                                            echo ' <option>Motel</option>';
                                            echo ' <option>Pousada/Chalé</option>';
                                            echo ' <option>Indústria</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "40" value = "' . $chaves . '"></div>';

                                            echo '</fieldset>';
                                            echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                                            echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                                            echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                                            echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_terreno\',\'valor_venda\');"></div>';
                                            echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                                            echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_terreno\',\'valor_metro\');"></div>';
                                            echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                                            echo ' <datalist id = "data_permuta">';
                                            echo ' <option>CASA</option>';
                                            echo ' <option>CARRO</option>';
                                            echo ' <option>CASA+CARRO</option>';
                                            echo ' </datalist>';
                                            echo ' <div class = "form2-varchar">Publicar Internet?';
                                            echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                                            echo ' <option>Não</option>';
                                            echo ' <option>Sim</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Destaque/Oferta?';
                                            echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                                            echo ' <option>Não</option>';
                                            echo ' <option>Sim</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo '</fieldset>';
                                            echo '<fieldset style = "position: absolute;top: 410px;left:380px;width: 740px;height: 180px;">';
                                            echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "8" cols = "45">' . $observacao . '</textarea></div>';
                                            echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "8" cols = "45">' . $descricao . '</textarea></div>';
                                            echo '</fieldset>';

                                            ///
                                            echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                                            echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                                            echo ' <div class = "form2-varchar">Frente Para';
                                            echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                                            echo ' <option></option>';
                                            echo ' <option>Norte</option>';
                                            echo ' <option>Sul</option>';
                                            echo ' <option>Leste</option>';
                                            echo ' <option>Oeste</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                                            echo ' <div class = "form2-varchar">Lançamento?';
                                            echo ' <br><select name = "lancamento" id = "lancamento" style = "width: 120px;">';
                                            echo ' <option>Não</option>';
                                            echo ' <option>Sim</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data Lançamento<br><input type = "text" class = "datepicker" name = "data_lancamento" id = "data_lancamento" size = "12" value = "' . data_decode($data_lancamento) . '"></div>';
                                            echo ' <div class = "form2-varchar">Construtora<br><input type = "text" name = "construtora" id = "construtora" size = "30" value = "' . $construtora . '"></div>';
                                            echo ' <div class = "form2-varchar">Nunca Usado?';
                                            echo ' <br><select name = "nunca_usado" id = "nunca_usado" style = "width: 120px;">';
                                            echo ' <option>Não</option>';
                                            echo ' <option>Sim</option>';
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                                            if (strpos($video_youtube, 'youtube.com')) {
                                                echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                            } else {
                                                echo '&nbsp; Utilizar http://www.youtube.com...';
                                            }
                                            echo ' </div>';

                                            echo '</fieldset>';



                                            ///
                                        } elseif ($tipo_nome == 'Rural') {
                                            ?>
                                            <div class="form-detalhe" id="form-detalhe" style="height: 1040px;">
                                                <div class="imovel2-foto">
                                                    <?php
                                                    $foto1 = $foto;
                                                    if (empty($foto1)) {
                                                        $foto1 = 'sem_foto.jpg';
                                                    } else {
                                                        if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
                                                            $foto1 = 'sem_foto.jpg';
                                                        }
                                                    }
                                                    echo '<input type = "hidden" name = "foto" id = "foto" value = "' . $foto1 . '">';
                                                    echo '<input type = "hidden" name = "tipo_nome" id = "tipo_nome" value = "' . $tipo_nome . '">';
                                                    if ($id != 'add') {
                                                        echo ' <div id = "foto2-detalhe" onclick = "mostra_fotos();">';
                                                    } else {
                                                        echo ' <div id = "foto2-detalhe" onclick = "alert(\'Para enviar fotos, primeiro faça o cadastro depois edite o mesmo novamente  e então poderá enviar fotos para ele.\');">';
                                                    }
                                                    echo ' <img src = "../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" width = "382" height = "366" title = "Clique aqui para Abrir a Galeria de Fotos">';
                                                    ?>
                                                </div>
                                                <div class="form2-titulo">Ficha de Cadastro de <?php echo $tipo_nome; ?></div>
                                                <div style="width: 100%;height: 50px;float: left;"></div>
                                                <?php
                                                if ($id != 'add') {
                                                    echo '<div class = "cadastro-referencia">Referência | <input type = "text" value = "' . str_pad($id, 8, '0', 0) . '" size = "8" readonly style = "text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                                                }
                                                echo '<input type = "hidden" name = "id" id = "id" value = "' . $id . '">';
                                                echo '<fieldset style = "position: absolute;top: 60px;left:380px;width: 740px;height: auto;">';
                                                echo ' <div class = "form2-varchar">Localização<br><input type = "text" list = "data_localizacao" name = "localizacao" id = "localizacao" size = "65" value = "' . $localizacao . '"></div>';
                                                echo ' <datalist id = "data_localizacao">';
                                                include '../model/conexao.php';
                                                $conexao = new conexao();
                                                $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel");
                                                while ($row = $ret->fetch()) {
                                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                                }
                                                echo ' </datalist>';
                                                echo ' <div class = "form2-varchar">Condomínio<br><input type = "text" list = "data_condominio" name = "condominio" id = "condominio" size = "65" value = "' . $condominio . '"></div>';
                                                echo ' <datalist id = "data_condominio">';
                                                include '../model/conexao.php';
                                                $conexao = new conexao();
                                                $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel");
                                                while ($row = $ret->fetch()) {
                                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                                }
                                                echo ' </datalist>';


                                                echo '<div class="linha-endereco"> <div class = "form2-varchar">CEP<br><input list = "data_cep" type = "text" name = "cep" id="cep" size="10" maxlength = "8" value = "' . $cep . '" onblur = "pesquisa_cep();" onkeypress = "return SomenteNumero(event);"></div>';
                                                echo ' <datalist id = "data_cep">';
                                                include '../model/conexao.php';
                                                $conexao = new conexao();
                                                $ret = $conexao->db->query("SELECT DISTINCT(cep) FROM cep");
                                                while ($row = $ret->fetch()) {
                                                    echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                                }
                                                echo ' </datalist>';
                                                echo ' <div class = "form2-varchar">Endereço<br><input type = "text" name = "tipo_logradouro" id = "tipo_logradouro" size = "8" value = "' . $tipo_logradouro . '"></div>';
                                                echo ' <div class = "form2-varchar">&nbsp;
                        <br><input type = "text" name = "logradouro" id = "logradouro" size = "75" value = "' . $logradouro . '"></div>';
                                                echo ' <div class = "form2-varchar">Número<br><input type = "text" name = "numero" id = "numero" size = "10" value = "' . $numero . '"></div></div>';

                                                echo ' <div class = "form2-varchar">Bairro<br><input type = "text" name = "bairro" id = "bairro" size = "60" value = "' . $bairro . '"></div>';
                                                echo ' <div class = "form2-varchar">Município<br><input type = "text" name = "cidade" id = "cidade" size = "60" value = "' . $cidade . '"></div>';
                                                echo ' <div class = "form2-varchar">UF<br><input type = "text" name = "uf" id = "uf" size = "4" value = "' . $uf . '"></div>';


                                                echo ' <div class = "form2-varchar">Garagem<br><input type = "text" name = "garagem" id = "garagem" size = "10" value = "' . $garagem . '"></div>';
                                                echo ' <div class = "form2-varchar">Banheiro<br><input type = "text" name = "banheiro" id = "lote" size = "10" value = "' . $banheiro . '"></div>';
                                                echo ' <div class = "form2-varchar">Pé Direito<br><input type = "text" name = "pe_direito" id = "pe_direito" size = "10" value = "' . $pe_direito . '"></div>';
                                                echo ' <div class = "form2-varchar">Área Terreno<br><input type = "text" name = "area_terreno" id = "area_terreno" size = "10" value = "' . $area_terreno . '" style = "text-align: right;"></div>';
                                                echo ' <div class = "form2-varchar">Área Útil<br><input type = "text" name = "area_util" id = "area_util" size = "10" value = "' . $area_util . '" style = "text-align: right;"></div>';

                                                echo ' <div class = "form2-varchar">Obra';
                                                echo ' <br><select name = "obra" id = "obra" style = "width: 100px;">';
                                                echo ' <option></option>';
                                                echo ' <option>Pronta</option>';
                                                echo ' <option>Parada</option>';
                                                echo ' <option>Em construção</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Topografia';
                                                echo ' <br><select name = "topografia" id = "topografia" style = "width: 150px;">';
                                                echo ' <option></option>';
                                                echo ' <option>Aclive</option>';
                                                echo ' <option>Declive</option>';
                                                echo ' <option>Leve Aclive</option>';
                                                echo ' <option>Leve Declive</option>';
                                                echo ' <option>Plano</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Proprietário<br><input type = "text" name = "proprietario" id = "proprietario" size = "10" value = "' . $proprietario . '" ondblclick = "quadro_edita(\'proprietario\',\'proprietario\',document.getElementById(\'proprietario\').value);" title = "Duplo-Clique para + editar" style = "cursor: pointer;background-image:url(http://sgiplus.com.br/img/edit_add.png);background-repeat:no-repeat;background-position: right;"></div>';
                                                echo ' <div class = "form2-varchar">Finalidade de Uso';
                                                echo ' <br><select name = "finalidade" id = "finalidade" style = "width: 130px;">';
                                                echo ' <option></option>';
                                                echo ' <option>Residencial</option>';
                                                echo ' <option>Comercial</option>';
                                                echo ' <option>Industrial</option>';
                                                echo ' </select>';
                                                echo ' </div>';

                                                echo ' <div class = "form2-varchar">Zoneamento';
                                                echo ' <br><select name = "zoneamento" id = "zoneamento" style = "width: 120px;">';
                                                echo ' <option></option>';
                                                echo ' <option>Comercial</option>';
                                                echo ' <option>Industrial</option>';
                                                echo ' <option>Residencial</option>';
                                                echo ' <option>ZUP-01</option>';
                                                echo ' <option>ZUP-03</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Situação do imóvel';
                                                echo ' <br><select name = "situacao" id = "situacao" style = "width: 120px;">';
                                                echo ' <option>Pendente</option>';
                                                echo ' <option>Ativo</option>';
                                                echo ' <option>Suspenso</option>';
                                                echo ' <option>Vendido</option>';
                                                echo ' <option>Vendido (Imobiliaria)</option>';
                                                echo ' <option>Locado</option>';
                                                echo ' <option>Administrado</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Tipo do imóvel';
                                                echo ' <br><select name = "subtipo_nome" id = "subtipo_nome" style = "width: 100px;">';
                                                echo ' <option>Chácara</option>';
                                                echo ' <option>Sítio</option>';
                                                echo ' <option>Fazenda</option>';
                                                echo ' <option>Aras</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Chaves<br><input type = "text" name = "chaves" id = "chaves" size = "40" value = "' . $chaves . '"></div>';
                                                echo ' <div class = "form2-varchar">Ano Construção<br><input type = "text" name = "ano_construcao" id = "ano_construcao" size = "4" value = "' . $ano_construcao . '" style = "text-align: right;"></div>';
                                                echo ' <div class = "form2-varchar">Ano Reforma<br><input type = "text" name = "ano_reforma" id = "ano_reforma" size = "4" value = "' . $ano_reforma . '" style = "text-align: right;"></div>';

                                                echo '</fieldset>';
                                                echo '<fieldset style = "position: absolute;top: 410px;left:00px;width: 345px;height: 180px;">';
                                                echo ' <div class = "form2-varchar">Valor Locação<br><input type = "text" name = "valor_locacao" id = "valor_locacao" size = "13" value = "' . us_br($valor_locacao) . '" style = "text-align: right;"><input type = "checkbox" name = "para_locacao" id = "para_locacao" value = "C"></div>';
                                                echo ' <div class = "form2-varchar"><br>&nbsp;Consulte&nbsp;</div>';
                                                echo ' <div class = "form2-varchar">Valor Venda<br><input type = "checkbox" name = "para_venda" id = "para_venda" value = "C"><input type = "text" name = "valor_venda" id = "valor_venda" size = "12" value = "' . us_br($valor_venda) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'mult\',\'valor_metro\',\'area_terreno\',\'valor_venda\');"></div>';
                                                echo ' <div class = "form2-varchar">Valor IPTU<br><input type = "text" name = "valor_iptu" id = "valor_iptu" size="10" value = "' . us_br($valor_iptu) . '" style = "text-align: right;"></div>';
                                                echo ' <div class = "form2-varchar">Valor Condomínio<br><input type = "text" name = "valor_condominio" id = "valor_condominio" size = "13" value = "' . us_br($valor_condominio) . '" style = "text-align: right;"></div>';
                                                echo ' <div class = "form2-varchar">Valor M²<br><input type = "text" name = "valor_metro" id = "valor_metro" size = "10" value = "' . us_br($valor_metro) . '" style = "text-align: right;"><img src="http://sgiplus.com.br/img/calculadora.png" width="20" alt="Calcular" title="Calcular" style="margin-top: 0px;" onclick="calcula(\'div\',\'valor_venda\',\'area_terreno\',\'valor_metro\');"></div>';
                                                echo ' <div class = "form2-varchar">Permuta por<br><input type = "text" list = "data_permuta" name = "permuta" id = "permuta" size = "48" value = "' . $permuta . '"></div>';
                                                echo ' <datalist id = "data_permuta">';
                                                echo ' <option>CASA</option>';
                                                echo ' <option>CARRO</option>';
                                                echo ' <option>CASA+CARRO</option>';
                                                echo ' </datalist>';
                                                echo ' <div class = "form2-varchar">Publicar Internet?';
                                                echo ' <br><select name = "internet" id = "internet" style = "width: 120px;">';
                                                echo ' <option>Não</option>';
                                                echo ' <option>Sim</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Destaque/Oferta?';
                                                echo ' <br><select name = "destaque" id = "destaque" style = "width: 120px;">';
                                                echo ' <option>Não</option>';
                                                echo ' <option>Sim</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo '</fieldset>';
                                                echo '<fieldset style = "position: absolute;top: 410px;left:380px;width: 740px;height: 180px;">';
                                                echo ' <div class = "form2-textarea">Observações (Internas da Imobiliária)<br><textarea name = "observacao" id = "observacao" rows = "8" cols = "45">' . $observacao . '</textarea></div>';
                                                echo ' <div class = "form2-textarea">Descrição do Imóvel (Públicas)<br><textarea name = "descricao" id = "descricao" rows = "8" cols = "45">' . $descricao . '</textarea></div>';
                                                echo '</fieldset>';


                                                ///
                                                echo '<fieldset style = "position: absolute;top: 620px;left:0px;width: 1120px;height: 170px;">';

                                                echo ' <div class = "form2-textarea">Condições Pagamento<br><textarea name = "condicoes_pagamento" id = "condicoes_pagamento" rows = "8" cols = "45">' . $condicoes_pagamento . '</textarea></div>';
                                                echo ' <div class = "form2-varchar">Frente Para';
                                                echo ' <br><select name = "frente" id = "frente" style = "width: 80px;">';
                                                echo ' <option></option>';
                                                echo ' <option>Norte</option>';
                                                echo ' <option>Sul</option>';
                                                echo ' <option>Leste</option>';
                                                echo ' <option>Oeste</option>';
                                                echo ' </select>';
                                                echo ' </div>';
                                                echo ' <div class = "form2-varchar">Exclusividade Até<br><input type = "text" class = "datepicker" name = "exclusividade_ate" id = "exclusividade_ate" size = "12" value = "' . data_decode($exclusividade_ate) . '"></div>';
                                                echo ' <div class = "form2-varchar">Nunca Usado?';
                                                echo ' <br><select name = "nunca_usado" id = "nunca_usado" style = "width: 120px;">';
                                                echo ' <option>Não</option>';
                                                echo ' <option>Sim</option>';
                                                echo ' </select>';
                                                echo ' </div>';



                                                echo ' <div class = "form2-varchar">URL Youtube<br><input type = "text" name = "video_youtube" id = "video_youtube" size = "80" value = "' . $video_youtube . '">';
                                                if (strpos($video_youtube, 'youtube.com')) {
                                                    echo '&nbsp;<a href="javascript: void(0);" onclick="window.open(\'' . str_replace('/watch?v=', '/embed/', $video_youtube) . '\',\'_blank\',\'top=100,left=100,width=500,height=400,status=no,adressbar=no\');" title="Clique para ver o vídeo"><img src="../img/youtube.png" width="20"></a>';
                                                } else {
                                                    echo '&nbsp; Utilizar http://www.youtube.com...';
                                                }
                                                echo ' </div>';

                                                echo '</fieldset>';



                                                ///
                                            }
                                            //
                                            // * VI
                                            //11-10-2014
                                            echo '<fieldset style = "position: absolute;top: 820px;left:0px;width: 1120px;height: auto;">';
                                            echo ' <div class = "form2-varchar">Captado (Venda) por<br>';
                                            echo ' <select name="captado_venda_por" id="captado_venda_por">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                $xsel = '';
                                                if ($captado_locacao_por == $row[0]) {
                                                    $xsel = 'selected';
                                                }
                                                echo '<option value = "' . $row[0] . '" '.$xsel.'>' . $row[0] . '</option>';
                                            }
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" class = "datepicker" name = "data_captacao_venda" id = "data_captacao_venda" size = "12" value = "' . data_decode($data_captacao_venda) . '"></div>';
                                            echo ' <div class = "form2-varchar">Placa (Venda)<br>';
                                            echo ' <input type = "text" list = "dt_placa_venda" name = "placa_venda" id = "placa_venda" size = "30">';
                                            echo ' <datalist id = "dt_placa_venda">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" class = "datepicker" name = "data_placa_venda" id = "data_placa_venda" size = "12" value = "' . data_decode($data_placa_venda) . '"></div>';
                                            echo ' <div class = "form2-varchar">Participante<br>';
                                            echo ' <input type = "text" list = "data_participante_venda" name = "participante_venda" id = "participante_venda" size = "30">';
                                            echo ' <datalist id = "data_participante_venda">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo '</fieldset>';
                                            //
                                            // * VF
                                            //11-10-2014
                                            echo '<fieldset style = "position: absolute;top: 890px;left:0px;width: 1120px;height: auto;">';
                                            echo ' <div class = "form2-varchar">Captado (Locação) por<br>';
                                            echo ' <select name="captado_locacao_por" id="captado_locacao_por">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                $xsel = '';
                                                if ($captado_locacao_por == $row[0]) {
                                                    $xsel = 'selected';
                                                }
                                                echo '<option value = "' . $row[0] . '" '.$xsel.'>' . $row[0] . '</option>';
                                            }
                                            echo ' </select>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" class = "datepicker" name = "data_captacao_locacao" id = "data_captacao_locacao" size = "12" value = "' . data_decode($data_captacao_locacao) . '"></div>';
                                            echo ' <div class = "form2-varchar">Placa (Locação)<br>';
                                            echo ' <input type = "text" list = "dt_placa_locacao" name = "placa_locacao" id = "placa_locacao" size = "30">';
                                            echo ' <datalist id = "dt_placa_locacao">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" class = "datepicker" name = "data_placa_locacao" id = "data_placa_locacao" size = "12" value = "' . data_decode($data_placa_locacao) . '"></div>';
                                            echo ' <div class = "form2-varchar">Participante<br>';
                                            echo ' <input type = "text" list = "data_participante_locacao" name = "participante_locacao" id = "participante_locacao" size = "30">';
                                            echo ' <datalist id = "data_participante_locacao">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM corretor ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Prev.Fim Locação<br><input type = "text" class = "datepicker" name = "prev_fim_locacao" id = "prev_fim_locacao" size = "20" value = "' . data_decode($prev_fim_locacao) . '"></div>';
                                            echo '</fieldset>';


                                            //
                                            // * XI
                                            //
                                                echo '<fieldset style = "position: absolute;top: 960px;left:0px;width: 1120px;height: auto;">';
                                            echo '<div style = "width: 900px;height: 45px">';
                                            echo ' <div class = "form2-varchar">Atualizado por<br>';
                                            echo ' <input type = "text" list = "data_atualizado_por" name = "atualizado_por" id = "atualizado_por">';
                                            echo ' <datalist id = "data_atualizado_por">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM usuario WHERE nome !='ADMIN' ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" class = "datepicker" name = "data_atualizacao" id = "data_atualizacao" size = "12" value = "' . data_decode($data_atualizacao) . '"></div>';
                                            //
                                            //
                                                //
                                            if ($id == 'add') {
                                                if (empty($cadastro_por)) {
                                                    $cadastro_por = $_SESSION['usuario_nome'];
                                                }
                                                if (empty($cadastro_data)) {
                                                    $cadastro_data = date('Ymd');
                                                }
                                                if (empty($cadastro_hora)) {
                                                    $cadastro_hora = date('H:i');
                                                }
                                                $readonly = '';
                                            } else {
                                                $readonly = ' readonly class = "desativado"';
                                            }
                                            echo ' <div class = "form2-varchar">Cadastrado por<br>';
                                            echo ' <input type = "text" list = "data_cadastro_por" name = "cadastro_por" id = "cadastro_por" ' . $readonly . '>';
                                            echo ' <datalist id = "data_cadastro_por">';
                                            include '../model/conexao.php';
                                            $conexao = new conexao();
                                            $ret = $conexao->db->query("SELECT nome FROM usuario WHERE nome !='ADMIN' ORDER BY nome");
                                            while ($row = $ret->fetch()) {
                                                echo '<option value = "' . $row[0] . '">' . $row[0] . '</option>';
                                            }
                                            echo ' </datalist>';
                                            echo ' </div>';
                                            echo ' <div class = "form2-varchar">Data<br><input type = "text" ';
                                            if ($id == 'add') {
                                                echo 'class = "datepicker"';
                                            }
                                            echo ' name = "cadastro_data" id = "cadastro_data" size = "12" value = "' . data_decode($cadastro_data) . '" ' . $readonly . '></div>';
                                            echo ' <div class = "form2-varchar">Horário<br><input type = "text" name = "cadastro_hora" id = "cadastro_hora" size = "5" value = "' . $cadastro_hora . '" ' . $readonly . '></div>';
                                            echo '</div>';
                                            echo '</fieldset>';
                                            echo ' <div style = "width: 100%;height: 30px;"></div>';



                                            echo "\r\n";

                                            echo '<script>';
                                            if ($id != 'add') {
                                                if ($tipo_nome == 'Casas') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('obra').value = '$obra';";
                                                    echo "document.getElementById('estado_construcao').value = '$estado_construcao';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('lancamento').value = '$lancamento';";
                                                    echo "document.getElementById('nunca_usado').value = '$nunca_usado';";
                                                } elseif ($tipo_nome == 'Apartamentos') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('obra').value = '$obra';";
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('estado_construcao').value = '$estado_construcao';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    echo "document.getElementById('lancamento').value = '$lancamento';";
                                                    echo "document.getElementById('nunca_usado').value = '$nunca_usado';";
                                                } elseif ($tipo_nome == 'Terrenos') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('topografia').value = '$topografia';";
                                                    echo "document.getElementById('zoneamento').value = '$zoneamento';";
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                } elseif ($tipo_nome == 'Galpões') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('obra').value = '$obra';";
                                                    echo "document.getElementById('topografia').value = '$topografia';";
                                                    echo "document.getElementById('zoneamento').value = '$zoneamento';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                    echo "document.getElementById('lancamento').value = '$lancamento';";
                                                    echo "document.getElementById('nunca_usado').value = '$nunca_usado';";
                                                } elseif ($tipo_nome == 'Comercial') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('obra').value = '$obra';";
                                                    echo "document.getElementById('topografia').value = '$topografia';";
                                                    echo "document.getElementById('zoneamento').value = '$zoneamento';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('lancamento').value = '$lancamento';";
                                                    echo "document.getElementById('nunca_usado').value = '$nunca_usado';";
                                                } elseif ($tipo_nome == 'Rural') {
                                                    echo "document.getElementById('subtipo_nome').value = '$subtipo_nome';";
                                                    echo "document.getElementById('obra').value = '$obra';";
                                                    echo "document.getElementById('topografia').value = '$topografia';";
                                                    echo "document.getElementById('finalidade').value = '$finalidade';";
                                                    echo "document.getElementById('zoneamento').value = '$zoneamento';";
                                                    echo "document.getElementById('situacao').value = '$situacao';";
                                                    echo "document.getElementById('internet').value = '$internet';";
                                                    echo "document.getElementById('destaque').value = '$destaque';";
                                                    if ($para_venda == 'C') {
                                                        echo "document.getElementById('para_venda').checked = true;";
                                                    }
                                                    if ($para_locacao == 'C') {
                                                        echo "document.getElementById('para_locacao').checked = true;";
                                                    }
                                                    echo "document.getElementById('frente').value = '$frente';";
                                                    echo "document.getElementById('nunca_usado').value = '$nunca_usado';";
                                                }
                                            }
                                            echo "document.getElementById('captado_venda_por').value = '$captado_venda_por';";
                                            echo "document.getElementById('captado_locacao_por').value = '$captado_locacao_por';";
                                            echo "document.getElementById('participante_venda').value = '$participante_venda';";
                                            echo "document.getElementById('participante_locacao').value = '$participante_locacao';";
                                            echo "document.getElementById('placa_venda').value = '$placa_venda';";
                                            echo "document.getElementById('placa_locacao').value = '$placa_locacao';";
                                            echo "document.getElementById('cadastro_por').value = '$cadastro_por';";
                                            echo "document.getElementById('atualizado_por').value = '$atualizado_por';";

                                            echo '</script>';
                                        }
                                        ?>
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
                                <div class="envia-email" id="envia-email">
                                    <img src="img/Button Close-01.png" width="15" alt="" style="position: absolute; top: 5px; right: 5px; cursor: pointer;" onclick="document.getElementById('envia-email').style.display = 'none';">
                                    <div class="form2-varchar">De
                                        <br>
                                        <select name="email_de" id="email_de">
                                            <?php
                                            include '../controller/site_config.php';
                                            include '../controller/cadastro.php';
                                            $cfg = json_decode(site_config_carregar());
                                            $email_envio = 'envios@sgifacil.com.br';
                                            if (!empty($cfg->email_envio)) {
                                                $email_envio = $cfg->email_envio;
                                                echo '<option value="0">Imobiliária (' . $email_envio . ')</option>';
                                            }
                                            $cors = json_decode(cadastro_listar('corretor', " and email!='' ", $order, $rows));
                                            foreach ($cors as $cor_id) {
                                                $cor = json_decode(cadastro_carregar('corretor', $cor_id));
                                                echo '<option value="' . $cor->id . '">' . $cor->nome . ' (' . $cor->email . ')</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form2-varchar">Para<br><input type="text" name="email_nome" id="email_nome" size="50"></div>
                                    <div class="form2-varchar">E-mail<br><input type="text" name="email_para" id="email_para" size="50"></div>
                                    <div class="form2-varchar" style="width: 300px;">
                                        <center>
                                            <img src="img/ajax-loader2.gif" id="ajax-loader" style="position: absolute; top: 110px; left: 15px;display: none;">&nbsp;<input type="button" value="Enviar por email" class="botao" onclick = "enviar_email('<?php echo $_GET['id']; ?>');">
                                        </center>
                                    </div>
                                </div>
                                <?php
                                if ($_SESSION['tipo_cadastro'] == 'imovel') {
                                    echo '<div class="div-flutuante" id="div-fotos">';
                                    echo '    <div class="form-titulo">';
                                    echo '        Fotos do Imóvel';
                                    echo '        <div style="position: absolute;top: 5px;right: 25px;z-index:999;"><a href="javascript: void(0);" onclick="oculta_fotos(\'' . $id . '\');">[ Fechar X ]</a></div>';
                                    echo '    </div>';
                                    echo '    <div style="width: 100%;height: 35px;float: left;"></div>';
                                    echo '    <iframe width="99%" height="95%" name="cadastro_foto" frameborder="no" scrolling="vertical" src="cadastro_foto_upload.php?id=' . $id . '"></iframe>';
                                    echo '</div>';
                                    $kad = new cadastro();
									if (!empty($logradouro) && !empty($bairro) && !empty($cidade)) {
										echo '<div style="position: absolute; top: 120px; left: 700px; z-index: 999;"><a href="javascript: void(0);" onclick="window.open(\'abre_mapa.php?endereco='.$logradouro.', '.$numero.' - '.$bairro.' - '.$cidade.'  \',\'_blank\',\'width=800,height=600,top=200,left=200\');"><img src="http://sgiplus.com.br/img/maps.png" alt="Mostrar mapa do endereço do imóvel" title="Mostrar mapa do endereço do imóvel" width="30"> Mapa</a></div>';
									}
                                }
                                ?>
                            </div>

                            </body>
                            </html>
