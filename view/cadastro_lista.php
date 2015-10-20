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

if (isset($_REQUEST['campo_valor'])) {
    $campo_valor = $_REQUEST['campo_valor'];
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
        <script src="../controller/js/cadastro.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $idd = $id;
        if ($idd == 'add') {
            $idd = '';
        }
        include '../controller/usuario_acesso.php';
        $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
        $tmp = $tipo_edita . '_consultar';
        if ($usu->$tmp != 'Sim') {
            echo '<script>alert("Você não tem permissões para essa operação.' . $tmp . '");</script>';
            echo '<script>window.top.frmPrincipal.$(\'#quadro-edita\').fadeOut(500);window.top.frmPrincipal.$(\'#fundo-negro\').fadeOut(1000);</script>';
            exit();
        }
        echo '<div style="width: 100%;height: 20px;float: left;"></div>';
        echo '<input type="button" value="Voltar" class="botao" onclick="edita_voltar(\'' . $tipo_edita . '\',\'' . $campo_edita . '\',\'' . $idd . '\',\'' . $campo_valor . '\');">';
        $tmp = $tipo_edita . '_alterar';
        if ($usu->$tmp == 'Sim') {
            echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'cadastro_edita.php?tipo_edita=' . $tipo_edita . '&campo_edita=' . $campo_edita . '&id=add\', \'_self\');">';
        }
        ?>
        <p>Pesquisar Por :</p>
        <form name="form1" id="form1" action="cadastro_lista.php" method="GET">
            <input type="hidden" name="id" value="<?php echo $idd; ?>">
            <input type="hidden" name="tipo_edita" value="<?php echo $tipo_edita; ?>">
            <input type="hidden" name="campo_edita" value="<?php echo $campo_edita; ?>">
            <input type="hidden" name="campo_valor" value="<?php echo $campo_valor; ?>">
            <input type="hidden" name="nova_pesquisa" value="S">


            <?php
            include '../controller/pesquisa.php';

            $lista = pesquisa_lista($tipo_edita);
            $x = 0;
            $cadastro_where = '';
            foreach ($lista as $camp) {

                $$camp = '';
                if (isset($_GET[$camp])) {
                    $$camp = $_GET[$camp];
                }
                $camp_tabela = json_decode(tabela_campo($tipo_edita, $camp));
                echo '<div style="width: 100%; height: auto;">';
                if (empty($camp_tabela->tabela_vinculo)) {
                    if (empty($camp_tabela->campo_opcao)) {
                        if ($camp_tabela->campo_tipo == 'ID') {
                            echo '  <input type="hidden" name="id" id="id" value="' . $id . '">';
                        } elseif ($camp_tabela->campo_tipo == 'VARCHAR') {
                            echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                            echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" style="width: 150px;">';
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $cadastro_where .= " and $camp LIKE '%" . $$camp . "%' ";
                                $pesquisando = 'S';
                            }
                        } elseif ($camp_tabela->campo_tipo == 'DATE') {
                            if (!empty($$camp)) {
                                $tmp = explode('-', $$camp);
                                $tmp_data = $tmp[0] . '-' . $tmp[1];
                            }
                            echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                            echo '  <br><input type="text"  class="datepicker" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="30" maxlength="30" value="' . $tmp_data . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $tmp = explode('-', $$camp);
                                $cadastro_where .= " and $camp BETWEEN " . data_encode($tmp[0]) . " and " . data_encode($tmp[1]) . "";
                                $pesquisando = 'S';
                            }
                        } elseif ($camp_tabela->campo_tipo == 'INT') {
                            echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                            echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="10" maxlength="10" value="' . $$camp . '">';
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $tmp = explode('-', $$camp);
                                $cadastro_where .= " and $camp BETWEEN " . $tmp[0] . " and " . $tmp[1] . "";
                                $pesquisando = 'S';
                            }
                        } elseif ($camp_tabela->campo_tipo == 'TEXT') {
                            echo '<div class="form-textarea">' . $camp_tabela->campo_nome;
                            echo '  <br><textarea name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" rows="5" cols="50">' . $$camp . '</textarea>';
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $cadastro_where .= " and $camp LIKE '%" . $$camp . "%' ";
                                $pesquisando = 'S';
                            }
                        } elseif ($camp_tabela->campo_tipo == 'DECIMAL') {
                            echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                            echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="20" value="' . $$camp . '">';
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $tmp = explode('-', $$camp);
                                $cadastro_where .= " and $camp BETWEEN " . $tmp[0] . " and " . $tmp[1] . "";
                                $pesquisando = 'S';
                            }
                        } elseif ($camp_tabela->campo_tipo == 'CHECKBOX') {
                            echo '<div class="form-varchar-lista">';
                            echo '  <br><input type="checkbox" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" value="S"> ' . $camp_tabela->campo_nome;
                            echo '</div>';
                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                $cadastro_where .= " and $camp = '" . $$camp . "' ";
                                $pesquisando = 'S';
                            }
                        }
                    } else {
                        echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                        echo '  <br><select name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '">';
                        echo '<option></option>';
                        $vinculo_tabela = explode('|', $camp_tabela->campo_opcao);
                        foreach ($vinculo_tabela as $camp_vinculo) {
                            $sel = '';
                            if ($$camp == $camp_vinculo) {
                                $sel = 'selected';
                            }
                            echo '      <option value="' . $camp_vinculo . '" ' . $sel . '>' . $camp_vinculo . '</option>';
                        }
                        echo '  <select>';
                        echo '</div>';
                        if (!empty($$camp) && empty($pesquisa_rapida)) {
                            $cadastro_where .= " and $camp LIKE '" . $$camp . "%' ";
                            $pesquisando = 'S';
                        }
                    }
                } else {
                    echo '<div class="form-varchar-lista">' . $camp_tabela->campo_nome;
                    echo '  <br><select name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '">';
                    echo '<option></option>';
                    $vinculo_tabela = json_decode(tabela_vinculo($camp_tabela->tabela_vinculo));
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
                    echo '  <select>';
                    echo '</div>';
                    if (!empty($$camp)) {
                        $cadastro_where .= " and $camp = '" . $$camp . "' ";
                    }
                }
                $x++;
            }
            ?>
            <div class="form-varchar-lista"><input class="botao" type="submit" value="Buscar" title="Buscar!"></div>
        </form>
        <?php
        echo '</div>';
        ?>
        <br>
        <div id="listagem">
            <p>Primeiros 20 resultados : </p>
            <table width="100%">
                <tr class="listagem-titulo">
                    <?php
                    $lista = json_decode(tabela_lista($tipo_edita));
                    foreach ($lista as $camp) {
                        if (!empty($camp->campo_nome)) {
                            echo '<td bgcolor="' . $cor . '" width="' . $camp->campo_tamanho . '">' . $camp->campo_nome;
                            echo '</td>';
                        }
                    }
                    ?>
                </tr>
                <?php
                if (isset($_GET['pagina'])) {
                    $pagina = $_GET['pagina'];
                } else {
                    $pagina = 1;
                }


                $itenspp = 20;

                $cadastro_rows = ' LIMIT ' . (($pagina - 1) * $itenspp) . ',' . $itenspp;
                $cadastro_order = '';

                include '../controller/cadastro.php';
                $ret = json_decode(cadastro_listar($tipo_edita, $cadastro_where, '', ''));
                $tot = count($ret);
                $paginas = ceil($tot / $itenspp);
                $ret = json_decode(cadastro_listar($tipo_edita, $cadastro_where, $cadastro_order, $cadastro_rows));
                foreach ($ret as $id) {
                    $cad = json_decode(cadastro_carregar($tipo_edita, $id));
                    echo '<tr class="listagem-item" ';
                    echo ' onclick="window.open(\'cadastro_edita.php?tipo_edita=' . $tipo_edita . '&campo_edita=' . $campo_edita . '&id=' . $id . '\', \'_self\');"';
                    echo '>';
                    $lista = json_decode(tabela_lista($tipo_edita));
                    foreach ($lista as $camp) {
                        $camp_dado = '';
                        if (!empty($camp->campo_nome)) {
                            $name_campo = $camp->campo;
                            $tabela = json_decode(tabela_campo($tipo_edita, $camp->campo));
                            if (isset($tabela->tabela_vinculo)) {
                                if (!empty($tabela->tabela_vinculo) && !empty($cad->$name_campo)) {
                                    $camp_dado = tabela_carregar_campo($tabela->tabela_vinculo, $tabela->tabela_texto, $cad->$name_campo);
                                    if (empty($camp_dado)) {
                                        $camp_dado = $tabela->tabela_vinculo . '->' . $tabela->tabela_texto . '->' . $cad->$name_campo;
                                    }
                                }
                            }
                            if (empty($camp_dado)) {
                                $camp_dado = $cad->$name_campo;
                            }
                            if ($tabela->campo_tipo == 'DECIMAL') {
                                $camp_dado = number_format($camp_dado, 2, ',', '.');
                            } elseif ($tabela->campo_tipo == 'DATE') {
                                $camp_dado = data_decode($camp_dado);
                            }
                            echo '<td width="' . $camp->campo_tamanho . '">' . $camp_dado . '</td>';
                        }
                    }
                    echo '</tr>';
                }
                //echo '<tr class="listagem-titulo"><td colspan="' . count($lista) . '" bgcolor="' . $cor . '">Total ' . $tot . ' registro(s). ';
                if ($paginas > 1) {
                    echo 'Página ' . $pagina . ' de ' . $paginas;
                }
                echo '</td></tr>';
                ?>

            </table>
        </div>
        <?php
        echo '</div>';
        ?>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>