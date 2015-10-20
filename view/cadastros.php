<?php
session_start();
include 'func.php';

include '../controller/usuario.php';
include '../controller/usuario_acesso.php';
include '../controller/cadastro.php';

$usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));

$dia = date('d');
$mes = date('m');
$ano = date('Y');
$mes--;
if ($mes < 1) {
    $mes = 12;
    $ano--;
}
$data_30 = $ano . str_pad($mes, 2, '0', 0) . $dia;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css?xyz=<?php echo rand(); ?>" rel="stylesheet" />
        <link href="css/base.css?xyz=<?php echo rand(); ?>" rel="stylesheet" />
        <link href="css/menu.css?xyz=<?php echo rand(); ?>" rel="stylesheet" />
        <link href="css/cadastro.css?xyz=<?php echo rand(); ?>" rel="stylesheet" />
        <script src="http://sgiplus.com.br/controller/js/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://sgiplus.com.br/view/css/jquery-ui.css">
        <script src="http://sgiplus.com.br/controller/js/jquery-1.9.1.js"></script>
        <script src="http://sgiplus.com.br/controller/js/jquery-ui.js"></script>
        <script src="../controller/js/cadastro.js?xyz=<?php echo rand(); ?>"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        if (isset($_GET['tabelas'])) {
            $_SESSION['tabelas'] = $_GET['tabelas'];
        }

        if ($_SESSION['tabelas'] != 'Sim') {
            $m = 'Cadastros';
        } else {
            $m = 'Tabelas';
        }
        include 'menu.php';

        if (isset($_GET['id_cadastro'])) {
            $_SESSION['id_cadastro'] = $_GET['id_cadastro'];
            unset($_SESSION['tipo_cadastro']);
            unset($_SESSION['cadastro_where']);
            unset($_SESSION['pesquisa']);
        }
        $tipo_cadastro = '';
        $name_tipo_cadastro = '';
        $pesquisando = 'N';

        if (isset($_SESSION['tipo_cadastro'])) {
            $tipo_cadastro = $_SESSION['tipo_cadastro'];
        }

        include '../controller/tabela.php';

        if (isset($_SESSION['id_cadastro'])) {
            $tcad = json_decode(tipo_cadastro_carregar($_SESSION['id_cadastro']));
            $name_tipo_cadastro = $tcad->tipo;
            $tipo_cadastro = $tcad->tabela;
            $_SESSION['tipo_cadastro'] = $tipo_cadastro;
            $cor = $tcad->cor;
            echo '<style>';
            echo '.botao {';
            echo '  background: ' . $cor . '!important;';
            echo '  border: 2px solid ' . $cor . '!important;';
            echo '}';
            echo '</style>';
        }
        if (!empty($name_tipo_cadastro)) {

            if (isset($_SESSION['pesquisa'])) {
                $pesquisa = $_SESSION['pesquisa'];
            } else {
                $pesquisa = array();
                $_SESSION['pesquisa'] = $pesquisa;
            }

            if (isset($_SESSION['pesquisa'][$tipo_cadastro])) {
                $pesquisa = $_SESSION['pesquisa'][$tipo_cadastro];
            } else {
                $pesquisa[$tipo_cadastro] = array();
                $_SESSION['pesquisa'][$tipo_cadastro] = $pesquisa[$tipo_cadastro];
            }

            if (isset($_SESSION['pesquisa'][$tipo_cadastro]['pagina'])) {
                $pagina = $_SESSION['pesquisa'][$tipo_cadastro]['pagina'];
            } else {
                $pagina = 1;
            }

            if (isset($_GET['pagina'])) {
                $pagina = $_GET['pagina'];
            }

//            if (isset($_SESSION['pesquisa'][$tipo_cadastro]['where'])) {
//                $cadastro_where = $_SESSION['pesquisa'][$tipo_cadastro]['where'];
//            } else {
//                $cadastro_where = '';
//            }

            if (isset($_SESSION['pesquisa'][$tipo_cadastro]['order'])) {
                $cadastro_order = $_SESSION['pesquisa'][$tipo_cadastro]['order'];
            } else {
                $cadastro_order = '';
            }

            if (isset($_SESSION['pesquisa'][$tipo_cadastro]['asc_desc'])) {
                $asc_desc = $_SESSION['pesquisa'][$tipo_cadastro]['asc_desc'];
            }

            if (isset($_GET['ordem'])) {
                $ordem = $_GET['ordem'];
                if (!empty($ordem)) {
                    if ($asc_desc == 'ASC') {
                        $asc_desc = 'DESC';
                    } else {
                        $asc_desc = 'ASC';
                    }
                    $_SESSION['pesquisa'][$tipo_cadastro]['asc_desc'] = $asc_desc;

                    $cadastro_order = " ORDER BY $ordem $asc_desc ";
                    $_SESSION['pesquisa'][$tipo_cadastro]['order'] = $cadastro_order;
                    //$pesquisando = 'S';
                }
            }
            $cadastro_home = '';
            $cadastro_data = '';
            $cadastro_datai = '';
            $cadastro_dataf = '';

            if (isset($_SESSION['cadastro_home'])) {
                $cadastro_home = $_SESSION['cadastro_home'];
            }

            if (isset($_SESSION['cadastro_data'])) {
                $cadastro_data = $_SESSION['cadastro_data'];
            }

            if (isset($_SESSION['cadastro_datai'])) {
                $cadastro_datai = $_SESSION['cadastro_datai'];
            }

            if (isset($_SESSION['cadastro_dataf'])) {
                $cadastro_dataf = $_SESSION['cadastro_dataf'];
            }

            $titulo_home = '';
            if (isset($_GET['home'])) {
                $cadastro_home = $_GET['home'];
                if ($cadastro_home == '1') {
                    $titulo_home = 'Imóveis Atualizados nos Últimos 30 dias ';
                } elseif ($cadastro_home == '2') {
                    $titulo_home = 'Imóveis Desatualizados (entre 31 e 60 dias) ';
                } elseif ($cadastro_home == '3') {
                    $titulo_home = 'Desatualizados (acima de 60 dias)  ';
                } elseif ($cadastro_home == '4') {
                    $titulo_home = 'Sem data de atualização  ';
                } elseif ($cadastro_home == '5') {
                    $titulo_home = 'Propostas Pendentes  ';
                } elseif ($cadastro_home == '6') {
                    $titulo_home = 'Propostas Em andamento  ';
                }
            }

            if (isset($_GET['data'])) {
                $cadastro_data = $_GET['data'];
            }

            if (isset($_GET['datai'])) {
                $cadastro_datai = $_GET['datai'];
            }

            if (isset($_GET['dataf'])) {
                $cadastro_dataf = $_GET['dataf'];
            }


            if (isset($_SESSION['pesquisa_rapida'])) {
                $pesquisa_rapida = $_SESSION['pesquisa_rapida'];
            }

            if (isset($_GET['pesquisa_rapida'])) {
                $pesquisa_rapida = filtra($_GET['pesquisa_rapida']);
            }


            if (isset($_GET['referencia_rapida'])) {
                $referencia_rapida = filtra($_GET['referencia_rapida']);
            }

//            if (isset($_SESSION['referencia_rapida'])) {
//                $referencia_rapida = $_SESSION['referencia_rapida'];
//            }

            if (isset($_GET['tipo_pesquisa_rapida'])) {
                $tipo_pesquisa_rapida = $_GET['tipo_pesquisa_rapida'];
            } elseif (isset($_SESSION['tipo_pesquisa_rapida'])) {
                if (!empty($_SESSION['tipo_pesquisa_rapida'])) {
                    $tipo_pesquisa_rapida = $_SESSION['tipo_pesquisa_rapida'];
                }
            }

            if (empty($tipo_pesquisa_rapida)) {
                $tipo_pesquisa_rapida = 0;
            }

            if (!empty($pesquisa_rapida) || !empty($referencia_rapida)) {
                $pesquisando = 'S';
            }

            $titulo_pesquisa = '';
            if ($tipo_cadastro == 'imovel') {
                $titulo_pesquisa = 'Pesquise aqui rapidamente por Bairro, Cidade, Endereço, Localização, Condomínio, Tipo, Subtipo, CEP, Observação ou Descricao que contenha...;';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'proprietario' || $tipo_cadastro == 'comprador') {
                $titulo_pesquisa = 'Pesquise aqui rapidamente por Apelido, Nome, CPF, RG, Profissão, CEP, Logradouro, Complemento, Bairro, Cidade, Fones, Contato, E-mail, Observação que contenha...;';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'corretor') {
                $titulo_pesquisa = 'Pesquise aqui rapidamente por Apelido, Nome, CEP, Logradouro, Complemento, Bairro, Cidade, Fones, E-mail, Observação que contenha...;';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'tabela') {
                $titulo_pesquisa = '';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'lista') {
                $titulo_pesquisa = '';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'pesquisa') {
                $titulo_pesquisa = '';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'portal') {
                $titulo_pesquisa = '';
                $pesquisar = 'S';
            } elseif ($tipo_cadastro == 'portal_tag') {
                $titulo_pesquisa = '';
                $pesquisar = 'S';
            } else {
                $pesquisar = 'N';
            }

            if (isset($_GET['nova_pesquisa'])) {
                if ($_GET['nova_pesquisa'] == 'S') {
                    $pagina = 1;
                    $cadastro_where = '';
                    $cadastro_order = '';
                    $cadastro_home = '';
                    $cadastro_data = '';
                    $cadastro_datai = '';
                    $cadastro_dataf = '';
                    $titulo_home = '';
                }
            }
            ?>
            <div id="conteudo">
                <h3>Cadastro de <?php echo $name_tipo_cadastro; ?></h3>
                <?php
                $tmp = $_SESSION['tipo_cadastro'] . '_alterar';
                if ($tipo_cadastro == 'imovel' && $usu->$tmp == 'Sim') {
                    //
                    echo '<div class="tipos-imovel" id="tipos-imovel">';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Casas\', \'_self\');">Casas</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Apartamentos\', \'_self\');">Apartamentos</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Terrenos\', \'_self\');">Terrenos</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Galpões\', \'_self\');">Galpões</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Comercial\', \'_self\');">Comercial</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro.php?id=add&tipo_nome=Rural\', \'_self\');">Rural</div>';
                    echo '  <div class="tipo-imovel" onclick="window.open(\'cadastro_ficha_captacao.php\',\'_blank\', \'width=800,height=800,menubar=no,status=no\');">Ficha Captação</div>';
                    if ($usuario->acesso >= 20) {
                        echo '  <div class="tipo-imovel" onclick="window.open(\'relatorios.php\',\'_self\');">Relatórios</div>';
                    }
                    echo '</div>';
                }
                ?>
                <form name="form1" id="form1" action="cadastros.php" method="GET">
                    <input type="hidden" name="nova_pesquisa" value="S">
                    <?php
                    $tmp = $_SESSION['tipo_cadastro'] . '_alterar';
                    if ($usu->$tmp == 'Sim' && $tipo_cadastro == 'cliente') {
                        echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'cliente.php?id=add\', \'_self\');" title="Incluir Novo Cadastro">';
                    } elseif ($usu->$tmp == 'Sim' && $tipo_cadastro != 'imovel') {
                        echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'cadastro.php?id=add\', \'_self\');" title="Incluir Novo Cadastro">';
                    }
                    include '../controller/pesquisa.php';
                    $lista = pesquisa_lista($tipo_cadastro);
                    if ($pesquisar == 'S' || !empty($lista)) {
                        echo '<input  style="margin-left: 6px;" type="button" value="Pesquisar" id="Pesquisar" class="botao" onclick="cadastro_pesquisar();" title="Pesquisar Detalhadamente">';
                        //echo '<input type="button" value="Imprimir" class="botao" title="Imprimir Listagem da Tela">';
                        if ($usuario->acesso >= 20) {
                            echo '&nbsp;&nbsp;<input type="button" value="Salvar Excel" class="botao" onclick="window.open(\'salva_excel.php?tipo_cadastro=' . $tipo_cadastro . '\', \'_blank\');" style="background: #008000 !important;border-color: #fff !important;height: 38px;">';
                        }
                        if (!empty($titulo_home)) {
                            echo '| ' . $titulo_home;
                        } elseif (!empty($titulo_home) && $tipo_cadastro == 'imovel') {
                            echo '| <span id="titulo-home"></span>';
                        } elseif (empty($titulo_home) && $tipo_cadastro == 'imovel') {
                            echo '| <span id="titulo-home">Todos os Imóveis</span>';
                        }
                        ?>
                        <div id="pesq_rapida" style="float:right;margin-right: 20px;text-align: right;"><?php
                            if ($tipo_cadastro == 'imovel') {
                                echo 'Refer&ecirc;ncia : <input type="text" name="referencia_rapida" id="referência_rapida" value="' . $referencia_rapida . '" size="10" maxlength="10"><input type="submit" value="&crarr;" title="Buscar!"><br>';


                                $chk1 = 'checked';
                                $chk2 = $chk3 = '';
                                if ($tipo_pesquisa_rapida == 1) {
                                    $chk1 = 'checked';
                                }
                                if ($tipo_pesquisa_rapida == 2) {
                                    $chk2 = 'checked';
                                }
                                if ($tipo_pesquisa_rapida == 3) {
                                    $chk3 = 'checked';
                                }
                                ?>Pesquisa R&aacute;pida 
                                <input type="radio" name="tipo_pesquisa_rapida" id="tipo_pesquisa_rapida" value="1" <?php echo $chk1; ?> > Exatamente : 
                                <input type="radio" name="tipo_pesquisa_rapida" id="tipo_pesquisa_rapida" value="2" <?php echo $chk2; ?> > Contendo : 
                                <input type="radio" name="tipo_pesquisa_rapida" id="tipo_pesquisa_rapida" value="3" <?php echo $chk3; ?> > Começando com... : 

                                <?php
                            }
                            ?>

                            <input type="text" name="pesquisa_rapida" id="pesquisa_rapida" value="<?php echo $pesquisa_rapida; ?>" size="60" maxlength="60" onkeypress="if (this.value != '') {
                                                $('#pesquisa').fadeOut(100);
                                            }
                                            ;" title="<?php echo $titulo_pesquisa; ?>"><input type="submit" value="&crarr;" title="Buscar!"></div>
                        <div id="pesquisa">
                            <div style="width: 100%;height: auto; padding: 20px; float: left;">
                                <?php
                                if ($tipo_cadastro != 'imovel') {

                                    $x = 0;

                                    foreach ($lista as $camp) {

                                        $$camp = '';
                                        $camp_tabela = json_decode(tabela_campo($tipo_cadastro, $camp));

                                        if (isset($_SESSION['pesquisa'][$tipo_cadastro][$camp]) && empty($pesquisa_rapida)) {
                                            $pesquisa[$tipo_cadastro][$camp] = $_SESSION['pesquisa'][$tipo_cadastro][$camp];
                                            $$camp = $pesquisa[$tipo_cadastro][$camp];
                                        } else {
                                            $pesquisa[$tipo_cadastro][$camp] = '';
                                        }

                                        if (isset($_GET[$camp]) && empty($pesquisa_rapida)) {
                                            $pesquisa[$tipo_cadastro][$camp] = filtra_campo($_GET[$camp]);
                                            $$camp = $pesquisa[$tipo_cadastro][$camp];
                                        }

                                        $_SESSION['pesquisa'][$tipo_cadastro][$camp] = $$camp;

                                        if (empty($camp_tabela->tabela_vinculo)) {
                                            if (empty($camp_tabela->campo_opcao)) {
                                                if ($camp_tabela->campo_tipo == 'ID') {
                                                    echo '  <input type="hidden" name="id" id="id" value="' . $id . '">';
                                                } elseif ($camp_tabela->campo_tipo == 'VARCHAR') {
                                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                                    echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '">';
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
                                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                                    echo '  <br><input type="text"  class="datepicker" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="30" maxlength="30" value="' . $tmp_data . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                                    echo '</div>';
                                                    if (!empty($$camp) && empty($pesquisa_rapida)) {
                                                        $tmp = explode('-', $$camp);
                                                        $cadastro_where .= " and $camp BETWEEN " . data_encode($tmp[0]) . " and " . data_encode($tmp[1]) . "";
                                                        $pesquisando = 'S';
                                                    }
                                                } elseif ($camp_tabela->campo_tipo == 'INT') {
                                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
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
                                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                                    echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="20" value="' . $$camp . '">';
                                                    echo '</div>';
                                                    if (!empty($$camp) && empty($pesquisa_rapida)) {
                                                        $tmp = explode('-', $$camp);
                                                        $cadastro_where .= " and $camp BETWEEN " . $tmp[0] . " and " . $tmp[1] . "";
                                                        $pesquisando = 'S';
                                                    }
                                                } elseif ($camp_tabela->campo_tipo == 'CHECKBOX') {
                                                    echo '<div class="form-varchar">';
                                                    echo '  <br><input type="checkbox" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" value="S"> ' . $camp_tabela->campo_nome;
                                                    echo '</div>';
                                                    if (!empty($$camp) && empty($pesquisa_rapida)) {
                                                        $cadastro_where .= " and $camp = '" . $$camp . "' ";
                                                        $pesquisando = 'S';
                                                    }
                                                }
                                            } else {
                                                echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
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
                                                    $cadastro_where .= " and $camp = '" . $$camp . "' ";
                                                    $pesquisando = 'S';
                                                }
                                            }
                                        } else {
                                            echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
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
                                            if (!empty($$camp) && empty($pesquisa_rapida)) {
                                                $cadastro_where .= " and $camp = '" . $$camp . "' ";
                                                $pesquisando = 'S';
                                            }
                                        }
                                        $x++;
                                    }
                                } else { // é imovel
                                    $cidade = '';
                                    $bairro = '';
                                    $localizacao = '';
                                    $condominio = '';
                                    $endereco = '';
                                    $area_terreno_de = 0;
                                    $area_terreno_ate = 0;
                                    $valor_venda_de = 0;
                                    $valor_venda_ate = 0;
                                    $area_construida_de = 0;
                                    $area_construida_ate = 0;
                                    $valor_locacao_de = 0;
                                    $valor_locacao_ate = 0;
                                    $valor_condominio_de = 0;
                                    $valor_condominio_ate = 0;
                                    $dormitorio_de = 0;
                                    $dormitorio_ate = 0;
                                    $obra = '';
                                    $suite_de = 0;
                                    $suite_ate = 0;
                                    $permuta = '';
                                    $banheiro_de = 0;
                                    $banheiro_ate = 0;
                                    $tipo_nome = '';
                                    $garagem_de = 0;
                                    $garagem_ate = 0;
                                    $proprietario = 0;
                                    $situacao = 'Ativo';
                                    $subtipo_nome = '';
                                    $edificio = '';
                                    //$quadra_de = '';
                                    //$quadra_ate = '';
                                    //$lote_de = '';
                                    //$lote_ate = '';
                                    $quadra = '';
                                    $lote = '';
                                    $metragem_de = '';
                                    $metragem_ate = '';
                                    $zoneamento = '';
                                    $topografia = '';
                                    $valor_metro_de = '';
                                    $valor_metro_ate = '';
                                    $aceita_financiamento = '';
                                    $na_internet = '';
                                    $nos_portais = '';
                                    $atualizado_recente = '';
                                    $captado_recente = '';
                                    $todos_venda = '';
                                    $todos_locacao = '';
                                    $com_foto = '';

                                    if (isset($_SESSION['cidade'])) {
                                        $cidade = $_SESSION['cidade'];
                                    }
                                    if (isset($_GET['cidade'])) {
                                        $cidade = $_GET['cidade'];
                                    }
                                    if (!empty($cidade) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and cidade = '" . $cidade . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['bairro'])) {
                                        $bairro = $_SESSION['bairro'];
                                    }
                                    if (isset($_GET['bairro'])) {
                                        $bairro = $_GET['bairro'];
                                    }
                                    if (!empty($bairro) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and bairro = '" . $bairro . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['localizacao'])) {
                                        $localizacao = $_SESSION['localizacao'];
                                    }
                                    if (isset($_GET['localizacao'])) {
                                        $localizacao = $_GET['localizacao'];
                                    }
                                    if (!empty($localizacao) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and localizacao = '" . $localizacao . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['condominio'])) {
                                        $condominio = $_SESSION['condominio'];
                                    }
                                    if (isset($_GET['condominio'])) {
                                        $condominio = $_GET['condominio'];
                                    }
                                    if (!empty($condominio) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and condominio = '" . $condominio . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['endereco'])) {
                                        $endereco = $_SESSION['endereco'];
                                    }
                                    if (isset($_GET['endereco'])) {
                                        $endereco = $_GET['endereco'];
                                    }
                                    if (!empty($endereco) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and logradouro = '" . $endereco . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['area_terreno_de'])) {
                                        $area_terreno_de = $_SESSION['area_terreno_de'];
                                    }
                                    if (isset($_GET['area_terreno_de'])) {
                                        $area_terreno_de = $_GET['area_terreno_de'];
                                    }
                                    if (isset($_SESSION['area_terreno_de'])) {
                                        $area_terreno_ate = $_SESSION['area_terreno_de'];
                                    }
                                    if (isset($_GET['area_terreno_ate'])) {
                                        $area_terreno_ate = $_GET['area_terreno_ate'];
                                    }
                                    if (($area_terreno_de > 0 || $area_terreno_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and area_terreno between $area_terreno_de and $area_terreno_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['valor_venda_de'])) {
                                        $valor_venda_de = $_SESSION['valor_venda_de'];
                                    }
                                    if (isset($_GET['valor_venda_de'])) {
                                        $valor_venda_de = $_GET['valor_venda_de'];
                                    }
                                    if (isset($_SESSION['valor_venda_ate'])) {
                                        $valor_venda_ate = $_SESSION['valor_venda_ate'];
                                    }
                                    if (isset($_GET['valor_venda_ate'])) {
                                        $valor_venda_ate = $_GET['valor_venda_ate'];
                                    }
                                    if (($valor_venda_de > 0 || $valor_venda_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and valor_venda between $valor_venda_de and $valor_venda_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['area_construida_de'])) {
                                        $area_construida_de = $_SESSION['area_construida_de'];
                                    }
                                    if (isset($_GET['area_construida_de'])) {
                                        $area_construida_de = $_GET['area_construida_de'];
                                    }
                                    if (isset($_SESSION['area_construida_ate'])) {
                                        $area_construida_ate = $_SESSION['area_construida_ate'];
                                    }
                                    if (isset($_GET['area_construida_ate'])) {
                                        $area_construida_ate = $_GET['area_construida_ate'];
                                    }
                                    if (($area_construida_de > 0 || $area_construida_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and area_construida between $area_construida_de and $area_construida_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['valor_locacao_de'])) {
                                        $valor_locacao_de = $_SESSION['valor_locacao_de'];
                                    }
                                    if (isset($_GET['valor_locacao_de'])) {
                                        $valor_locacao_de = $_GET['valor_locacao_de'];
                                    }
                                    if (isset($_SESSION['valor_locacao_ate'])) {
                                        $valor_locacao_ate = $_SESSION['valor_locacao_ate'];
                                    }
                                    if (isset($_GET['valor_locacao_ate'])) {
                                        $valor_locacao_ate = $_GET['valor_locacao_ate'];
                                    }
                                    if (($valor_locacao_de > 0 || $valor_locacao_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and valor_locacao between $valor_locacao_de and $valor_locacao_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['valor_condominio_de'])) {
                                        $valor_condominio_de = $_SESSION['valor_condominio_de'];
                                    }
                                    if (isset($_GET['valor_condominio_de'])) {
                                        $valor_condominio_de = $_GET['valor_condominio_de'];
                                    }
                                    if (isset($_SESSION['valor_condominio_ate'])) {
                                        $valor_condominio_ate = $_SESSION['valor_condominio_ate'];
                                    }
                                    if (isset($_GET['valor_condominio_ate'])) {
                                        $valor_condominio_ate = $_GET['valor_condominio_ate'];
                                    }
                                    if (($valor_condominio_de > 0 || $valor_condominio_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and valor_condominio between $valor_condominio_de and $valor_condominio_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['dormitorio_de'])) {
                                        $dormitorio_de = $_SESSION['dormitorio_de'];
                                    }
                                    if (isset($_GET['dormitorio_de'])) {
                                        $dormitorio_de = $_GET['dormitorio_de'];
                                    }
                                    if (isset($_SESSION['dormitorio_ate'])) {
                                        $dormitorio_ate = $_SESSION['dormitorio_ate'];
                                    }
                                    if (isset($_GET['dormitorio_ate'])) {
                                        $dormitorio_ate = $_GET['dormitorio_ate'];
                                    }
                                    if (($dormitorio_de > 0 || $dormitorio_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and dormitorio between $dormitorio_de and $dormitorio_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['obra'])) {
                                        $obra = $_SESSION['obra'];
                                    }
                                    if (isset($_GET['obra'])) {
                                        $obra = $_GET['obra'];
                                    }
                                    if (isset($_SESSION['situacao'])) {
                                        $situacao = $_SESSION['situacao'];
                                    }
                                    if (isset($_GET['situacao'])) {
                                        $situacao = $_GET['situacao'];
                                    }
                                    if (isset($_SESSION['suite_de'])) {
                                        $suite_de = $_SESSION['suite_de'];
                                    }
                                    if (isset($_GET['suite_de'])) {
                                        $suite_de = $_GET['suite_de'];
                                    }
                                    if (isset($_SESSION['suite_ate'])) {
                                        $suite_ate = $_SESSION['suite_ate'];
                                    }
                                    if (isset($_GET['suite_ate'])) {
                                        $suite_ate = $_GET['suite_ate'];
                                    }
                                    if (($suite_de > 0 || $suite_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and suite between $suite_de and $suite_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['permuta'])) {
                                        $permuta = $_SESSION['permuta'];
                                    }
                                    if (isset($_GET['permuta'])) {
                                        $permuta = $_GET['permuta'];
                                    }
                                    if (!empty($permuta) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and permuta = '" . $permuta . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['banheiro_de'])) {
                                        $banheiro_de = $_SESSION['banheiro_de'];
                                    }
                                    if (isset($_GET['banheiro_de'])) {
                                        $banheiro_de = $_GET['banheiro_de'];
                                    }
                                    if (isset($_SESSION['banheiro_ate'])) {
                                        $banheiro_ate = $_SESSION['banheiro_ate'];
                                    }
                                    if (isset($_GET['banheiro_ate'])) {
                                        $banheiro_ate = $_GET['banheiro_ate'];
                                    }
                                    if (($banheiro_de > 0 || $banheiro_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and banheiro between $banheiro_de and $banheiro_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['tipo_nome'])) {
                                        $tipo_nome = $_SESSION['tipo_nome'];
                                    }
                                    if (isset($_GET['tipo_nome'])) {
                                        $tipo_nome = $_GET['tipo_nome'];
                                    }
                                    if (!empty($tipo_nome) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and tipo_nome = '" . $tipo_nome . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['garagem_de'])) {
                                        $garagem_de = $_SESSION['garagem_de'];
                                    }
                                    if (isset($_GET['garagem_de'])) {
                                        $garagem_de = $_GET['garagem_de'];
                                    }
                                    if (isset($_SESSION['garagem_ate'])) {
                                        $garagem_ate = $_SESSION['garagem_ate'];
                                    }
                                    if (isset($_GET['garagem_ate'])) {
                                        $garagem_ate = $_GET['garagem_ate'];
                                    }
                                    if (($garagem_de > 0 || $garagem_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and garagem between $garagem_de and $garagem_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['proprietario'])) {
                                        $proprietario = $_SESSION['proprietario'];
                                    }
                                    if (isset($_GET['proprietario'])) {
                                        $proprietario = $_GET['proprietario'];
                                    }
                                    if ($proprietario > 0 && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and proprietario = $proprietario ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['subtipo_nome'])) {
                                        $subtipo_nome = $_SESSION['subtipo_nome'];
                                    }
                                    if (isset($_GET['subtipo_nome'])) {
                                        $subtipo_nome = $_GET['subtipo_nome'];
                                    }
                                    if (!empty($subtipo_nome) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and subtipo_nome = '" . $subtipo_nome . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['edificio'])) {
                                        $edificio = $_SESSION['edificio'];
                                    }
                                    if (isset($_GET['edificio'])) {
                                        $edificio = $_GET['edificio'];
                                    }
                                    if (!empty($edificio) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and edificio = '" . $edificio . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['zoneamento'])) {
                                        $zoneamento = $_SESSION['zoneamento'];
                                    }
                                    if (isset($_GET['zoneamento'])) {
                                        $zoneamento = $_GET['zoneamento'];
                                    }
                                    if (!empty($zoneamento) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and zoneamento = '" . $zoneamento . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['topografia'])) {
                                        $topografia = $_SESSION['topografia'];
                                    }
                                    if (isset($_GET['topografia'])) {
                                        $topografia = $_GET['topografia'];
                                    }
                                    if (!empty($topografia) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and topografia = '" . $topografia . "' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['quadra'])) {
                                        $quadra = $_SESSION['quadra'];
                                    }

                                    if (isset($_GET['quadra'])) {
                                        $quadra = $_GET['quadra'];
                                    }

                                    // if (isset($_SESSION['quadra_de'])) {
                                    // $quadra_de = $_SESSION['quadra_de'];
                                    // }
                                    // if (isset($_GET['quadra_de'])) {
                                    // $quadra_de = $_GET['quadra_de'];
                                    // }
                                    // if (isset($_SESSION['quadra_ate'])) {
                                    // $quadra_ate = $_SESSION['quadra_ate'];
                                    // }
                                    // if (isset($_GET['quadra_ate'])) {
                                    // $quadra_ate = $_GET['quadra_ate'];
                                    // }
                                    // if (($quadra_de > 0 || $quadra_ate > 0) && empty($pesquisa_rapida)) {
                                    // $cadastro_where .= " and quadra between $quadra_de and $quadra_ate ";
                                    // $pesquisando = 'S';
                                    // }
                                    if (!empty($quadra) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and quadra LIKE '$quadra' ";
                                        $pesquisando = 'S';
                                    }
                                    // if (isset($_SESSION['lote_de'])) {
                                    // $lote_de = $_SESSION['lote_de'];
                                    // }
                                    // if (isset($_GET['lote_de'])) {
                                    // $lote_de = $_GET['lote_de'];
                                    // }
                                    // if (isset($_SESSION['lote_ate'])) {
                                    // $lote_ate = $_SESSION['lote_ate'];
                                    // }
                                    // if (isset($_GET['lote_ate'])) {
                                    // $lote_ate = $_GET['lote_ate'];
                                    // }
                                    // if (($lote_de > 0 || $lote_ate > 0) && empty($pesquisa_rapida)) {
                                    // $cadastro_where .= " and lote between $lote_de and $lote_ate ";
                                    // $pesquisando = 'S';
                                    // }
                                    if (isset($_SESSION['lote'])) {
                                        $lote = $_SESSION['lote'];
                                    }
                                    if (isset($_GET['lote'])) {
                                        $lote = $_GET['lote'];
                                    }
                                    if (!empty($lote) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and lote LIKE '$lote' ";
                                        $pesquisando = 'S';
                                    }

                                    if (isset($_SESSION['metragem_de'])) {
                                        $metragem_de = $_SESSION['metragem_de'];
                                    }
                                    if (isset($_GET['metragem_de'])) {
                                        $metragem_de = $_GET['metragem_de'];
                                    }
                                    if (isset($_SESSION['metragem_ate'])) {
                                        $metragem_ate = $_SESSION['metragem_ate'];
                                    }
                                    if (isset($_GET['metragem_ate'])) {
                                        $metragem_ate = $_GET['metragem_ate'];
                                    }
                                    if (($metragem_de > 0 || $metragem_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and metragem between $metragem_de and $metragem_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['valor_metro_de'])) {
                                        $valor_metro_de = $_SESSION['valor_metro_de'];
                                    }
                                    if (isset($_GET['valor_metro_de'])) {
                                        $valor_metro_de = $_GET['valor_metro_de'];
                                    }
                                    if (isset($_SESSION['valor_metro_ate'])) {
                                        $valor_metro_ate = $_SESSION['valor_metro_ate'];
                                    }
                                    if (isset($_GET['valor_metro_ate'])) {
                                        $valor_metro_ate = $_GET['valor_metro_ate'];
                                    }
                                    if (($valor_metro_de > 0 || $valor_metro_ate > 0) && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and valor_metro between $valor_metro_de and $valor_metro_ate ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['aceita_financiamento'])) {
                                        $aceita_financiamento = $_SESSION['aceita_financiamento'];
                                    }
                                    if (isset($_GET['aceita_financiamento'])) {
                                        $aceita_financiamento = $_GET['aceita_financiamento'];
                                    }
                                    if ($aceita_financiamento == 'Sim' && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and id IN (SELECT ref FROM imovel_caracteristica WHERE ref=imovel.id and caracteristica=129) ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['na_internet'])) {
                                        $na_internet = $_SESSION['na_internet'];
                                    }
                                    if (isset($_GET['na_internet'])) {
                                        $na_internet = $_GET['na_internet'];
                                    }
                                    if ($na_internet == 'Sim' && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and internet='Sim' ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['nos_portais'])) {
                                        $nos_portais = $_SESSION['nos_portais'];
                                    }
                                    if (isset($_GET['nos_portais'])) {
                                        $nos_portais = $_GET['nos_portais'];
                                    }
                                    if ($nos_portais == 'Sim' && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and id IN (SELECT ref FROM publicar WHERE ref=imovel.id) ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['atualizado_recente'])) {
                                        $atualizado_recente = $_SESSION['atualizado_recente'];
                                    }
                                    if (isset($_GET['atualizado_recente'])) {
                                        $atualizado_recente = $_GET['atualizado_recente'];
                                    }
                                    if ($atualizado_recente == 'Sim' && empty($pesquisa_rapida)) {
                                        // data30
                                        $cadastro_where .= " and (data_atualizacao >= $data_30 and data_atualizacao != '') ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['captado_recente'])) {
                                        $captado_recente = $_SESSION['captado_recente'];
                                    }
                                    if (isset($_GET['captado_recente'])) {
                                        $captado_recente = $_GET['captado_recente'];
                                    }
                                    if ($captado_recente == 'Sim' && empty($pesquisa_rapida)) {
                                        // data30
                                        $cadastro_where .= " and ((data_captacao_venda >= $data_30 and data_captacao_venda != '') OR (data_captacao_locacao >= $data_30 and data_captacao_locacao != '')) ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['todos_venda'])) {
                                        $todos_venda = $_SESSION['todos_venda'];
                                    }
                                    if (isset($_GET['todos_venda'])) {
                                        $todos_venda = $_GET['todos_venda'];
                                    }
                                    if ($todos_venda == 'Sim' && empty($pesquisa_rapida)) {
                                        // data30
                                        $cadastro_where .= " and valor_venda > 0 ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['todos_locacao'])) {
                                        $todos_locacao = $_SESSION['todos_locacao'];
                                    }
                                    if (isset($_GET['todos_locacao'])) {
                                        $todos_locacao = $_GET['todos_locacao'];
                                    }
                                    if ($todos_locacao == 'Sim' && empty($pesquisa_rapida)) {
                                        // data30
                                        $cadastro_where .= " and valor_locacao > 0 ";
                                        $pesquisando = 'S';
                                    }
                                    if (isset($_SESSION['com_foto'])) {
                                        $com_foto = $_SESSION['com_foto'];
                                    }
                                    if (isset($_GET['com_foto'])) {
                                        $com_foto = $_GET['com_foto'];
                                    }
                                    if ($com_foto == 'Sim' && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and (foto != '' and foto NOT LIKE '%sem_foto%')  ";
                                        $pesquisando = 'S';
                                    } elseif ($com_foto == 'Não' && empty($pesquisa_rapida)) {
                                        $cadastro_where .= " and (foto = '' or foto LIKE '%sem_foto%')  ";
                                        $pesquisando = 'S';
                                    }
                                    if (empty($area_terreno_de)) {
                                        $area_terreno_de = 0;
                                    }
                                    if (empty($area_terreno_ate)) {
                                        $area_terreno_ate = 0;
                                    }
                                    if (empty($valor_venda_de)) {
                                        $valor_venda_de = 0;
                                    }
                                    if (empty($valor_venda_ate)) {
                                        $valor_venda_ate = 0;
                                    }
                                    if (empty($area_construida_de)) {
                                        $area_construida_de = 0;
                                    }
                                    if (empty($area_construida_ate)) {
                                        $area_construida_ate = 0;
                                    }
                                    if (empty($valor_locacao_de)) {
                                        $valor_locacao_de = 0;
                                    }
                                    if (empty($valor_locacao_ate)) {
                                        $valor_locacao_ate = 0;
                                    }
                                    if (empty($valor_condominio_ate)) {
                                        $valor_condominio_ate = 0;
                                    }
                                    if (empty($dormitorio_de)) {
                                        $dormitorio_de = 0;
                                    }
                                    if (empty($dormitorio_ate)) {
                                        $dormitorio_ate = 0;
                                    }
                                    if (empty($suite_de)) {
                                        $suite_de = 0;
                                    }
                                    if (empty($suite_ate)) {
                                        $suite_ate = 0;
                                    }
                                    if (empty($banheiro_de)) {
                                        $banheiro_de = 0;
                                    }
                                    if (empty($banheiro_ate)) {
                                        $banheiro_ate = 0;
                                    }
                                    if (empty($garagem_ate)) {
                                        $garagem_ate = 0;
                                    }
                                    if (empty($garagem_de)) {
                                        $garagem_de = 0;
                                    }
                                    if (empty($proprietario)) {
                                        $proprietario = 0;
                                    }
                                    // if (empty($quadra_ate)) {
                                    // $quadra_ate = 0;
                                    // }
                                    // if (empty($quadra_de)) {
                                    // $quadra_de = 0;
                                    // }
                                    // if (empty($lote_ate)) {
                                    // $lote_ate = 0;
                                    // }
                                    // if (empty($lote_de)) {
                                    // $lote_de = 0;
                                    // }
                                    if (empty($metragem_ate)) {
                                        $metragem_ate = 0;
                                    }
                                    if (empty($metragem_de)) {
                                        $metragem_de = 0;
                                    }
                                    if (empty($valor_metro_ate)) {
                                        $valor_metro_ate = 0;
                                    }
                                    if (empty($valor_metro_de)) {
                                        $valor_metro_de = 0;
                                    }
                                    //
                                    $_SESSION['pesquisa_rapida'] = $pesquisa_rapida;
                                    $_SESSION['referencia_rapida'] = $referencia_rapida;
                                    $_SESSION['tipo_pesquisa_rapida'] = $tipo_pesquisa_rapida;
                                    //
                                    $_SESSION['cidade'] = $cidade;
                                    $_SESSION['bairro'] = $bairro;
                                    $_SESSION['localizacao'] = $localizacao;
                                    $_SESSION['condominio'] = $condominio;
                                    $_SESSION['endereco'] = $endereco;
                                    $_SESSION['area_terreno_de'] = $area_terreno_de;
                                    $_SESSION['area_terreno_ate'] = $area_terreno_ate;
                                    $_SESSION['valor_venda_de'] = $valor_venda_de;
                                    $_SESSION['valor_venda_ate'] = $valor_venda_ate;
                                    $_SESSION['area_construida_de'] = $area_construida_de;
                                    $_SESSION['area_construida_ate'] = $area_construida_ate;
                                    $_SESSION['valor_locacao_de'] = $valor_locacao_de;
                                    $_SESSION['valor_locacao_ate'] = $valor_locacao_ate;
                                    $_SESSION['valor_condominio_de'] = $valor_condominio_de;
                                    $_SESSION['valor_condominio_ate'] = $valor_condominio_ate;
                                    $_SESSION['dormitorio_de'] = $dormitorio_de;
                                    $_SESSION['dormitorio_ate'] = $dormitorio_ate;
                                    $_SESSION['zoneamento'] = $zoneamento;
                                    $_SESSION['topografia'] = $topografia;
                                    $_SESSION['obra'] = $obra;
                                    $_SESSION['suite_de'] = $suite_de;
                                    $_SESSION['suite_ate'] = $suite_ate;
                                    $_SESSION['permuta'] = $permuta;
                                    $_SESSION['banheiro_de'] = $banheiro_de;
                                    $_SESSION['banheiro_ate'] = $banheiro_ate;
                                    $_SESSION['tipo_nome'] = $tipo_nome;
                                    $_SESSION['garagem_de'] = $garagem_de;
                                    $_SESSION['garagem_ate'] = $garagem_ate;
                                    $_SESSION['proprietario'] = $proprietario;
                                    $_SESSION['situacao'] = $situacao;
                                    $_SESSION['subtipo_nome'] = $subtipo_nome;
                                    $_SESSION['edificio'] = $edificio;
                                    //$_SESSION['quadra_de'] = $quadra_de;
                                    //$_SESSION['quadra_ate'] = $quadra_ate;
                                    $_SESSION['quadra'] = $quadra;
                                    // $_SESSION['lote_de'] = $lote_de;
                                    // $_SESSION['lote_ate'] = $lote_ate;
                                    $_SESSION['lote'] = $lote;
                                    $_SESSION['metragem_de'] = $metragem_de;
                                    $_SESSION['metragem_ate'] = $metragem_ate;
                                    $_SESSION['valor_metro_de'] = $valor_metro_de;
                                    $_SESSION['valor_metro_ate'] = $valor_metro_ate;
                                    $_SESSION['aceita_financiamento'] = $aceita_financiamento;
                                    $_SESSION['na_internet'] = $na_internet;
                                    $_SESSION['nos_portais'] = $nos_portais;
                                    $_SESSION['atualizado_recente'] = $atualizado_recente;
                                    $_SESSION['captado_recente'] = $captado_recente;
                                    $_SESSION['todos_venda'] = $todos_venda;
                                    $_SESSION['todos_locacao'] = $todos_locacao;
                                    $_SESSION['com_foto'] = $com_foto;
                                    //
                                    if (!empty($situacao)) {
                                        echo '<script>document.getElementById("titulo-home").innerHTML=" Exibindo imóveis ' . $situacao . 's";</script>';
                                    }

                                    //
                                    echo '<div id="carregando2"><img src="img/ajax-loader2.gif" width="40"></div>';
                                    //
                                    echo '<div style="width: 1200px;height: 70px;">';
                                    echo '<div class="form-varchar">Tipo do imóvel';
                                    echo '  <br><select name="tipo_nome" id="tipo_nome" style="width: 130px;" onchange="pesquisa_tiponome(1);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(tipo_nome) FROM imovel ORDER BY tipo_nome");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Sub-Tipo';
                                    echo '  <br><select name="subtipo_nome" id="subtipo_nome" style="width: 100px;" onchange="pesquisa_tiponome(2);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(subtipo_nome) FROM imovel ORDER BY subtipo_nome");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Cidade';
                                    echo '  <br><select name="cidade" id="cidade" style="width: 120px;" onchange="pesquisa_tiponome(3);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(cidade) FROM imovel ORDER BY cidade");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Bairro';
                                    echo '  <br><select name="bairro" id="bairro" style="width: 140px;" onchange="pesquisa_tiponome(4);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(bairro) FROM imovel ORDER BY bairro");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Localização';
                                    echo '  <br><select name="localizacao" id="localizacao" style="width: 140px;" onchange="pesquisa_tiponome(5);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(localizacao) FROM imovel ORDER BY localizacao");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Condominio';
                                    echo '  <br><select name="condominio" id="condominio" style="width: 140px;" onchange="pesquisa_tiponome(6);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(condominio) FROM imovel ORDER BY condominio");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '<div class="form-varchar">Edificio';
                                    echo '  <br><select name="edificio" id="edificio" style="width: 140px;" onchange="pesquisa_tiponome(7);">';
                                    echo '      <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(edificio) FROM imovel ORDER BY edificio");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '  </select>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div style="width: 1200px;height: auto;margin-top: -1px;">';
                                    echo '  <table width="1150px" align="center" style="margin-top: 10px;margin-bottom: 20px;">';
                                    echo '      <tr>';
                                    echo '          <td>Endereço</td>';
                                    echo '          <td>';
                                    echo '              <select name="endereco" id="endereco" style="width: 270px;" onchange="pesquisa_tiponome(8);">';
                                    echo '                  <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(logradouro) FROM imovel ORDER BY logradouro");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '          <td>Área Terreno</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="area_terreno_de" id="area_terreno_de" value="' . $area_terreno_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="area_terreno_ate" id="area_terreno_ate" value="' . $area_terreno_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Quadra';
                                    echo '          </td>';
                                    echo '          <td>';
                                    // echo '              <input type="text" name="quadra_de" id="quadra_de" value="' . $quadra_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    // echo '              &nbsp;a&nbsp;';
                                    // echo '              <input type="text" name="quadra_ate" id="quadra_ate" value="' . $quadra_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              <input type="text" name="quadra" id="quadra" value="' . $quadra . '" size="20" style="width: 270px;">';
                                    echo '          </td>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Valor Venda</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="valor_venda_de" id="valor_venda_de" value="' . $valor_venda_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="valor_venda_ate" id="valor_venda_ate" value="' . $valor_venda_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Área Construída</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="area_construida_de" id="area_construida_de" value="' . $area_construida_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="area_construida_ate" id="area_construida_ate" value="' . $area_construida_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Lote';
                                    echo '          </td>';
                                    echo '          <td>';
                                    // echo '              <input type="text" name="lote_de" id="lote_de" value="' . $lote_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    // echo '              &nbsp;a&nbsp;';
                                    // echo '              <input type="text" name="lote_ate" id="lote_ate" value="' . $lote_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              <input type="text" name="lote" id="lote" value="' . $lote . '" size="20" style="width: 270px;">';
                                    echo '          </td>';
                                    echo '      </tr>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Valor Locação</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="valor_locacao_de" id="valor_locacao_de" value="' . $valor_locacao_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="valor_locacao_ate" id="valor_locacao_ate" value="' . $valor_locacao_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Valor Condomínio</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="valor_condominio_de" id="valor_condominio_de" value="' . $valor_condominio_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="valor_condominio_ate" id="valor_condominio_ate" value="' . $valor_condominio_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Metragem';
                                    echo '          </td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="metragem_de" id="metragem_de" value="' . $metragem_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="metragem_ate" id="metragem_ate" value="' . $metragem_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '      </tr>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Dormitórios</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="dormitorio_de" id="dormitorio_de" value="' . $dormitorio_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="dormitorio_ate" id="dormitorio_ate" value="' . $dormitorio_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Situação da Obra</td>';
                                    echo '          <td>';
                                    echo '              <select name="obra" id="obra" style="width: 270px;">';
                                    echo '                  <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(obra) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '  <script>document.getElementById("obra").value="' . $obra . '";</script>';
                                    echo '          <td>Zoneamento';
                                    echo '          </td>';
                                    echo '          <td>';
                                    echo '              <select name="zoneamento" id="zoneamento" style="width: 270px;">';
                                    echo '                  <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(zoneamento) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '  <script>document.getElementById("zoneamento").value="' . $zoneamento . '";</script>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Suítes</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="suite_de" id="suite_de" value="' . $suite_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="suite_ate" id="suite_ate" value="' . $suite_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Permuta</td>';
                                    echo '          <td>';
                                    echo '              <select name="permuta" id="permuta" style="width: 270px;">';
                                    echo '                  <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(permuta) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '  <script>document.getElementById("permuta").value="' . $permuta . '";</script>';
                                    echo '          <td>Topografia';
                                    echo '          </td>';
                                    echo '          <td>';
                                    echo '              <select name="topografia" id="topografia" style="width: 270px;">';
                                    echo '                  <option></option>';
                                    include '../model/conexao.php';
                                    $conexao = new conexao();
                                    $ret = $conexao->db->query("SELECT DISTINCT(topografia) FROM imovel");
                                    while ($row = $ret->fetch()) {
                                        echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                    }
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '  <script>document.getElementById("topografia").value="' . $topografia . '";</script>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Banheiros</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="banheiro_de" id="banheiro_de" value="' . $banheiro_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="banheiro_ate" id="banheiro_ate" value="' . $banheiro_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>Situação</td>';
                                    echo '          <td>';
                                    echo '              <select name="situacao" id="situacao" style="width: 270px;">';
                                    echo '                  <option></option>';
                                    echo '                  <option>Pendente</option>';
                                    echo '                  <option>Ativo</option>';
                                    echo '                  <option>Suspenso</option>';
                                    echo '                  <option>Vendido</option>';
                                    echo '                  <option>Vendido (Imobiliaria)</option>';
                                    echo '                  <option>Locado</option>';
                                    echo '                  <option>Administrado</option>';
                                    echo '              </select>';
                                    echo '          </td>';
                                    echo '  <script>document.getElementById("situacao").value="' . $situacao . '";</script>';
                                    echo '          <td>Garagens';
                                    echo '          </td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="garagem_de" id="garagem_de" value="' . $garagem_de . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '              &nbsp;a&nbsp;';
                                    echo '              <input type="text" name="garagem_ate" id="garagem_ate" value="' . $garagem_ate . '" size="20" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '      </tr>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td>Proprietário</td>';
                                    echo '          <td>';
                                    echo '              <input type="text" name="proprietario" id="proprietario" value="' . $proprietario . '" size="8" style="width: 120px;" onkeypress="return SomenteNumero(event);">';
                                    echo '          </td>';
                                    echo '          <td>&nbsp;</td>';
                                    echo '          <td>&nbsp; </td>';
                                    echo '          <td>&nbsp;</td>';
                                    echo '          <td>&nbsp;</td>';
                                    echo '      </tr>';
                                    echo '      </tr>';
                                    echo '      <tr>';
                                    echo '          <td colspan="6">';
                                    
                                    if ($aceita_financiamento == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo 'Financiamento <select name="aceita_financiamento" id="aceita_financiamento" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($na_internet == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Internet <select name="na_internet" id="na_internet" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($nos_portais == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Portais <select name="nos_portais" id="nos_portais" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($atualizado_recente == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Atualizado <select name="atualizado_recente" id="atualizado_recente" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($captado_recente == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Cadastro Recente <select name="captado_recente" id="captado_recente" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($todos_venda == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Todos Venda <select name="todos_venda" id="todos_venda" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    if ($todos_locacao == 'Sim') {
                                        $sel1 = 'selected';
                                        $sel2 = '';
                                    } else {
                                        $sel1 = '';
                                        $sel2 = 'selected';
                                    }
                                    echo '&nbsp; | &nbsp;Todos Locação <select name="todos_locacao" id="todos_locacao" >';
                                    echo '<option '.$sel1.'>Sim</option>';
                                    echo '<option '.$sel2.'>Não</option>';
                                    echo '</select>';
                                    
                                    echo '          </td>';
                                    echo '      </tr>';
                                    //
                                    //
                                    echo '      <tr>';
                                    echo '          <td colspan="6">';
                                    //
                                    $chk_com_foto1 = $chk_com_foto2 = $chk_com_foto3 = '';
                                    if ($com_foto == 'Sim') {
                                        $chk_com_foto1 = 'checked';
                                    } elseif ($com_foto == 'Não') {
                                        $chk_com_foto2 = 'checked';
                                    } else {
                                        $chk_com_foto3 = 'checked';
                                    }
                                    echo '              Opção Foto : <input type="radio" name="com_foto" value="Sim" ' . $chk_com_foto1 . '> Com Foto ';
                                    echo '              &nbsp;&nbsp;<input type="radio" name="com_foto" value="Não" ' . $chk_com_foto2 . '> Sem Foto ';
                                    echo '              &nbsp;&nbsp;<input type="radio" name="com_foto" value="" ' . $chk_com_foto3 . '> Indiferente ';
                                    //
                                    echo '          </td>';
                                    echo '      </tr>';
                                    //
                                    echo '  </table>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <p alig="right" style="margin-left: px;">
                                <input type="button" value="Fechar" class="botao" onclick="pesq_rapida();">
                                <input type="submit" value="Buscar" class="botao" style="margin-right: 30px;">
                                <?php
                                if ($tipo_cadastro == 'imovel') {
                                    echo '<input type="button" value="Limpar Todos" class="botao" style="margin-right: 30px;" onclick="limpa_pesquisa();">';
                                }
                                ?>
                                <!-- <input type="button" value="Salvar Excel" class="botao" onclick="window.open('salva_excel.php?tipo_cadastro=<?php echo $tipo_cadastro; ?>', '_blank');" style="background: #008000;"> -->
                            </p>
                        </div>
                        <?php
                    }

                    echo '<script>';
                    echo 'function carrega_valores(nivel) {';
                    echo 'if (nivel == 1) {document.getElementById("subtipo_nome").value="' . $subtipo_nome . '";}';
                    echo 'if (nivel == 2) {document.getElementById("cidade").value="' . $cidade . '";}';
                    echo 'if (nivel == 3) {document.getElementById("bairro").value="' . $bairro . '";}';
                    echo 'if (nivel == 4) {document.getElementById("localizacao").value="' . $localizacao . '";}';
                    echo 'if (nivel == 5) {document.getElementById("condominio").value="' . $condominio . '";}';
                    echo 'if (nivel == 6) {document.getElementById("edificio").value="' . $edificio . '";}';
                    echo 'if (nivel == 7) {document.getElementById("endereco").value="' . $endereco . '";}';
                    echo '}';
                    echo '</script>';

                    if ($pesquisando == 'S' && empty($pesquisa_rapida) && empty($referencia_rapida)) {

                        echo '<script>cadastro_pesquisar();</script>';

                        echo '<script>document.getElementById("tipo_nome").value="' . $tipo_nome . '";</script>';
                        echo '<script>pesquisa_tiponome(1);</script>';
                    }
                    ?>
                </form>
                <div id="mostrar"></div>
                <div id="listagem">
                    <table width="100%">
                        <tr class="listagem-titulo">
                            <?php
                            $lista = json_decode(tabela_lista($tipo_cadastro));
                            $tot_colunas = count($lista);
                            foreach ($lista as $camp) {
                                if (!empty($camp->campo_nome)) {
                                    echo '<td bgcolor="' . $cor . '" width="' . $camp->campo_tamanho . '" onclick="window.open(\'cadastros.php?ordem=' . $camp->campo . '\',\'_self\');" style="cursor:pointer;">' . $camp->campo_nome;
                                    ;
                                    if (strpos($cadastro_order, $camp->campo)) {
                                        if ($asc_desc == 'ASC') {
                                            echo ' ▲ ';
                                        } else {
                                            echo ' ▼ ';
                                        }
                                    }
                                    echo '</td>';
                                }
                            }
                            ?>
                        </tr>
                        <?php
