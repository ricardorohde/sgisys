<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');
ini_set('register_globals', 'off');
ini_set('display_errors', 'on');

include 'func.php';


include 'fpdf/fpdf.php';

setlocale(LC_CTYPE, 'pt_BR');

class PDF extends FPDF {

    function Header() {

        include '../controller/ficha_config.php';
        $ficha_config = json_decode(ficha_config_carregar());

        include 'func.php';

        $this->SetDrawColor(10, 10, 10);

        $this->SetXY(185, 5);
        $this->SetFont('Arial', '', 6);
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
            $this->Image('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $ficha_config->logo, 11, 11, 38, 18);
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
        $this->SetFillColor(230, 230, 230);

        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ref'), 1, 0, 'L', 1);
        $this->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tipo'), 1, 0, 'L', 1);
        $this->Cell(87, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Endereço'), 1, 0, 'L', 1);
        $this->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Bairro'), 1, 0, 'L', 1);
        $this->Cell(35, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cidade'), 1, 0, 'L', 1);
        $this->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Dorm'), 1, 0, 'C', 1);
        $this->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vagas'), 1, 0, 'C', 1);
        $this->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'WC'), 1, 0, 'C', 1);
        $this->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vl.Venda'), 1, 0, 'R', 1);
        $this->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vl.Locação'), 1, 0, 'R', 1);

        $this->Ln(7);
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
$cadastro_where = $_SESSION['pesquisa'][$tipo_cadastro]['where'];
$cadastro_order = $_SESSION['pesquisa'][$tipo_cadastro]['order'];

$ret = json_decode(cadastro_listar($tipo_cadastro, $cadastro_where, '', ''));
$tot = count($ret);
$cadastro_rows = '';
$ret = json_decode(cadastro_listar($tipo_cadastro, $cadastro_where, $cadastro_order, $cadastro_rows));

foreach ($ret as $id) {
    $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

    $pdf->SetFont('Arial', '', 8);
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->id), 1, 0, 'L');
    $pdf->Cell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->tipo_nome), 1, 0, 'L');
    $pdf->Cell(87, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero), 1, 0, 'L');
    $pdf->Cell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->bairro), 1, 0, 'L');
    $pdf->Cell(35, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->cidade), 1, 0, 'L');
    $pdf->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->dormitorio), 1, 0, 'C');
    $pdf->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->garagem), 1, 0, 'C');
    $pdf->Cell(11, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cad->banheiro), 1, 0, 'C');
    $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_venda)), 1, 0, 'R');
    $pdf->Cell(20, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', us_br($cad->valor_locacao)), 1, 0, 'R');
    $pdf->Ln(5);
}




$pdf->Output();
