<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

include 'func.php';


include 'fpdf/fpdf.php';

setlocale(LC_CTYPE, 'pt_BR');


include '../controller/ficha_config.php';
$ficha_config = json_decode(ficha_config_carregar());



$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetXY(95, 5);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(200, 200, 200);
$pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sistema SGI Fácil : www.sgifacil.com.br ' . $_SESSION['usuario_id'] . '-' . $_SERVER['REMOTE_ADDR'] . ' '), 0, 0, 'R');

$pdf->Rect(10, 10, 185, 20);
$pdf->Rect(10, 10, 40, 20);

if (!empty($ficha_config->logo)) {
    $logo = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $ficha_config->logo;
    if (file_exists($logo)) {
        $pdf->Image($logo, 11, 11, 38, 18);
    }
}

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(230, 230, 230);

$pdf->SetXY(52, 15);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto1), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(52, 20);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $ficha_config->texto2), 0, 0, 'L');
$pdf->SetXY(52, 20);

$pdf->SetXY(10, 33);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(185, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ficha de Captação de Imóvel'), 0, 0, 'C');

$pdf->SetXY(10, 40);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tipo de imóvel : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(155, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Casas   [  ]Apartamentos   [  ]Terrenos   [  ]Galpões   [  ]Comercial   [  ]Rural '), 1, 0, 'C');

$pdf->SetXY(10, 46);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Captador V: '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data: '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor VENDA : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'R$'), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Captador L: '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Data: '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Locação : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'R$'), 1, 0, 'L');
$pdf->Ln(9);


// Endereço
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Endereço : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(115, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CEP : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Bairro : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cidade : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'UF : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Localização : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Condomínio : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor Condominio : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'R$'), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor IPTU : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'R$'), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Edifício : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(75, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Torre : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Complemento : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Andar:'), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sala/Cj:'), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Galpão:'), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Lote:'), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Quadra:'), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(9);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Quartos : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Banheiro : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Suíte : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sala : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Garagem : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Útil : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(36, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Construída : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(36, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Total : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Terreno : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(36, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Metragem : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(36, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Fabril : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'm2'), 1, 0, 'R');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Finalidade : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Comercial   [  ]Residencial'), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Obra : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Pronta   [  ]Parada   [  ]Em Construção'), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Chaves : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(85, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Exclusividade Até : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Permuta Por : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Valor M2 : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'R$'), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Est.Construção: '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Excelente   [  ]Bom   [  ]Regular   [  ]Ruim   [  ]Reformar'), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Frente : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]N  [  ]S  [  ]L  [  ]O'), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(33, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Construção : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Ano Reforma : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Internet : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(32, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Sim   [  ]Não'), 1, 0, 'L');
$pdf->Ln(9);

// Prop
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Proprietário : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(155, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Endereço : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(115, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CEP : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Bairro : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cidade : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'UF : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tel1 : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(31, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Tel2 : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(31, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cel : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(31, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'T.Com : '), 1, 0, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(32, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 0, 'L');
$pdf->Ln(9);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Descrição do Imóvel : '), 1, 1, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 1, 'L');
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 1, 'L');
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 1, 'L');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Características : '), 0, 1, 'L', 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Armário na cozinha'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Andar inteiro'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Armário nos dormitórios'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Estacionamento'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Copa'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Refeitório'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Closet'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Escritório'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Dependência de empregada'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Entrada para caminhões'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]W.C.Área de Serviço'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Assobradado'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Lavabo'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]W.C. social'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Bifásico'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Edícula'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Trifásico'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Varanda'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Planta regularizada'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Sala de visita'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Entrada lateral'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Sala de jantar'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Garagem coberta'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Quintal'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(55, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Garagem descoberta'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Interfone'), 0, 0, 'L');
$pdf->Cell(45, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '[  ]Vestiário'), 0, 0, 'L');
$pdf->Ln(6);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Outras Características'), 1, 1, 'L', 1);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ''), 1, 1, 'L');
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(185, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '__________________, ____/____/______       De Acordo, Nome: _______________________________________ DOC _____________________'), 0, 1, 'L');



$pdf->Output();