//                        if ($pesquisando != 'S') {
//                            $cadastro_where = '';
//                            $cadastro_order = '';
//                            $pesquisa_rapida = '';
//                        }
                        if ($_SESSION['cliente_id'] == 2) {
                            echo '<p>TPR:' . $referencia_rapida . $tipo_pesquisa_rapida . $pesquisa_rapida . '</p>';
                        }

//                        $corretor_restrito = 'Não';
//                        $corretor_id = usuario_corretor($_SESSION['usuario_id']);
//                        $corretor_nome = '';
//                        if ($corretor_id) {
//                            $corretor_nome = usuario_corretor_nome($_SESSION['usuario_id']);
//                            $corretor_restrito = usuario_corretor_restrito($_SESSION['usuario_id']);
//                            if ($corretor_restrito == 'Apenas seus Clientes') {
//                                echo "<h3>◊ $corretor_nome - Corretor Restrito ◊</h3>";
//                            } else {
//                                echo "<h3>≈ $corretor_nome - Corretor Irrestrito ≈</h3>";
//                            }
//                        }
                        if (!empty($pesquisa_rapida)) {
                            if ($tipo_cadastro == 'imovel' and $tipo_pesquisa_rapida == '1') {
                                $cadastro_where = " AND (id = '$pesquisa' or "
                                        . "bairro LIKE '$pesquisa_rapida' or "
                                        . "cidade LIKE '$pesquisa_rapida' or "
                                        . "logradouro LIKE '$pesquisa_rapida' or "
                                        . "descricao LIKE '$pesquisa_rapida' or "
                                        . "condominio LIKE '$pesquisa_rapida' or "
                                        . "tipo_nome LIKE '$pesquisa_rapida' or "
                                        . "subtipo_nome LIKE '$pesquisa_rapida' or "
                                        . "localizacao LIKE '$pesquisa_rapida' or "
                                        . "cep LIKE '$pesquisa_rapida' or "
                                        . "condicoes_pagamento LIKE '$pesquisa_rapida' or "
                                        . "observacao LIKE '$pesquisa_rapida') ";
                            } elseif ($tipo_cadastro == 'imovel' and $tipo_pesquisa_rapida == '2') {
                                $cadastro_where = " AND (id = '$pesquisa' or "
                                        . "bairro LIKE '%$pesquisa_rapida%' or "
                                        . "cidade LIKE '%$pesquisa_rapida%' or "
                                        . "logradouro LIKE '%$pesquisa_rapida%' or "
                                        . "descricao LIKE '%$pesquisa_rapida%' or "
                                        . "condominio LIKE '%$pesquisa_rapida%' or "
                                        . "tipo_nome LIKE '%$pesquisa_rapida%' or "
                                        . "subtipo_nome LIKE '%$pesquisa_rapida%' or "
                                        . "localizacao LIKE '%$pesquisa_rapida%' or "
                                        . "cep LIKE '%$pesquisa_rapida%' or "
                                        . "condicoes_pagamento LIKE '%$pesquisa_rapida%' or "
                                        . "observacao LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'imovel' and $tipo_pesquisa_rapida == '3') {
                                $cadastro_where = " AND (id = '$pesquisa' or "
                                        . "bairro LIKE '$pesquisa_rapida%' or "
                                        . "cidade LIKE '$pesquisa_rapida%' or "
                                        . "logradouro LIKE '$pesquisa_rapida%' or "
                                        . "descricao LIKE '$pesquisa_rapida%' or "
                                        . "condominio LIKE '$pesquisa_rapida%' or "
                                        . "tipo_nome LIKE '$pesquisa_rapida%' or "
                                        . "subtipo_nome LIKE '$pesquisa_rapida%' or "
                                        . "localizacao LIKE '$pesquisa_rapida%' or "
                                        . "cep LIKE '$pesquisa_rapida%' or "
                                        . "condicoes_pagamento LIKE '$pesquisa_rapida%' or "
                                        . "observacao LIKE '$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'proprietario') {
                                $cadastro_where = " AND (apelido LIKE '%$pesquisa_rapida%' or nome LIKE '%$pesquisa_rapida%' or cpf LIKE '%$pesquisa_rapida%' or rg LIKE '%$pesquisa_rapida%' or profissao LIKE '%$pesquisa_rapida%' or cep LIKE '%$pesquisa_rapida%' or logradouro LIKE '%$pesquisa_rapida%' or complemento LIKE '%$pesquisa_rapida%' or bairro LIKE '%$pesquisa_rapida%' or cidade LIKE '%$pesquisa_rapida%' or fone1 LIKE '%$pesquisa_rapida%' or fone2 LIKE '%$pesquisa_rapida%' or cel LIKE '%$pesquisa_rapida%' or fone_com LIKE '%$pesquisa_rapida%' or contato LIKE '%$pesquisa_rapida%' or email LIKE '%$pesquisa_rapida%' or observacao LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'comprador') {
                                $cadastro_where = " AND (apelido LIKE '%$pesquisa_rapida%' or nome LIKE '%$pesquisa_rapida%' or cpf LIKE '%$pesquisa_rapida%' or rg LIKE '%$pesquisa_rapida%' or profissao LIKE '%$pesquisa_rapida%' or cep LIKE '%$pesquisa_rapida%' or logradouro LIKE '%$pesquisa_rapida%' or complemento LIKE '%$pesquisa_rapida%' or bairro LIKE '%$pesquisa_rapida%' or cidade LIKE '%$pesquisa_rapida%' or fone1 LIKE '%$pesquisa_rapida%' or fone2 LIKE '%$pesquisa_rapida%' or cel LIKE '%$pesquisa_rapida%' or fone_com LIKE '%$pesquisa_rapida%' or contato LIKE '%$pesquisa_rapida%' or email LIKE '%$pesquisa_rapida%' or observacao LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'corretor') {
                                $cadastro_where = " AND (apelido LIKE '%$pesquisa_rapida%' or nome LIKE '%$pesquisa_rapida%' or cep LIKE '%$pesquisa_rapida%' or logradouro LIKE '%$pesquisa_rapida%' or complemento LIKE '%$pesquisa_rapida%' or bairro LIKE '%$pesquisa_rapida%' or cidade LIKE '%$pesquisa_rapida%' or fone1 LIKE '%$pesquisa_rapida%' or fone2 LIKE '%$pesquisa_rapida%' or cel LIKE '%$pesquisa_rapida%' or email LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'tabela') {
                                $cadastro_where = " AND (nome LIKE '%$pesquisa_rapida%' or campo LIKE '%$pesquisa_rapida%' or campo_nome LIKE '%$pesquisa_rapida%' or campo_tipo LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'lista') {
                                $cadastro_where = " AND (nome LIKE '%$pesquisa_rapida%' or campo LIKE '%$pesquisa_rapida%' or campo_nome LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'pesquisa') {
                                $cadastro_where = " AND (nome LIKE '%$pesquisa_rapida%' or campo LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'portal') {
                                $cadastro_where = " AND (nome LIKE '%$pesquisa_rapida%' or nome_completo LIKE '%$pesquisa_rapida%') ";
                            } elseif ($tipo_cadastro == 'portal_tag') {
                                $cadastro_where = " AND (portal LIKE '%$pesquisa_rapida%' or tag LIKE '%$pesquisa_rapida%' or campo LIKE '%$pesquisa_rapida%') ";
                            } else {
                                $cadastro_where = " AND ( id = '$pesquisa_rapida'";
                                $lista = pesquisa_lista($tipo_cadastro);
                                if ($lista) {
                                    foreach ($lista as $camp) {
                                        $cadastro_where .= " or $camp LIKE '%$pesquisa_rapida%' ";
                                    }
                                    $cadastro_where .= " ) ";
                                }
                            }
                        }

                        if (!empty($cadastro_home)) {
                            if ($cadastro_home == '1') {
                                $cadastro_where .= " and data_atualizacao > " . $cadastro_datai . " and data_atualizacao != '' ";
                            } elseif ($cadastro_home == '2') {
                                $cadastro_where .= " and data_atualizacao BETWEEN " . $cadastro_datai . " and " . $cadastro_dataf . " ";
                            } elseif ($cadastro_home == '3') {
                                $cadastro_where .= " and data_atualizacao < " . $cadastro_data . "  and data_atualizacao != '' ";
                            } elseif ($cadastro_home == '4') {
                                $cadastro_where .= " and data_atualizacao = '' ";
                            } elseif ($cadastro_home == '5') {
                                $cadastro_where .= " and situacao='Pendente' ";
                            } elseif ($cadastro_home == '6') {
                                $cadastro_where .= " and situacao='Enviada' ";
                            }
                        }

                        if (!empty($situacao) && empty($pesquisa_rapida) && $tipo_cadastro == 'imovel') {
                            $cadastro_where .= " and situacao = '" . $situacao . "' ";
                        }

                        if (!empty($referencia_rapida)) {
                            $cadastro_where = " and id = '" . $referencia_rapida . "' ";
                        }

                        //
                        // 27-06-2015
                        //
                        
                        
                        if ($tipo_cadastro == 'imovel') {
                            if ($corretor_restrito == 'Apenas Seus Imóveis' || $corretor_restrito == 'Apenas seus Clientes e Imóveis') {
                                $cadastro_where .= " AND (captado_venda_por = '$corretor_nome' OR captado_locacao_por = '$corretor_nome') ";
                            }
                        } elseif ($tipo_cadastro == 'comprador') {
                            if ($corretor_restrito == 'Apenas seus Clientes' || $corretor_restrito == 'Apenas seus Clientes e Imóveis') {
                                $cadastro_where .= " AND corretor = '$corretor_id' ";
                            }
                        }
                        


                        //
                        // fim 27-06-2015
                        //

                        if ($pesquisando == 'S') {
                            $_SESSION['pesquisa'][$tipo_cadastro]['where'] = $cadastro_where;
                        }

                        $_SESSION['pesquisa'][$tipo_cadastro]['pagina'] = $pagina;
                        $_SESSION['cadastro_home'] = $cadastro_home;
                        $_SESSION['cadastro_data'] = $cadastro_data;
                        $_SESSION['cadastro_datai'] = $cadastro_datai;
                        $_SESSION['cadastro_dataf'] = $cadastro_dataf;

                        $itenspp = 20;

                        $cadastro_rows = ' LIMIT ' . (($pagina - 1) * $itenspp) . ',' . $itenspp;

                        $ret = json_decode(cadastro_listar($tipo_cadastro, $cadastro_where, '', ''));
                        $tot = count($ret);
                        $paginas = ceil($tot / $itenspp);
                        //echo ' cadastro_where: ' . $cadastro_where;
                        $ret = json_decode(cadastro_listar($tipo_cadastro, $cadastro_where, $cadastro_order, $cadastro_rows));
                        if ($ret) {
                            foreach ($ret as $id) {
                                $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));
                                echo '<tr class="listagem-item" ';
                                if ($tipo_cadastro == 'imovel') {
                                    echo ' ondblclick="window.open(\'cadastro.php?id=' . $cad->id . '\',\'_self\');"';
                                    echo ' onclick="cadastro_mostrar(\'' . $cad->id . '\');"';
                                } elseif ($tipo_cadastro == 'cliente') {
                                    echo ' onclick="window.open(\'cliente.php?id=' . $cad->id . '\',\'_self\');"';
                                } else {
                                    echo ' onclick="window.open(\'cadastro.php?id=' . $cad->id . '\',\'_self\');"';
                                }
                                echo '>';
                                $lista = json_decode(tabela_lista($tipo_cadastro));
                                foreach ($lista as $camp) {
                                    $camp_dado = '';
                                    if (!empty($camp->campo_nome)) {
                                        $name_campo = $camp->campo;
                                        $tabela = json_decode(tabela_campo($tipo_cadastro, $camp->campo));
                                        if (isset($tabela->tabela_vinculo)) {
                                            if (!empty($tabela->tabela_vinculo) && !empty($cad->$name_campo)) {
                                                $camp_dado = tabela_carregar_campo($tabela->tabela_vinculo, $tabela->tabela_texto, $cad->$name_campo);
                                                if (empty($camp_dado)) {
                                                    $camp_dado = $cad->$name_campo;
                                                }
                                            }
                                        }
                                        if (empty($camp_dado)) {
                                            $camp_dado = $cad->$name_campo;
                                        }
                                        //
                                        $text_align = 'left';
                                        if ($tabela->campo_tipo == 'DECIMAL') {
                                            $camp_dado = number_format($camp_dado, 2, ',', '.');
                                            $text_align = 'right';
                                        } elseif ($tabela->campo_tipo == 'DATE' or strpos('.' . $camp->campo, 'data')) {
                                            $camp_dado = data_decode($camp_dado);
                                        }

                                        if ($camp->campo == 'dormitorio' || $camp->campo == 'garagem' || $camp->campo == 'piscina' || $camp->campo == 'suite' || $camp->campo == 'escritorio') {
                                            $text_align = 'center';
                                        }
                                        if ($camp->campo == 'id' || $camp->campo == 'area_terreno' || $camp->campo == 'area_construida') {
                                            $text_align = 'right';
                                        }

                                        $cor_alt = '';
                                        if ($cad->data_atualizacao >= $data_30) {
                                            $cor_alt = 'background: #e1f7f9 !important;';
                                        }

                                        echo '<td  width="' . $camp->campo_tamanho . '" style="text-align: ' . $text_align . ';' . $cor_alt . '">&nbsp;' . $camp_dado . '&nbsp;</td>';
                                    }
                                }
                                echo '</tr>';
                            }
                            echo '<tr class="listagem-titulo"><td colspan="' . $tot_colunas . '" bgcolor="' . $cor . '">Total ' . $tot . ' registro(s). ';
                            if ($paginas > 1) {
                                echo 'Página ' . $pagina . ' de ' . $paginas;
                            }
                        } else {
                            echo '<tr class="listagem-titulo"><td colspan="' . $tot_colunas . '" bgcolor="' . $cor . '">Nenhum Registro Encontrado.';
                        }
                        echo '</td></tr>';
                        ?>
                    </table>
                    <div><center>
                            <?php
                            if ($pagina < $paginas) {
                                echo '<div class="botao_pagina" onclick="window.open(\'cadastros.php?pagina=' . $paginas . '\',\'_self\');">Ultima &raquo;&rsaquo;</div>';
                                echo '<div class="botao_pagina" onclick="window.open(\'cadastros.php?pagina=' . ($pagina + 1) . '\',\'_self\');">Avançar &raquo;</div>';
                            }
                            if ($pagina > 1) {
                                echo '<div class="botao_pagina" onclick="window.open(\'cadastros.php?pagina=' . ($pagina - 1) . '\',\'_self\');">&laquo; Voltar</div>';
                                echo '<div class="botao_pagina" onclick="window.open(\'cadastros.php?pagina=1\',\'_self\');">&lsaquo;&laquo; Primeira</div>';
                            }
                            ?>
                        </center>
                    </div>
                </div>
            </div>
            <?php
        } else { // empty tipo_cadastro
            echo '<div style="width: 100%;height: 70px;"></div>';
            $tmp_usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));
            if ($_SESSION['cliente_id'] != 0000 || ($_SESSION['cliente_id'] == 0000 && $tmp_usuario->acesso > 80)) {
                echo '<div style="border: 2px solid #ccc; background: #fff;">';
                echo '<p>&nbsp;&nbsp;Total de Cadastros : </p>';
                echo '</div>';
                include "../controller/cadastro.php";
                $ret = json_decode(tipo_cadastro_listar());
                $tot = count($ret);
                foreach ($ret as $id) {
                    $tcad = json_decode(tipo_cadastro_carregar($id));
                    $tmp = $tcad->tabela . '_consultar';
                    $ret = json_decode(cadastro_listar($tcad->tabela, '', '', ''));
                    $tot = count($ret);
                    if ($usu->$tmp == 'Sim' && $tcad->tabelas != 'S') {
                        echo '    <div class="cadastro-total" onclick="window.open(\'cadastros.php?id_cadastro=' . $id . '&tabelas=Não\', \'_self\')">' . $tcad->tipo . '<br>' . $tot . '</div>';
                    }
                }
                //
            }
            echo '<div style="width: 100%;height: 130px;"></div>';
            if ($_SESSION['cliente_id'] != 0000) {
                echo '<div style="border: 2px solid #ccc; background: #fff;">';
                echo '<p>&nbsp;&nbsp;Total de Publicações : </p>';
                echo '</div>';
                //
                $ret = json_decode(cadastro_listar('imovel', " AND internet='Sim'", '', ''));
                $tot = count($ret);
                echo '    <div class="cadastro-total" onclick="window.open(\'cadastros.php?id_cadastro=1&tabelas=Não&na_internet=Sim\', \'_self\')">Na Internet<br>' . $tot . '</div>';
                //
                $ret = json_decode(cadastro_listar('imovel', " and situacao='Ativo' and id IN (SELECT ref FROM publicar WHERE ref=imovel.id) ", '', ''));
                $tot = count($ret);
                echo '    <div class="cadastro-total" onclick="window.open(\'cadastros.php?id_cadastro=1&tabelas=Não&nos_portais=Sim\', \'_self\')">Nos Portais<br>' . $tot . '</div>';
                // data30
                $cadastro_where .= " and situacao='Ativo' and (data_atualizacao >= $data_30 and data_atualizacao != '') ";
                $ret = json_decode(cadastro_listar('imovel', $cadastro_where, '', ''));
                $tot = count($ret);
                echo '    <div class="cadastro-total" onclick="window.open(\'cadastros.php?id_cadastro=1&tabelas=Não&atualizado_recente=Sim\', \'_self\')">Atualizados<br>' . $tot . '</div>';
                //
            }
        }

