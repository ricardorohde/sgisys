<?php
session_start();
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
        <link href="css/agenda.css" rel="stylesheet" />
        <script src="../controller/js/agenda.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Home';
        include 'menu.php';
        if (isset($_GET['dia'])) {
            $dia = $_GET['dia'];
        } else {
            $dia = date('d');
        }
        if (isset($_GET['mes'])) {
            $mes = $_GET['mes'];
        } else {
            $mes = date('m');
        }
        if (isset($_GET['ano'])) {
            $ano = $_GET['ano'];
        } else {
            $ano = date('Y');
        }

        $ano = str_pad($ano, 4, '0', 0);
        $mes = str_pad($mes, 2, '0', 0);
        $dia = str_pad($dia, 2, '0', 0);

        $diasemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, $dia, $ano), 0);
        ?>
        <div id="conteudo">
            <h3>Agenda e Calend&aacute;rio do Usu&aacute;rio</h3>
            <div class="pastas">
                <?php
                MostreCalendario($mes, $ano);
                echo "<br/>";
                ?>
            </div>
            <div class="agendas">
                <h4 style="text-align: right;">Compromissos de <?php echo $_SESSION['usuario_nome'] . ' de &nbsp;&nbsp;&nbsp;&nbsp;<br><strong style="font-size: 14pt;color: #ff6600;">' . DiaSemana($diasemana) . ', ' . $dia . '/' . $mes . '/' . $ano . '</strong>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;</h4>
                <table width="49%" style="float:left;">
                    <?php
                    include '../controller/agenda.php';
                    $ret = json_decode(agenda_listar($ano . $mes . $dia, $_SESSION['usuario_id']));
                    $tot = count($ret);
                    foreach ($ret as $id) {
                        $agenda = json_decode(agenda_carregar($id));
                        $comp_text = 'compromisso-text';
                        if (strlen($agenda->compromisso) > 0) {
                            $comp_text = 'compromisso-text2';
                        }
                        if (intval($agenda->hora) <= 1130) {
                            $estilo = 'background-color: #eee;';
                            echo '<tr class="listagem-item" style="' . $estilo . '">';
                            echo '<td width="50"><center>' . substr($agenda->hora, 0, 2) . ':' . substr($agenda->hora, 2) . '</center></td>';
                            echo '<td><input class="' . $comp_text . '" type="text" name="c' . $agenda->hora . '" size="40" value="' . $agenda->compromisso . '" title="' . $agenda->compromisso . '" onchange="grava_compromisso(\'' . $_SESSION['usuario_id'] . '\',\'' . $agenda->data . '\',\'' . $agenda->hora . '\',this.value);"></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
                <table width="49%" style="float:left;">
                    <?php
                    $ret = json_decode(agenda_listar($ano . $mes . $dia, $_SESSION['usuario_id']));
                    $tot = count($ret);
                    foreach ($ret as $id) {
                        $agenda = json_decode(agenda_carregar($id));
                        $comp_text = 'compromisso-text';
                        if (strlen($agenda->compromisso) > 0) {
                            $comp_text = 'compromisso-text2';
                        }
                        if (intval($agenda->hora) > 1130) {
                            $estilo = 'background-color: #eee;';
                            echo '<tr class="listagem-item" style="' . $estilo . '">';
                            echo '<td width="50"><center>' . substr($agenda->hora, 0, 2) . ':' . substr($agenda->hora, 2) . '</center></td>';
                            echo '<td><input class="' . $comp_text . '" type="text" name="c' . $agenda->hora . '" size="40" value="' . $agenda->compromisso . '"  title="' . $agenda->compromisso . '" onchange="grava_compromisso(\'' . $_SESSION['usuario_id'] . '\',\'' . $agenda->data . '\',\'' . $agenda->hora . '\',this.value);"></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        <div id="fundo-negro" onclick="fecha_agenda();"></div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>

<?php

function MostreSemanas() {
    $semanas = "DSTQQSS";

    for ($i = 0; $i < 7; $i++) {
        echo "<td bgcolor='#ccc'><center>" . $semanas{$i} . "</center></td>";
    }
}

function GetNumeroDias($mes) {
    $numero_dias = array(
        '01' => 31, '02' => 28, '03' => 31, '04' => 30, '05' => 31, '06' => 30,
        '07' => 31, '08' => 31, '09' => 30, '10' => 31, '11' => 30, '12' => 31
    );

    if (((date('Y') % 4) == 0 and ( date('Y') % 100) != 0) or ( date('Y') % 400) == 0) {
        $numero_dias['02'] = 29;
    }

    return $numero_dias[$mes];
}

function GetNomeMes($mes) {
    $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março",
        '04' => "Abril", '05' => "Maio", '06' => "Junho",
        '07' => "Julho", '08' => "Agosto", '09' => "Setembro",
        '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro"
    );

    if ($mes >= 01 && $mes <= 12) {
        return $meses[$mes];
    }

    return "Mês deconhecido";
}

function DiaSemana($dianumero) {
    $dias = array('0' => "Domingo", '1' => "Segunda", '2' => "Terça",
        '3' => "Quarta", '4' => "Quinta", '5' => "Sexta",
        '6' => "Sábado", '7' => "Domingo"
    );

    return $dias[$dianumero];
}

function MostreCalendario($mes, $ano) {

    $numero_dias = GetNumeroDias($mes);
    $nome_mes = GetNomeMes($mes);
    if (empty($ano)) {
        $ano = date('Y');
    }
    $diacorrente = 0;

    $diasemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, "01", $ano), 0); // função que descobre o dia da semana

    $aano = $ano;
    $ames = ($mes - 1);
    if ($ames == 0) {
        $ames = 12;
        $aano--;
    }
    $pano = $ano;
    $pmes = ($mes + 1);
    if ($pmes == 13) {
        $pmes = 1;
        $pano++;
    }

    $aano = str_pad($aano, 4, '0', 0);
    $ames = str_pad($ames, 2, '0', 0);
    $pano = str_pad($pano, 4, '0', 0);
    $pmes = str_pad($pmes, 2, '0', 0);

    echo "<table border = 0 cellspacing = '2' width='240'>";
    echo "<tr>";
    echo "<td colspan = 7><center><h3><a href='agenda.php?mes=" . $ames . "&ano=" . $aano . "' target='frmPrincipal'>◄</a>&nbsp;<a href='agenda.php?mes=" . date('m') . "&ano=" . date('Y') . "' target='frmPrincipal'>" . $nome_mes . "/" . $ano . "</a>&nbsp;<a href='agenda.php?mes=" . $pmes . "&ano=" . $pano . "' target='frmPrincipal'>►</a></h3></center></td>";
    echo "</tr>";
    echo "<tr>";
    MostreSemanas(); // função que mostra as semanas aqui
    echo "</tr>";
    for ($linha = 0; $linha < 6; $linha++) {


        echo "<tr>";

        for ($coluna = 0; $coluna < 7; $coluna++) {
            echo "<td width = 30 height = 30 ";

            if (($diacorrente == ( date('d') - 1) && date('m') == $mes)) {
                echo " id = 'dia_atual' ";
            } else {
                if (($diacorrente + 1) <= $numero_dias) {
                    if ($coluna < $diasemana && $linha == 0) {
                        echo " id = 'dia_branco' ";
                    } else {
                        echo " id = 'dia_comum' ";
                    }
                } else {
                    echo " ";
                }
            }

            $hoje = 'bgcolor = "#e4e4e4"';
            $cor = '';
            if (($diacorrente + 1) == date('d') && $mes == date('m') && $ano == date('Y')) {
                $hoje = 'bgcolor = "#ccc"';
                $cor = '<font color="#ff6600" size="3">';
            }

            echo ' ' . $hoje . ' align = "center" valign = "center"><center>';

            include "../controller/agenda.php";
            $tot = pesquisa_compromisso_dia($_SESSION['usuario_id'], str_pad($ano, 4, '0', 0) . str_pad($mes, 2, '0', 0) . str_pad(($diacorrente + 1), 2, '0', 0));

            $tem = $ftem = $tit = $sup = '';

            if ($tot > 0) {
                $tit = "title='$tot compromisso(s) nesse dia'";
                $tem = '<b>';
                $ftem = '</b>';
                $sup = "<sup>$tot</sup>";
            }

            if ($diacorrente + 1 <= $numero_dias) {
                if ($coluna < $diasemana && $linha == 0) {
                    echo " ";
                } else {
                    echo "<a href='agenda.php?mes=$mes&dia=" . ($diacorrente + 1) . "' " . $tit . " target='frmPrincipal'>" . $tem . "" . $cor . "" . ++$diacorrente . "" . $ftem . $sup . "</a>";
                }
            } else {
                break;
            }
            echo "</center></td>";
        }
        echo "</tr>";
    }

    echo "</table>";
    echo '<font size=1>Hoje é ' . DiaSemana(date('w')) . ', ' . date('d') . ' de ' . GetNomeMes(date('m')) . ' de ' . date('Y') . '</font>';
}

function MostreCalendarioCompleto($ano) {
    echo '<table align = "center">';
    $cont = 1;
    for ($j = 0; $j < 4; $j++) {
        echo "<tr>";
        for ($i = 0; $i < 3; $i++) {

            echo "<td>";
            MostreCalendario(($cont < 10 ) ? "0" . $cont : $cont, $ano);

            $cont++;
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>