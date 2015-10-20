<?php
session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

include 'func.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tipo_cadastro = $_SESSION['tipo_cadastro'];
    include '../controller/cadastro.php';

    if ($tipo_cadastro != 'imovel') {

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
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>SGI Fácil : : Painél Administrativo</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width">
                <link href="css/fontes.css" rel="stylesheet" />
                <link href="css/ficha.css" rel="stylesheet" />
                <link href="css/forms.css" rel="stylesheet" />
                <link href="css/cadastro.css" rel="stylesheet" />
            </head>
            <body>
                <div class="ficha-logo"><img src="img/logo_sgi.png" width="100"></div>
                <h3><center>Ficha de <?php echo $_SESSION['nome_tipo_cadastro']; ?></center></h3>
                <hr>
                <?php
                include '../controller/tabela.php';
                $camps_tabela = json_decode(tabela_carregar($tipo_cadastro));
                $grupo = '';
                echo '<fieldset>';
                $x = 0;
                foreach ($camps_tabela as $camp_tabela) {
                    if ($x == 0 && $tipo_cadastro == 'imovel' && $id != 'add') {
                        echo '<div class="cadastro-referencia">Referência | <input type="text" value="' . str_pad($id, 8, '0', 0) . '" size="8" readonly style="text-align: center; font-weight: bold;background: #d0d9f5;"></div>';
                    }
                    if (!empty($camp_tabela->campo_grupo)) {
                        $camp = $camp_tabela->campo;
                        $func = $camp_tabela->funcao;
                        $func2 = $camp_tabela->funcao2;
                        $readonly = 'readonly';
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
                                    echo '</div>';
                                } elseif ($camp_tabela->campo_tipo == 'DATE') {
                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                    echo '  <br><input type="text"  class="datepicker" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . data_decode($$camp) . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                    echo '</div>';
                                } elseif ($camp_tabela->campo_tipo == 'INT') {
                                    echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                    echo '  <br><input type="number" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="' . $camp_tabela->campo_tamanho . '" maxlength="' . $camp_tabela->campo_max . '" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
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
                                    echo $readonly . ' ' . $required . ' ' . $func . '>';
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
                                }
                                $x++;
                            } else {
                                echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                                echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="25" value="' . $$camp . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                                echo '</div>';
                                $x++;
                            }
                        } else {
                            echo '<div class="form-varchar">' . $camp_tabela->campo_nome;
                            echo '  <br><input type="text" name="' . $camp_tabela->campo . '" id="' . $camp_tabela->campo . '" size="25"  value="' . tabela_carregar_campo($camp_tabela->tabela_vinculo, $camp_tabela->tabela_texto, $$camp) . '" ' . $readonly . ' ' . $required . ' ' . $func . '>';
                            echo '</div>';
                            $x++;
                        }
                    }
                }
                echo '</fieldset>';
                ?>
                <br><div style="clear:both; width: 100%;height: 40px;"></div>
                <script>window.print();</script>
            </body>
        </html>
        <?php
    } else {

        $prop = '';
        if (isset($_GET['prop'])) {
            $prop = $_GET['prop'];
        }

        include '../controller/caracteristica.php';
        include '../controller/imovel_caracteristica.php';
        include '../controller/ficha_config.php';
        $ficha_config = json_decode(ficha_config_carregar());

        $imovel = json_decode(cadastro_carregar('imovel', $id));
        $proprietario = json_decode(cadastro_carregar('proprietario', $imovel->proprietario));
        include 'fpdf/fpdf.php';

        setlocale(LC_CTYPE, 'pt_BR');

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->Rect(10, 10, 185, 20);
        $pdf->Rect(10, 10, 40, 20);

        if (!empty($ficha_config->logo)) {
            $logo = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $ficha_config->logo;
            if (file_exists($logo)) {
                $pdf->Image($logo, 11, 11, 38, 18);
            }
        }

        $pdf->SetXY(52, 10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto1), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(52, 15);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto2), 0, 0, 'L');
        $pdf->SetXY(52, 20);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ficha impressa por ' . $_SESSION['usuario_nome']), 0, 0, 'L');
        $pdf->SetXY(52, 25);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data : ' . date('d/m/Y H:i')), 0, 0, 'L');

        $pdf->Rect(10, 30, 185, 45);
        $pdf->Rect(10, 30, 60, 45);

        if (!empty($imovel->foto)) {
            $logo = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $imovel->foto;
            if (file_exists($logo)) {
                $pdf->Image($logo, 10, 30, 60, 45);
            }
        }
        //$pdf->Image('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $imovel->foto, 11, 31, 58, 43);

        $pdf->SetXY(72, 32);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ficha de Imóvel Ref: ' . $id . ' - ' . $imovel->tipo_nome . ' ' . $imovel->subtipo_nome . ' '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(72, 37);
        if ($imovel->valor_venda > 0) {
            $pdf->Cell(50, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor de venda R$ ' . us_br($imovel->valor_venda)), 0, 0, 'L');
            $pdf->SetXY(72, 42);
        }
        if ($imovel->valor_locacao > 0) {
            $pdf->Cell(50, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor de locação R$ ' . us_br($imovel->valor_locacao)), 0, 0, 'L');
        }

        $pdf->line(72, 48, 192, 48);

        $pdf->SetXY(72, 50);
        $pdf->SetFont('Arial', 'B', 10);

        $apto = '';
        if (!empty($imovel->numero_apartamento)) {
            $apto .= ' Apto: ' . $imovel->numero_apartamento;
        }

        if (!empty($imovel->andar)) {
            $apto .= '    ' . $imovel->andar . 'o. andar';
        }


        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->tipo_logradouro . ' ' . $imovel->logradouro . ' ' . $imovel->numero . ' - ' . $imovel->bairro), 0, 0, 'L');
        $pdf->SetXY(72, 55);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CEP ' . $imovel->cep . ' - ' . $imovel->cidade . ' - ' . $imovel->uf . '        ' . $apto), 0, 0, 'L');

        if ($prop == 'S') {
            $pdf->line(72, 62, 192, 62);

            $tels = $proprietario->fone1;
            if (!empty($proprietario->fone2)) {
                $tels.= ' | ' . $proprietario->fone2;
            }
            if (!empty($proprietario->cel)) {
                $tels.= ' | ' . $proprietario->cel;
            }

            $pdf->SetXY(72, 64);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $proprietario->nome), 0, 0, 'L');
            $pdf->SetXY(72, 69);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tels : ' . $tels), 0, 0, 'L');
        }

        $pdf->Rect(10, 78, 185, 13);

        $pdf->SetXY(35, 79);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Captado (Venda) por'), 0, 0, 'L');
        $pdf->SetXY(37, 84);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->captado_venda_por), 1, 0, 'L');

        $pdf->SetXY(80, 79);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data'), 0, 0, 'L');
        $pdf->SetXY(80, 84);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->data_captacao_venda)), 1, 0, 'L');

        //

        $pdf->SetXY(106, 79);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Captado (Locação) por'), 0, 0, 'L');
        $pdf->SetXY(106, 84);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->captado_locacao_por), 1, 0, 'L');

        $pdf->SetXY(149, 79);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data'), 0, 0, 'L');
        $pdf->SetXY(149, 84);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->data_captacao_locacao)), 1, 0, 'L');

        //

        if ($imovel->tipo_nome == 'Casas') {
            //

            $pdf->Rect(10, 93, 185, 26);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor IPTU'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_iptu)), 1, 0, 'R');

            $pdf->SetXY(45, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Condomínio'), 0, 0, 'L');
            $pdf->SetXY(45, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_condominio)), 1, 0, 'R');

            $pdf->SetXY(78, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor M²'), 0, 0, 'L');
            $pdf->SetXY(78, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_metro)), 1, 0, 'R');

            $pdf->SetXY(101, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Permuta Por'), 0, 0, 'L');
            $pdf->SetXY(101, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(92, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->permuta), 1, 0, 'L');

            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Area Terreno'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_terreno) . ' m2', 1, 0, 'R');

            $pdf->SetXY(40, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Area Construida'), 0, 0, 'L');
            $pdf->SetXY(40, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_construida) . ' m2', 1, 0, 'R');

            $pdf->SetXY(68, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade de Uso'), 0, 0, 'L');
            $pdf->SetXY(68, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(101, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Obra'), 0, 0, 'L');
            $pdf->SetXY(101, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->obra), 1, 0, 'L');

            $pdf->SetXY(134, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Est. da Construção'), 0, 0, 'L');
            $pdf->SetXY(134, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->estado_construcao), 1, 0, 'L');

            $pdf->SetXY(167, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(26, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situação'), 0, 0, 'L');
            $pdf->SetXY(167, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(26, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->situacao), 1, 0, 'L');

            //

            $pdf->Rect(10, 121, 185, 25);

            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Amb/Sala'), 0, 0, 'L');
            $pdf->SetXY(12, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->sala), 1, 0, 'R');

            $pdf->SetXY(30, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Dormitório'), 0, 0, 'L');
            $pdf->SetXY(30, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->dormitorio), 1, 0, 'R');

            $pdf->SetXY(51, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Suíte'), 0, 0, 'L');
            $pdf->SetXY(51, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->suite), 1, 0, 'R');

            $pdf->SetXY(69, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Banheiro'), 0, 0, 'L');
            $pdf->SetXY(69, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->banheiro), 1, 0, 'R');

            $pdf->SetXY(87, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Garagem'), 0, 0, 'L');
            $pdf->SetXY(87, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->garagem), 1, 0, 'R');

            $pdf->SetXY(105, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Frente'), 0, 0, 'L');
            $pdf->SetXY(105, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->frente), 1, 0, 'L');

            $pdf->SetXY(130, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(130, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            $pdf->SetXY(163, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Destaque/Oferta'), 0, 0, 'L');
            $pdf->SetXY(163, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->destaque), 1, 0, 'L');

            //

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');

            $pdf->SetXY(95, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(95, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');

            $pdf->SetXY(128, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Construção'), 0, 0, 'L');
            $pdf->SetXY(128, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->ano_construcao), 1, 0, 'L');

            $pdf->SetXY(162, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Reforma'), 0, 0, 'L');
            $pdf->SetXY(162, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->ano_reforma), 1, 0, 'L');

            //

            $pdf->Rect(10, 148, 185, 56);

            $pdf->SetXY(12, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 154, 89, 47);
            $pdf->SetXY(12, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 154, 89, 47);
            $pdf->SetXY(104, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 206, 185, 80);

            $pdf->SetXY(12, 207);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 212;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        } elseif ($imovel->tipo_nome == 'Apartamentos') {
            //

            $pdf->SetXY(130, 37);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->localizacao), 0, 0, 'L');
            $pdf->SetXY(130, 42);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->condominio), 0, 0, 'L');


            //

            $pdf->Rect(10, 93, 185, 26);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor IPTU'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_iptu)), 1, 0, 'R');

            $pdf->SetXY(45, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Condomínio'), 0, 0, 'L');
            $pdf->SetXY(45, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_condominio)), 1, 0, 'R');

            $pdf->SetXY(78, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor M²'), 0, 0, 'L');
            $pdf->SetXY(78, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_metro)), 1, 0, 'R');

            $pdf->SetXY(101, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Permuta Por'), 0, 0, 'L');
            $pdf->SetXY(101, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(92, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->permuta), 1, 0, 'L');

            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Area Útil'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_util) . ' m2', 1, 0, 'R');

            $pdf->SetXY(40, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Area Total'), 0, 0, 'L');
            $pdf->SetXY(40, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_total) . ' m2', 1, 0, 'R');

            $pdf->SetXY(68, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Aptos por Andar'), 0, 0, 'L');
            $pdf->SetXY(68, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->aps_por_andar), 1, 0, 'R');

            $pdf->SetXY(101, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(101, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');

            $pdf->SetXY(134, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vagas para Visitante'), 0, 0, 'L');
            $pdf->SetXY(134, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->vagas_visitante), 1, 0, 'R');

            $pdf->SetXY(167, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(26, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situação'), 0, 0, 'L');
            $pdf->SetXY(167, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(26, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->situacao), 1, 0, 'L');

            //

            $pdf->Rect(10, 121, 185, 25);

            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Amb/Sala'), 0, 0, 'L');
            $pdf->SetXY(12, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->sala), 1, 0, 'R');

            $pdf->SetXY(30, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Dormitório'), 0, 0, 'L');
            $pdf->SetXY(30, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->dormitorio), 1, 0, 'R');

            $pdf->SetXY(51, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Suíte'), 0, 0, 'L');
            $pdf->SetXY(51, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->suite), 1, 0, 'R');

            $pdf->SetXY(69, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Banheiro'), 0, 0, 'L');
            $pdf->SetXY(69, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->banheiro), 1, 0, 'R');

            $pdf->SetXY(87, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Garagem'), 0, 0, 'L');
            $pdf->SetXY(87, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->garagem), 1, 0, 'R');

            $pdf->SetXY(105, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Frente'), 0, 0, 'L');
            $pdf->SetXY(105, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->frente), 1, 0, 'L');

            $pdf->SetXY(130, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(130, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            $pdf->SetXY(163, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Destaque/Oferta'), 0, 0, 'L');
            $pdf->SetXY(163, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->destaque), 1, 0, 'L');

            //

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');

            $pdf->SetXY(95, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade Uso'), 0, 0, 'L');
            $pdf->SetXY(95, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(129, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Obra'), 0, 0, 'L');
            $pdf->SetXY(129, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->obra), 1, 0, 'L');

            $pdf->SetXY(162, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Estado Construcao'), 0, 0, 'L');
            $pdf->SetXY(162, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->estado_construcao), 1, 0, 'L');

            //

            $pdf->Rect(10, 148, 185, 56);

            $pdf->SetXY(12, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 154, 89, 47);
            $pdf->SetXY(12, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 154, 89, 47);
            $pdf->SetXY(104, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 206, 185, 80);

            $pdf->SetXY(12, 207);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 212;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        } elseif ($imovel->tipo_nome == 'Comercial') {
            //

            $pdf->SetXY(130, 37);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->localizacao), 0, 0, 'L');
            $pdf->SetXY(130, 42);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->condominio), 0, 0, 'L');


            //

            $pdf->Rect(10, 93, 185, 36);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Torre'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(80, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->torre), 1, 0, 'L');

            $pdf->SetXY(95, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Andar'), 0, 0, 'L');
            $pdf->SetXY(95, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->andar), 1, 0, 'L');

            $pdf->SetXY(113, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Área Terreno'), 0, 0, 'L');
            $pdf->SetXY(113, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_terreno . ' m2'), 1, 0, 'R');

            $pdf->SetXY(136, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Área Útil'), 0, 0, 'L');
            $pdf->SetXY(136, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_util . ' m2'), 1, 0, 'R');

            $pdf->SetXY(159, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Garagem'), 0, 0, 'L');
            $pdf->SetXY(159, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->garagem), 1, 0, 'R');

            $pdf->SetXY(177, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(16, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Banheiro'), 0, 0, 'L');
            $pdf->SetXY(177, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(16, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->banheiro), 1, 0, 'R');

            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Pé Direito'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->pe_direito), 1, 0, 'R');

            $pdf->SetXY(35, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(35, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');

            $pdf->SetXY(68, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade de Uso'), 0, 0, 'L');
            $pdf->SetXY(68, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(116, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Zoneamento'), 0, 0, 'L');
            $pdf->SetXY(116, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->zoneamento), 1, 0, 'L');

            $pdf->SetXY(159, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(34, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Topografia'), 0, 0, 'L');
            $pdf->SetXY(159, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(34, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->topografia), 1, 0, 'L');

            //

            $pdf->SetXY(12, 117);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');

            $pdf->SetXY(105, 117);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(105, 122);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            $pdf->SetXY(153, 117);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Destaque/Oferta'), 0, 0, 'L');
            $pdf->SetXY(153, 122);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->destaque), 1, 0, 'L');

            //

            $pdf->Rect(10, 132, 185, 56);

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 138, 89, 47);
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 138, 89, 47);
            $pdf->SetXY(104, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 190, 185, 80);

            $pdf->SetXY(12, 190);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 195;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        } elseif ($imovel->tipo_nome == 'Galpões') {
            //

            $pdf->SetXY(130, 37);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->localizacao), 0, 0, 'L');
            $pdf->SetXY(130, 42);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->condominio), 0, 0, 'L');


            //

            $pdf->Rect(10, 93, 185, 26);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'No.Galpão'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->numero_galpao), 1, 0, 'R');

            $pdf->SetXY(35, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Terreno'), 0, 0, 'L');
            $pdf->SetXY(35, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_terreno . ' m2'), 1, 0, 'R');

            $pdf->SetXY(58, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(21, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Construída'), 0, 0, 'L');
            $pdf->SetXY(58, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(21, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_construida . ' m2'), 1, 0, 'R');

            $pdf->SetXY(84, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Escritório'), 0, 0, 'L');
            $pdf->SetXY(84, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_escritorio . ' m2'), 1, 0, 'R');

            $pdf->SetXY(107, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Galpão'), 0, 0, 'L');
            $pdf->SetXY(107, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_galpao . ' m2'), 1, 0, 'R');

            $pdf->SetXY(130, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Pé direito'), 0, 0, 'L');
            $pdf->SetXY(130, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->pe_direito), 1, 0, 'R');

            $pdf->SetXY(153, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Fabril'), 0, 0, 'L');
            $pdf->SetXY(153, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_fabril . ' m2'), 1, 0, 'R');

            $pdf->SetXY(174, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vão Livre'), 0, 0, 'L');
            $pdf->SetXY(174, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->vao_livre), 1, 0, 'R');

            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Pátio'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_patio) . ' m2', 1, 0, 'R');

            $pdf->SetXY(35, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'metragem'), 0, 0, 'L');
            $pdf->SetXY(35, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->metragem), 1, 0, 'R');

            $pdf->SetXY(58, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cabine Primária'), 0, 0, 'L');
            $pdf->SetXY(58, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->cabine_primaria) . ' Kwh', 1, 0, 'L');

            $pdf->SetXY(91, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(35, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade Uso'), 0, 0, 'L');
            $pdf->SetXY(91, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(35, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(129, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Obra'), 0, 0, 'L');
            $pdf->SetXY(129, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->obra), 1, 0, 'L');

            $pdf->SetXY(162, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Zoneamento'), 0, 0, 'L');
            $pdf->SetXY(162, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->zoneamento), 1, 0, 'L');

            //

            $pdf->Rect(10, 121, 185, 25);

            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situacao Imóvel'), 0, 0, 'L');
            $pdf->SetXY(12, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->situacao), 1, 0, 'L');

            $pdf->SetXY(45, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tipo Imóvel'), 0, 0, 'L');
            $pdf->SetXY(45, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->subtipo_nome), 1, 0, 'L');

            $pdf->SetXY(78, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(78, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');

            $pdf->SetXY(113, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Topografia'), 0, 0, 'L');
            $pdf->SetXY(113, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->topografia), 1, 0, 'L');

            $pdf->SetXY(136, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Força Trifásico'), 0, 0, 'L');
            $pdf->SetXY(136, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->forca_tri), 1, 0, 'R');

            $pdf->SetXY(161, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(161, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            //

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');


            //

            $pdf->Rect(10, 148, 185, 56);

            $pdf->SetXY(12, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 154, 89, 47);
            $pdf->SetXY(12, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 154, 89, 47);
            $pdf->SetXY(104, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 206, 185, 80);

            $pdf->SetXY(12, 207);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 212;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        } elseif ($imovel->tipo_nome == 'Rural') {
            //

            $pdf->SetXY(130, 37);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->localizacao), 0, 0, 'L');
            $pdf->SetXY(130, 42);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->condominio), 0, 0, 'L');


            //

            $pdf->Rect(10, 93, 185, 26);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor IPTU'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_iptu)), 1, 0, 'R');

            $pdf->SetXY(35, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Condomínio'), 0, 0, 'L');
            $pdf->SetXY(35, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_condominio)), 1, 0, 'R');

            $pdf->SetXY(68, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor M2'), 0, 0, 'L');
            $pdf->SetXY(68, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_metro)), 1, 0, 'R');

            $pdf->SetXY(86, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(106, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Permuta Por'), 0, 0, 'L');
            $pdf->SetXY(86, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(106, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->permuta), 1, 0, 'R');


            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Terreno'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_terreno) . ' m2', 1, 0, 'R');

            $pdf->SetXY(35, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Útil'), 0, 0, 'L');
            $pdf->SetXY(35, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_util) . ' m2', 1, 0, 'R');

            $pdf->SetXY(58, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade Uso'), 0, 0, 'L');
            $pdf->SetXY(58, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(101, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Zoneamento'), 0, 0, 'L');
            $pdf->SetXY(101, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->zoneamento), 1, 0, 'L');

            $pdf->SetXY(149, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(43, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situação Imóvel'), 0, 0, 'L');
            $pdf->SetXY(149, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(43, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->situacao), 1, 0, 'L');


            //

            $pdf->Rect(10, 121, 185, 25);

            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Quadra'), 0, 0, 'L');
            $pdf->SetXY(12, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->quadra), 1, 0, 'R');

            $pdf->SetXY(35, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Lote'), 0, 0, 'L');
            $pdf->SetXY(35, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->lote), 1, 0, 'R');

            $pdf->SetXY(58, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Topografia'), 0, 0, 'L');
            $pdf->SetXY(58, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->topografia), 1, 0, 'L');

            $pdf->SetXY(106, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(41, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(106, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(41, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            $pdf->SetXY(150, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Destaque/Oferta'), 0, 0, 'L');
            $pdf->SetXY(150, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->destaque), 1, 0, 'L');

            //

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');

            $pdf->SetXY(105, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(105, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');

            $pdf->SetXY(139, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Construção'), 0, 0, 'L');
            $pdf->SetXY(139, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->ano_construcao), 1, 0, 'L');

            $pdf->SetXY(167, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Reforma'), 0, 0, 'L');
            $pdf->SetXY(167, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->ano_reforma), 1, 0, 'L');

            //

            $pdf->Rect(10, 148, 185, 56);

            $pdf->SetXY(12, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 154, 89, 47);
            $pdf->SetXY(12, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 154, 89, 47);
            $pdf->SetXY(104, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 206, 185, 80);

            $pdf->SetXY(12, 207);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 212;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        } elseif ($imovel->tipo_nome == 'Terrenos') {
            //

            $pdf->SetXY(130, 37);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->localizacao), 0, 0, 'L');
            $pdf->SetXY(130, 42);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->condominio), 0, 0, 'L');


            //

            $pdf->Rect(10, 93, 185, 26);

            $pdf->SetXY(12, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor IPTU'), 0, 0, 'L');
            $pdf->SetXY(12, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_iptu)), 1, 0, 'R');

            $pdf->SetXY(35, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Condomínio'), 0, 0, 'L');
            $pdf->SetXY(35, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_condominio)), 1, 0, 'R');

            $pdf->SetXY(68, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor M2'), 0, 0, 'L');
            $pdf->SetXY(68, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($imovel->valor_metro)), 1, 0, 'R');

            $pdf->SetXY(86, 95);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(106, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Permuta Por'), 0, 0, 'L');
            $pdf->SetXY(86, 100);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(106, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->permuta), 1, 0, 'R');


            //

            $pdf->SetXY(12, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Terreno'), 0, 0, 'L');
            $pdf->SetXY(12, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_terreno) . ' m2', 1, 0, 'R');

            $pdf->SetXY(35, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Á. Útil'), 0, 0, 'L');
            $pdf->SetXY(35, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->area_util) . ' m2', 1, 0, 'R');

            $pdf->SetXY(58, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade Uso'), 0, 0, 'L');
            $pdf->SetXY(58, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->finalidade), 1, 0, 'L');

            $pdf->SetXY(101, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Zoneamento'), 0, 0, 'L');
            $pdf->SetXY(101, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->zoneamento), 1, 0, 'L');

            $pdf->SetXY(149, 106);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(43, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situação Imóvel'), 0, 0, 'L');
            $pdf->SetXY(149, 111);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(43, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->situacao), 1, 0, 'L');


            //

            $pdf->Rect(10, 121, 185, 25);

            $pdf->SetXY(12, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Quadra'), 0, 0, 'L');
            $pdf->SetXY(12, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->quadra), 1, 0, 'R');

            $pdf->SetXY(35, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Lote'), 0, 0, 'L');
            $pdf->SetXY(35, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->lote), 1, 0, 'R');

            $pdf->SetXY(58, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Topografia'), 0, 0, 'L');
            $pdf->SetXY(58, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->topografia), 1, 0, 'L');

            $pdf->SetXY(106, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(41, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Publicar Internet'), 0, 0, 'L');
            $pdf->SetXY(106, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(41, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->internet), 1, 0, 'L');

            $pdf->SetXY(150, 122);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Destaque/Oferta'), 0, 0, 'L');
            $pdf->SetXY(150, 127);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->destaque), 1, 0, 'L');

            //

            $pdf->SetXY(12, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(146, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves'), 0, 0, 'L');
            $pdf->SetXY(12, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(146, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $imovel->chaves), 1, 0, 'L');

            $pdf->SetXY(161, 133);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até'), 0, 0, 'L');
            $pdf->SetXY(161, 138);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($imovel->exclusividade_ate)), 1, 0, 'L');
            //

            $pdf->Rect(10, 148, 185, 56);

            $pdf->SetXY(12, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Observações (Internas da Imobiliária)'), 0, 0, 'L');
            $pdf->Rect(12, 154, 89, 47);
            $pdf->SetXY(12, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->observacao, 0, 410)), 0, 'L');

            $pdf->SetXY(104, 149);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel (Públicas)'), 0, 0, 'L');
            $pdf->Rect(104, 154, 89, 47);
            $pdf->SetXY(104, 154);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($imovel->descricao, 0, 410)), 0, 'L');

            //

            $pdf->Rect(10, 206, 185, 80);

            $pdf->SetXY(12, 207);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características Disponíveis'), 0, 0, 'L');
            $pdf->SetXY(12, 212);
            $pdf->SetFont('Arial', '', 9);
            //
            $ref = $imovel->id;
            $aux = json_decode(caracteristica_listar());
            $x = 12;
            $y = 212;
            $col = 1;
            $limite = 0;
            foreach ($aux as $id) {
                $carac = json_decode(caracteristica_carregar($id));
                $sel = '';
                if (imovel_caracteristica_procurar($ref, $id) && $limite <= 40) {
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(42, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[x] ' . substr($carac->nome, 0, 25)), 0, 0, 'L');
                    $x+=45;
                    $col++;
                    if ($col == 5) {
                        $x = 12;
                        $y+=5;
                        $col = 1;
                    }
                    $limite++;
                }
            }
        }

        $pdf->SetXY(95, 5);
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetTextColor(200, 200, 200);
        $pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sistema SGI Fácil : www.sgifacil.com.br ' . $_SESSION['usuario_id'] . '-' . $_SERVER['REMOTE_ADDR'] . ' '), 0, 0, 'R');

        $pdf->Output();
    }
}
    