//
        if (empty($_SESSION['tipo_cadastro']) && $_SESSION['cliente_id'] != 0000) {
            ///
            $_SESSION['tipo_cadastro'] = 'imovel';
            $_SESSION['id_cadastro'] = '1';
            //
            echo '<div style="width: 100%;height: 130px;"></div>';
            echo '<div style="border: 2px solid #ccc; background: #fff;">';
            echo '<p>&nbsp;&nbsp;TOP 20 Imóveis mais clicados no site : </p>';
            echo '</div>';
            //" WHERE situacao='Ativo' and internet='Sim' "
            $ret = json_decode(cadastro_listar('imovel', " AND internet='Sim' AND situacao='Ativo' ", ' ORDER BY clicks DESC ', '  LIMIT 0,20'));
            $tot = count($ret);
            if ($ret) {
                echo '<table width="95%" style="border: 3px solid #ccc; margin: 10px;" style="cursor: pointer;">';
                echo '    <tr>';
                echo '        <td bgcolor="#cccccc">Ref</td>';
                echo '        <td bgcolor="#cccccc">Tipo</td>';
                echo '        <td bgcolor="#cccccc">Endereço</td>';
                echo '        <td bgcolor="#cccccc">Bairro</td>';
                echo '        <td bgcolor="#cccccc">Cidade</td>';
                echo '        <td bgcolor="#cccccc">Atualização</td>';
                echo '        <td bgcolor="#cccccc">Clicks</td>';
                echo ' </tr>';
                //
                include '../controller/cadastro.php';
                foreach ($ret as $id) {
                    $imo = json_decode(cadastro_carregar('imovel', $id));
                    echo '<tr  onclick="window.open(\'cadastro.php?id=' . $imo->id . '\',\'_self\');">';
                    echo ' <td bgcolor = "#fff">' . $imo->id . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->tipo_nome . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->logradouro . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->bairro . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->cidade . '</td>';
                    echo ' <td bgcolor = "#fff">' . data_decode($imo->data_atualizacao) . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->clicks . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '&nbsp;Nenhum imóvel clicado.';
            }
        }
        ?>
    </body>
</html>


