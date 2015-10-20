<?php

include 'fpdf/fpdf.php';

setlocale(LC_CTYPE, 'pt_BR');

class PDF extends FPDF {

    function Header() {

        include '../controller/ficha_config.php';
        $ficha_config = json_decode(ficha_config_carregar());

        include 'func.php';

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
        $situacao = 'Ativo';
        $subtipo_nome = '';
        $edificio = '';
        $quadra_de = '';
        $quadra_ate = '';
        $lote_de = '';
        $lote_ate = '';
        $metragem_de = '';
        $metragem_ate = '';
        $zoneamento = '';
        $topografia = '';
        $valor_metro_de = '';
        $valor_metro_ate = '';

        if (isset($_SESSION['situacao'])) {
            $condicoes = " Situação = {$_SESSION['situacao']} ";
        }

        if (isset($_SESSION['cidade'])) {
            $cidade = $_SESSION['cidade'];
        }

        if (!empty($cidade)) {
            $condicoes .= " e Cidade = '" . $cidade . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['bairro'])) {
            $bairro = $_SESSION['bairro'];
        }

        if (!empty($bairro)) {
            $condicoes .= " e Bairro = '" . $bairro . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['localizacao'])) {
            $localizacao = $_SESSION['localizacao'];
        }

        if (!empty($localizacao)) {
            $condicoes .= " e Localização = '" . $localizacao . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['condominio'])) {
            $condominio = $_SESSION['condominio'];
        }

        if (!empty($condominio)) {
            $condicoes .= " e Condomínio = '" . $condominio . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['endereco'])) {
            $endereco = $_SESSION['endereco'];
        }

        if (!empty($endereco)) {
            $condicoes .= " e Endereço = '" . $endereco . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['area_terreno_de'])) {
            $area_terreno_de = $_SESSION['area_terreno_de'];
        }

        if (isset($_SESSION['area_terreno_de'])) {
            $area_terreno_ate = $_SESSION['area_terreno_de'];
        }

        if (($area_terreno_de > 0 || $area_terreno_ate > 0)) {
            $condicoes .= " e Área Terreno entre $area_terreno_de e $area_terreno_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['valor_venda_de'])) {
            $valor_venda_de = $_SESSION['valor_venda_de'];
        }

        if (isset($_SESSION['valor_venda_ate'])) {
            $valor_venda_ate = $_SESSION['valor_venda_ate'];
        }

        if (($valor_venda_de > 0 || $valor_venda_ate > 0)) {
            $condicoes .= " e Valor Venda entre " . us_br($valor_venda_de) . " e " . us_br($valor_venda_ate) . " ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['area_construida_de'])) {
            $area_construida_de = $_SESSION['area_construida_de'];
        }

        if (isset($_SESSION['area_construida_ate'])) {
            $area_construida_ate = $_SESSION['area_construida_ate'];
        }

        if (($area_construida_de > 0 || $area_construida_ate > 0)) {
            $condicoes .= " e Área Construída entre $area_construida_de e $area_construida_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['valor_locacao_de'])) {
            $valor_locacao_de = $_SESSION['valor_locacao_de'];
        }

        if (isset($_SESSION['valor_locacao_ate'])) {
            $valor_locacao_ate = $_SESSION['valor_locacao_ate'];
        }

        if (($valor_locacao_de > 0 || $valor_locacao_ate > 0)) {
            $condicoes .= " e Valor Locação entre " . us_br($valor_locacao_de) . " e " . us_br($valor_locacao_ate) . " ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['valor_condominio_de'])) {
            $valor_condominio_de = $_SESSION['valor_condominio_de'];
        }

        if (isset($_SESSION['valor_condominio_ate'])) {
            $valor_condominio_ate = $_SESSION['valor_condominio_ate'];
        }

        if (($valor_condominio_de > 0 || $valor_condominio_ate > 0)) {
            $condicoes .= " e Valor Condomínio entre " . us_br($valor_condominio_de) . " e " . us_br($valor_condominio_ate) . " ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['dormitorio_de'])) {
            $dormitorio_de = $_SESSION['dormitorio_de'];
        }

        if (isset($_SESSION['dormitorio_ate'])) {
            $dormitorio_ate = $_SESSION['dormitorio_ate'];
        }

        if (($dormitorio_de > 0 || $dormitorio_ate > 0)) {
            $condicoes .= " e Dormitórios entre $dormitorio_de e $dormitorio_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['obra'])) {
            $obra = $_SESSION['obra'];
        }
        if (isset($_SESSION['suite_de'])) {
            $suite_de = $_SESSION['suite_de'];
        }

        if (isset($_SESSION['suite_ate'])) {
            $suite_ate = $_SESSION['suite_ate'];
        }

        if (($suite_de > 0 || $suite_ate > 0)) {
            $condicoes .= " e Suítes entre $suite_de e $suite_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['permuta'])) {
            $permuta = $_SESSION['permuta'];
        }

        if (!empty($permuta)) {
            $condicoes .= " e Aceita Permuta = '" . $permuta . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['banheiro_de'])) {
            $banheiro_de = $_SESSION['banheiro_de'];
        }

        if (isset($_SESSION['banheiro_ate'])) {
            $banheiro_ate = $_SESSION['banheiro_ate'];
        }

        if (($banheiro_de > 0 || $banheiro_ate > 0)) {
            $condicoes .= " e Banheiro entre $banheiro_de e $banheiro_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['tipo_nome'])) {
            $tipo_nome = $_SESSION['tipo_nome'];
        }

        if (!empty($tipo_nome)) {
            $condicoes .= " e Tipo = '" . $tipo_nome . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['garagem_de'])) {
            $garagem_de = $_SESSION['garagem_de'];
        }

        if (isset($_SESSION['garagem_ate'])) {
            $garagem_ate = $_SESSION['garagem_ate'];
        }

        if (($garagem_de > 0 || $garagem_ate > 0)) {
            $condicoes .= " e Garagem entre $garagem_de e $garagem_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['subtipo_nome'])) {
            $subtipo_nome = $_SESSION['subtipo_nome'];
        }

        if (!empty($subtipo_nome)) {
            $condicoes .= " e Subtipo = '" . $subtipo_nome . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['edificio'])) {
            $edificio = $_SESSION['edificio'];
        }

        if (!empty($edificio)) {
            $condicoes .= " e Edifício = '" . $edificio . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['zoneamento'])) {
            $zoneamento = $_SESSION['zoneamento'];
        }

        if (!empty($zoneamento)) {
            $condicoes .= "e Zoneamento = '" . $zoneamento . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['topografia'])) {
            $topografia = $_SESSION['topografia'];
        }

        if (!empty($topografia)) {
            $condicoes .= " e Topografia = '" . $topografia . "' ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['quadra_de'])) {
            $quadra_de = $_SESSION['quadra_de'];
        }

        if (isset($_SESSION['quadra_ate'])) {
            $quadra_ate = $_SESSION['quadra_ate'];
        }

        if (($quadra_de > 0 || $quadra_ate > 0)) {
            $condicoes .= " e Quadra entre $quadra_de e $quadra_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['lote_de'])) {
            $lote_de = $_SESSION['lote_de'];
        }

        if (isset($_SESSION['lote_ate'])) {
            $lote_ate = $_SESSION['lote_ate'];
        }

        if (($lote_de > 0 || $lote_ate > 0)) {
            $condicoes .= " e Lote entre $lote_de e $lote_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['metragem_de'])) {
            $metragem_de = $_SESSION['metragem_de'];
        }

        if (isset($_SESSION['metragem_ate'])) {
            $metragem_ate = $_SESSION['metragem_ate'];
        }

        if (($metragem_de > 0 || $metragem_ate > 0)) {
            $condicoes .= " e Metragem entre $metragem_de e $metragem_ate ";
            $pesquisando = 'S';
        }
        if (isset($_SESSION['valor_metro_de'])) {
            $valor_metro_de = $_SESSION['valor_metro_de'];
        }

        if (isset($_SESSION['valor_metro_ate'])) {
            $valor_metro_ate = $_SESSION['valor_metro_ate'];
        }

        if (($valor_metro_de > 0 || $valor_metro_ate > 0)) {
            $condicoes .= " e Valor M2 entre " . us_br($valor_metro_de) . " e " . us_br($valor_metro_ate) . " ";
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
        if (empty($quadra_ate)) {
            $quadra_ate = 0;
        }
        if (empty($quadra_de)) {
            $quadra_de = 0;
        }
        if (empty($lote_ate)) {
            $lote_ate = 0;
        }
        if (empty($lote_de)) {
            $lote_de = 0;
        }
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

        $this->SetDrawColor(10, 10, 10);

        $this->SetXY(185, 5);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(200, 200, 200);
        $this->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sistema SGI Fácil : www.sgifacil.com.br ' . $_SESSION['usuario_id'] . '-' . $_SERVER['REMOTE_ADDR'] . ' '), 0, 0, 'R');

        $this->SetXY(185, 10);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(10, 10, 10);
        $this->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data : ' . date('d/m/Y H:i')), 0, 0, 'R');

        $this->SetXY(185, 15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Página : ' . $this->PageNo() . ' de {nb} '), 0, 0, 'R');

        $this->Rect(10, 10, 275, 20);
        $this->Rect(10, 10, 40, 20);

        if (!empty($ficha_config->logo)) {
            $logo = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $ficha_config->logo;
            if (file_exists($logo)) {
                $this->Image($logo, 11, 11, 38, 18);
            }
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(230, 230, 230);

        $this->SetXY(52, 15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto1), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->SetXY(52, 20);
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto2), 0, 0, 'L');
        $this->SetXY(52, 20);

        $this->SetXY(10, 33);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(275, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Relatório de Imóvel'), 0, 0, 'C');

        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(255, 255, 255);

        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(270, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Critério: ' . $condicoes), 0, 0, 'L', 0);
        $this->Ln(7);

        if (empty($tipo_nome)) {
            $this->Cell(70, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO/PROPRIETÁRIO/TELEFONES'), $tipo_moldura, 0, 'L', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AT'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AC'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FRE'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FUN'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PRO'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DORM'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'SU'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ES'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PIS'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VG'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'L', 1);
            $this->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'L', 1);
            $this->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 1);
            $this->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 1);
            $this->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 1);
            $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'WEB'), $tipo_moldura, 0, 'C', 1);
        } elseif ($tipo_nome == 'Casas' || $tipo_nome == 'Rural') {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(50, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AT'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AC'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DRM'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'SU'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ES'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PIS'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VG'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TIPO'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CHAVES'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '@'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(68, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROPRIETÁRIOS'), $tipo_moldura, 0, 'L', 0);
            $this->Ln(3);
        } elseif ($tipo_nome == 'Apartamentos') {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(20, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'NUM.APTO'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AT'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AU'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DRM'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'SU'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ES'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PIS'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VG'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TIPO'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CHAVES'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '@'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(98, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROPRIETÁRIOS'), $tipo_moldura, 0, 'L', 0);
            $this->Ln(3);
        } elseif ($tipo_nome == 'Galpões') {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(60, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'N.GALP'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AC'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AT'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PD'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'BN'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TIPO'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CHAVES'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '@'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROPRIETÁRIOS'), $tipo_moldura, 0, 'L', 0);
            $this->Ln(3);
        } elseif ($tipo_nome == 'Comercial') {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(60, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CONJ'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AU'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'AT'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PD'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'BN'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TIPO'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CHAVES'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '@'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROPRIETÁRIOS'), $tipo_moldura, 0, 'L', 0);
            $this->Ln(3);
        } elseif ($tipo_nome == 'Terrenos') {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(54, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MT'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Q'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'L'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(16, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TP'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(14, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VM'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(17, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VENDA'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'LOCAÇÃO'), $tipo_moldura, 0, 'R', 0);
            $this->Cell(13, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ULTCONF'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CAPTADOR'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'REF'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TIPO'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CHAVES'), $tipo_moldura, 0, 'L', 0);
            $this->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '@'), $tipo_moldura, 0, 'C', 0);
            $this->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROPRIETÁRIOS'), $tipo_moldura, 0, 'L', 0);
            $this->Ln(3);
        }
        $this->Ln(5);
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');

include '../controller/ficha_config.php';
$ficha_config = json_decode(ficha_config_carregar());

$itenspp = 20;

include '../controller/cadastro.php';

$tipo_cadastro = 'imovel';
$condicoes = $_SESSION['pesquisa'][$tipo_cadastro]['where'];
$cadastro_order = $_SESSION['pesquisa'][$tipo_cadastro]['order'];

if (isset($_SESSION['tipo_nome'])) {
    $tipo_nome = $_SESSION['tipo_nome'];
} else {
    $tipo_nome = '';
}

if ($tipo_nome == 'Casas' || $tipo_nome == 'Rural' || $tipo_nome == 'Galpões' || $tipo_nome == 'Comercial') {
    $cadastro_order = 'ORDER BY condominio, bairro, logradouro, numero';
} elseif ($tipo_nome == 'Apartamentos') {
    $cadastro_order = 'ORDER BY condominio, edificio, logradouro, numero, numero_apartamento';
} elseif ($tipo_nome == 'Terrenos') {
    $cadastro_order = 'ORDER BY condominio, bairro, logradouro, numero, quadra, lote';
}

if (isset($_POST['com_proprietario'])) {
    $com_proprietario = $_POST['com_proprietario'];
} else {
    $com_proprietario = '';
}

if (isset($_POST['com_foto'])) {
    $com_foto = $_POST['com_foto'];
} else {
    $com_foto = '';
}

if (isset($_POST['com_pagto'])) {
    $com_pagto = $_POST['com_pagto'];
} else {
    $com_pagto = '';
}

if (isset($_POST['com_espaco'])) {
    $com_espaco = $_POST['com_espaco'];
} else {
    $com_espaco = '';
}

if ($com_espaco == 'S') {
    $tipo_moldura = 'LTR';
} else {
    $tipo_moldura = 1;
}

$espaco = 3;




$ret = json_decode(cadastro_listar($tipo_cadastro, $condicoes, '', ''));
$tot = count($ret);
$cadastro_rows = '';
$ret = json_decode(cadastro_listar($tipo_cadastro, $condicoes, $cadastro_order, $cadastro_rows));

//
if (empty($tipo_nome)) {

    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = ' - ' . $prop->nome . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }


        if ($refs != $cad->condominio . $cad->bairro) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->bairro), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $cad->condominio . $cad->bairro;
        }

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        if ($com_foto == 'S') {
            if (!empty($cad->foto)) {
                if (file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $cad->foto)) {
                    $pdf->Image('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $cad->foto, null, null, 38, 18);
                } else {
                    $pdf->Image('../site/fotos/' . $_SESSION['cliente_id'] . '/sem_foto.jpg', null, null, 38, 18);
                }
            } else {
                $pdf->Image('../site/fotos/' . $_SESSION['cliente_id'] . '/sem_foto.jpg', null, null, 38, 18);
            }
        }

        $pdf->Cell(70, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_terreno), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_construida), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->m2_frente), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->fundos), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->profundidade), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->dormitorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->suite), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->escritorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->piscina), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->garagem), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->captado_venda_por, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(15, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->id), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->internet), $tipo_moldura, 0, 'C', 0);
        $pdf->Ln(6);

        if ($com_espaco == 'S') {
            $espaco = 5;
            $pdf->Cell(70, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(20, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(20, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(22, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Ln(1 + $espaco);
        }


        if (!empty($cad->chaves)) {
            $pdf->Cell(90, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves : ' . substr($cad->chaves, 0, 50)), 0, 0, 'L', 0);
        }

        if ($com_pagto == 'S' && !empty($cad->condicoes_pagamento)) {
            $pdf->Cell(150, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Condições Pagto : ' . substr($cad->condicoes_pagamento, 0, 90)), 0, 0, 'L', 0);
            $pdf->Ln(6);
        }

        if ($com_proprietario == 'S' && $prop) {
            $pdf->Cell(150, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Proprietário : ' . $prop_nome), 0, 0, 'L', 0);
            $pdf->Ln(6);
        }

        $pdf->Ln(3);
    }
} elseif ($tipo_nome == 'Casas' || $tipo_nome == 'Rural') {


    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = $prop->nome;

            if (empty($prop_nome)) {
                $prop_nome = $prop->apelido;
            }

            $prop_nome = substr($prop_nome, 0, 30) . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel; // . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }

        $endereco = substr($cad->tipo_logradouro . ' ' . $cad->logradouro, 0, 50) . ' ' . $cad->numero;

        $ponteiro = $cad->condominio . $cad->bairro;

        if ($refs != $ponteiro) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->bairro), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $ponteiro;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(50, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $endereco), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_terreno), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_construida), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->dormitorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->suite), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->escritorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->piscina), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->garagem), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr(substr($cad->captado_venda_por, 0, 15), 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', str_pad($cad->id, 7, '0', 0)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->subtipo_nome, 0, 4)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->chaves, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->internet, 0, 1)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(68, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($prop_nome, 0, 55)), $tipo_moldura, 0, 'L', 0);
        $pdf->Ln(3);

        if ($com_espaco == 'S') {
            $pdf->Cell(50, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(21, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(5, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(68, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Ln($espaco);
        }
    }
} elseif ($tipo_nome == 'Apartamentos') {

    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = $prop->nome;

            if (empty($prop_nome)) {
                $prop_nome = $prop->apelido;
            }

            $prop_nome = substr($prop_nome, 0, 30) . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel; // . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }

        $endereco = ' - ' . substr($cad->tipo_logradouro . ' ' . $cad->logradouro, 0, 90) . ' ' . $cad->numero;

        $ponteiro = $cad->condominio . $cad->edificio . $endereco;

        if ($refs != $ponteiro) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->edificio . $endereco), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $ponteiro;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(20, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->numero_apartamento), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_terreno), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_construida), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->dormitorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->suite), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->escritorio), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->piscina), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->garagem), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr(substr($cad->captado_venda_por, 0, 15), 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', str_pad($cad->id, 7, '0', 0)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->subtipo_nome, 0, 4)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->chaves, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->internet, 0, 1)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(98, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($prop_nome, 0, 55)), $tipo_moldura, 0, 'L', 0);
        $pdf->Ln(3);

        if ($com_espaco == 'S') {
            $pdf->Cell(20, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(21, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(5, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(98, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Ln($espaco);
        }
    }
} elseif ($tipo_nome == 'Galpões') {


    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = $prop->nome;

            if (empty($prop_nome)) {
                $prop_nome = $prop->apelido;
            }

            $prop_nome = substr($prop_nome, 0, 30) . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel; // . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }

        $endereco = substr($cad->tipo_logradouro . ' ' . $cad->logradouro, 0, 47) . ' ' . $cad->numero;

        $ponteiro = $cad->condominio . $cad->bairro;

        if ($refs != $ponteiro) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->bairro), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $ponteiro;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(60, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $endereco), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->numero_galpao), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_construida), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_terreno), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->pe_direito), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->banheiro), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr(substr($cad->captado_venda_por, 0, 15), 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', str_pad($cad->id, 7, '0', 0)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->subtipo_nome, 0, 4)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->chaves, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->internet, 0, 1)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($prop_nome, 0, 45)), $tipo_moldura, 0, 'L', 0);
        $pdf->Ln(3);

        if ($com_espaco == 'S') {
            $pdf->Cell(60, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(12, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(12, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(21, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(5, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(58, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Ln($espaco);
        }
    }
} elseif ($tipo_nome == 'Comercial') {


    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = $prop->nome;

            if (empty($prop_nome)) {
                $prop_nome = $prop->apelido;
            }

            $prop_nome = substr($prop_nome, 0, 30) . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel; // . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }

        $endereco = substr($cad->tipo_logradouro . ' ' . $cad->logradouro, 0, 47) . ' ' . $cad->numero;

        $ponteiro = $cad->condominio . $cad->bairro;

        if ($refs != $ponteiro) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->bairro), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $ponteiro;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(60, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $endereco), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->conjunto), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_util), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(12, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->area_terreno), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->pe_direito), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(7, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->banheiro), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr(substr($cad->captado_venda_por, 0, 15), 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', str_pad($cad->id, 7, '0', 0)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->subtipo_nome, 0, 4)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->chaves, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->internet, 0, 1)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($prop_nome, 0, 45)), $tipo_moldura, 0, 'L', 0);
        $pdf->Ln(3);
        if ($com_espaco == 'S') {
            $pdf->Cell(60, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(12, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(12, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(7, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(21, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(5, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(58, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Ln($espaco);
        }
    }
} elseif ($tipo_nome == 'Terrenos') {


    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        if ($com_proprietario == 'S' && $usu->proprietario_consultar == 'Sim') {

            $prop = json_decode(cadastro_carregar('proprietario', $cad->proprietario));

            $prop_nome = $prop->nome;

            if (empty($prop_nome)) {
                $prop_nome = $prop->apelido;
            }

            $prop_nome = substr($prop_nome, 0, 30) . ' ' . $prop->fone1 . ' ' . $prop->fone2 . ' ' . $prop->cel; // . ' ' . $prop->email;
        } else {
            $prop_nome = '';
        }

        $endereco = substr($cad->tipo_logradouro . ' ' . $cad->logradouro, 0, 47) . ' ' . $cad->numero;

        $ponteiro = $cad->condominio . $cad->bairro;

        if ($refs != $ponteiro) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(200, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->condominio . ' - ' . $cad->bairro), 0, 0, 'L', 0);
            $pdf->Ln(6);
            $refs = $ponteiro;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Cell(54, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $endereco), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->metragem), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->quadra, 0, 5)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->lote, 0, 5)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(16, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->topografia), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(14, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->valor_metro), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(17, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(15, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(13, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', data_decode($cad->data_atualizacao)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(18, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr(substr($cad->captado_venda_por, 0, 15), 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(10, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', str_pad($cad->id, 7, '0', 0)), $tipo_moldura, 0, 'R', 0);
        $pdf->Cell(8, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->subtipo_nome, 0, 4)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(21, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->chaves, 0, 15)), $tipo_moldura, 0, 'L', 0);
        $pdf->Cell(5, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cad->internet, 0, 1)), $tipo_moldura, 0, 'C', 0);
        $pdf->Cell(58, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($prop_nome, 0, 45)), $tipo_moldura, 0, 'L', 0);
        $pdf->Ln(3);
        if ($com_espaco == 'S') {
            $pdf->Cell(54, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(8, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(8, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(16, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(14, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(17, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(15, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(13, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(18, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(10, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(8, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Cell(21, $espaco, '', 'LBR', 0, 'R', 0);
            $pdf->Cell(5, $espaco, '', 'LBR', 0, 'C', 0);
            $pdf->Cell(58, $espaco, '', 'LBR', 0, 'L', 0);
            $pdf->Ln($espaco);
        }
    }
}

$pdf->Output();


