<?php
session_start();

include '../controller/usuario.php';
include '../controller/ocorrencia.php';
include '../controller/cadastro.php';
include '../controller/imovel.php';
include '../controller/ligacao.php';
include '../controller/tipo_cadastro.php';
include '../controller/proposta.php';
include '../controller/portal.php';

$usu = new usuario();
$dados_usuario = $usu->carregar($_SESSION['usuario_id']);
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
        <link href="css/home.css" rel="stylesheet" />
        <meta http-equiv="refresh" content="60;URL=home.php">
    </head>
    <body style="font-size: 9pt;">
        <?php
        $m = 'Home';
        include 'menu.php';
        //
        include 'func.php';
        //
        if ($usu->imovel_alterar == 'Sim') {
            $ano = @date('Y');
            $mes = @date('m');
            $dia = @date('d');
            //
            $ano2 = $ano;
            //
            $mes2 = ($mes - 1);
            if ($mes2 < 1) {
                $mes2 = 12;
                $ano2--;
            }
            //
            $ano3 = $ano2;
            $mes3 = ($mes2 - 1);
            if ($mes3 < 1) {
                $mes3 = 12;
                $ano3--;
            }
            //
            $dia = str_pad($dia, 2, '0', 0);
            $mes = str_pad($mes, 2, '0', 0);
            $ano = str_pad($ano, 4, '0', 0);
            $dia2 = str_pad($dia2, 2, '0', 0);
            $mes2 = str_pad($mes2, 2, '0', 0);
            $ano2 = str_pad($ano2, 4, '0', 0);
            $dia3 = str_pad($dia3, 2, '0', 0);
            $mes3 = str_pad($mes3, 2, '0', 0);
            $ano3 = str_pad($ano3, 4, '0', 0);
            //
            //
            // 3 meses captacao
            //
            $data3i = $ano3 . str_pad($mes3, 2, '0', 0) . '01';
            $data3f = $ano3 . str_pad($mes3, 2, '0', 0) . '31';
            //
            $data2i = $ano2 . str_pad($mes2, 2, '0', 0) . '01';
            $data2f = $ano2 . str_pad($mes2, 2, '0', 0) . '31';
            //
            $data1i = $ano . $mes . '01';
            $data1f = $ano . $mes . '31';
            //
            $estat1 = imovel_estat_cap($data1i, $data1f);
            $estat2 = imovel_estat_cap($data2i, $data2f);
            $estat3 = imovel_estat_cap($data3i, $data3f);
            //
            $tot3 = '[\'' . substr(retorna_nome_mes($mes3 + 0), 0, 3) . '/' . $ano3 . '\',' . $estat3 . ']';
            $tot2 = '[\'' . substr(retorna_nome_mes($mes2 + 0), 0, 3) . '/' . $ano2 . '\',' . $estat2 . ']';
            $tot1 = '[\'' . substr(retorna_nome_mes($mes + 0), 0, 3) . '/' . $ano . '\',' . $estat1 . ']';
            //
            //
            $estat1 = ligacao_estat_1($data1i, $data1f);
            $estat2 = ligacao_estat_1($data2i, $data2f);
            $estat3 = ligacao_estat_1($data3i, '20000101');
            //
            $lig3 = '[\'' . substr(retorna_nome_mes($mes3 + 0), 0, 3) . '/' . $ano3 . '\',' . $estat3 . ']';
            $lig2 = '[\'' . substr(retorna_nome_mes($mes2 + 0), 0, 3) . '/' . $ano2 . '\',' . $estat2 . ']';
            $lig1 = '[\'' . substr(retorna_nome_mes($mes + 0), 0, 3) . '/' . $ano . '\',' . $estat1 . ']';
            //
            ?>
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart1);
                function drawChart1() {
                    var data = google.visualization.arrayToDataTable([
                        ['Mês', 'Total'],
    <?php echo $tot3; ?>,
    <?php echo $tot2; ?>,
    <?php echo $tot1; ?>
                    ]);

                    var options = {
                        title: 'Imóveis Captados',
                        vAxis: {title: 'Mês', titleTextStyle: {color: 'red'}}
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chart_div1'));
                    chart.draw(data, options);
                }

                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart2);

                function drawChart2() {
                    var data = google.visualization.arrayToDataTable([
                        ['Dia', 'Ligações'],
    <?php echo $lig3; ?>,
    <?php echo $lig2; ?>,
    <?php echo $lig1; ?>
                    ]);

                    var options = {
                        title: 'Ligações / Mês'
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
                    chart.draw(data, options);
                }

            </script>
    <?php
}
?>
        <table width="100%" border="0" class="tab-estats" align="center" style="font-size: 9pt;">
        <?php
        if ($_SESSION['cliente_id'] == '0000' && $dados_usuario['admin'] != 'S') {
            ?>
                <tr>
                    <td colspan="3" align="center">
    <?php
    echo '<div style="clear: both;width: 100%; height: 10px;"></div>';
    echo '<h3 style="width: 800px; margin: 2px; padding: 2px;">Suas Ocorrências Agendadas Hoje</h3>';


    $ret = json_decode(ocorrencia_listar(" WHERE agenda_data = " . date('Ymd') . " AND para='{$_SESSION['usuario_id']}' AND status!='Resolvido' ORDER BY agenda_hora ", "", ""));
    if ($ret) {
        $tot = 0;
        $ocor = '';
        echo '<ul class="home-ocorrencia">';
        foreach ($ret as $id) {
            $ocorrencia = json_decode(ocorrencia_carregar($id));
            echo '<li>';
            echo '<a href="cliente.php?id=' . $ocorrencia->ref . '&tipo_cadastro=cliente" target="_self">';
            $cliente = json_decode(cadastro_carregar('cliente', $ocorrencia->ref));
            echo $ocorrencia->agenda_hora . ' ' . $cliente->nome;
            echo '</a>';
            echo '</li>';
            $tot++;
        }
        echo '</ul>';
        echo $tot . ' ocorrência(s) hoje.';
    } else {
        echo 'Nenhuma ocorrência agendada hoje.';
    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
    <?php
    echo '<div style="clear: both;width: 100%; height: 10px;"></div>';
    echo '<h3 style="width: 800px; margin: 2px; padding: 2px;">Ocorrências Ativas</h3>';

    if ($dados_usuario['acesso'] < 90) {
        $whe = " AND para='{$_SESSION['usuario_id']}' ";
    } else {
        $whe = '';
    }

    $ret = json_decode(ocorrencia_listar(" WHERE agenda_data <= " . date('Ymd') . " AND status!='Resolvido' $whe ORDER BY status,para,ref,data,hora ", "", ""));
    if ($ret) {
        $tot = 0;
        $ocor = '';
        $ref = '';
        $ref2 = '';
        echo '<ul class="home-ocorrencia">';
        foreach ($ret as $id) {
            $ocorrencia = json_decode(ocorrencia_carregar($id));
            $para = json_decode(usuario_carregar($ocorrencia->para));
            if ($ref != $ocorrencia->status . $ocorrencia->para) {
                echo '<p>' . $ocorrencia->status . ' -> ' . $para->nome . '</p>';
                $ref = $ocorrencia->status . $ocorrencia->para;
            }
            echo '<li>';
            echo '<a href="cliente.php?id=' . $ocorrencia->ref . '&tipo_cadastro=cliente" target="_self">';
            $cliente = json_decode(cadastro_carregar('cliente', $ocorrencia->ref));
            echo ' (' . $ocorrencia->status . ') ';
            echo data_decode($ocorrencia->agenda_data) . ' ' . $ocorrencia->agenda_hora . ' cliente:' . $cliente->nome;
            echo '</a>';
            echo '</li>';
            $tot++;
        }
        echo '</ul>';
        echo $tot . ' ocorrência(s) pendente(s).';
    } else {
        echo 'Nenhuma ocorrência pendente.';
    }
    ?>
                    </td>
                </tr>
    <?php
} else {
    ?>
                <tr>
                    <td width="33%" align="center">
    <?php
    if ($usu->imovel_alterar == 'Sim') {
        echo'<div id="chart_div1" class="charts"></div>';
    }
    ?>
                    </td>
                    <td width="33%" align="center">
    <?php
    if ($usu->imovel_alterar == 'Sim') {
        echo'<div id="chart_div2" class="charts"></div>';
    }
    ?>
                    </td>
                    <td width="33%" align="center">
    <?php
    if ($usu->imovel_alterar == 'Sim') {
        ?>
                            <div class="charts">
                                <div style="width: 100%;height: 40px;"></div>
                                <h3>Imóveis Atualizados</h3>
        <?php
        //
        // atualizacao ultimos 30 dias
        //
                                $data1i = $ano2 . $mes2 . $dia;
        $data1f = $ano . $mes . $dia;
        //
        $data2i = $ano3 . $mes3 . $dia;
        $data2f = $ano2 . $mes2 . $dia;
        //
        $data3i = $ano3 . $mes3 . $dia;
        //
        $tot4 = imovel_estat_atu_maior($data1i);
        $tot5 = imovel_estat_atu($data2i, $data2f);
        $tot6 = imovel_estat_atu_menor($data3i);
        $tot7 = imovel_estat_atu_sem();
        //
        if ($usu->imovel_consultar == 'Sim') {

            $ret = json_decode(tipo_cadastro_listar());
            foreach ($ret as $id) {
                $tcad = json_decode(tipo_cadastro_carregar($id));
                if ($tcad->tabela == 'imovel') {
                    $id_cadastro = $id;
                }
                if ($tcad->tabela == 'proposta') {
                    $id_proposta = $id;
                }
            }
            ?>
                                    <ul>
                                        <li><font color="green">Últimos 30 dias</font> : 
            <?php
            if ($tot4 > 0) {
                echo '<a href="cadastros.php?id_cadastro=' . $id_cadastro . '&tabelas=Não&home=1&datai=' . $data1i . '&dataf=' . $data1f . '">' . $tot4 . ' ver+</a>';
            } else {
                echo '0';
            }
            ?>
                                        </li>
                                        <li><font color="orange">Desatualizados (entre 31 e 60 dias)</font> : 
            <?php
            if ($tot5 > 0) {
                echo '<a href="cadastros.php?id_cadastro=' . $id_cadastro . '&tabelas=Não&home=2&datai=' . $data2i . '&dataf=' . $data2f . '">' . $tot5 . ' ver+</a>';
            } else {
                echo '0';
            }
            ?>
                                        </li>
                                        <li><font color="red">Desatualizados (acima de 60 dias)</font> : 
            <?php
            if ($tot6 > 0) {
                echo '<a href="cadastros.php?id_cadastro=' . $id_cadastro . '&tabelas=Não&home=3&data=' . $data3i . '">' . $tot6 . ' ver+</a>';
            } else {
                echo '0';
            }
            ?>
                                        </li>
                                        <li><font color="brown">Sem data de atualização</font> : 
            <?php
            if ($tot7 > 0) {
                echo '<a href="cadastros.php?id_cadastro=' . $id_cadastro . '&tabelas=Não&home=4&data=">' . $tot7 . ' ver+</a>';
            } else {
                echo '0';
            }
            ?>
                                        </li>
                                    </ul>

            <?php
        }
        if ($usu->proposta_consultar == 'Sim') {
            echo '<h3>Propostas</h3>';

            $tot7 = proposta_contar('P');
            $tot8 = proposta_contar('E');
            ?>
                                    <ul>
                                        <li style="color: orange;">Pendentes : 
            <?php
            if ($tot7 > 0) {
                echo '<a href="cadastros.php?id_cadastro=' . $id_proposta . '&tabelas=Não&home=4">' . $tot7 . ' ver+</a>';
            } else {
                echo '0';
            }
            ?></li>
                                        <li style="color: green;">Em andamento : 
                                            <?php
                                            if ($tot8 > 0) {
                                                echo '<a href="cadastros.php?id_cadastro=' . $id_proposta . '&tabelas=Não&home=5">' . $tot8 . ' ver+</a>';
                                            } else {
                                                echo '0';
                                            }
                                            ?></li>
                                    </ul>
                                </div>
            <?php
        }
    }
    ?>
                    </td>
                </tr>
                <tr>
                    <td width="33%" align="center">
    <?php
    $usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));

    if ($usuario->acesso >= 20) {
        ?>
                            <div class="charts">
                                <div style="width: 100%;height: 10px;"></div>
                                <h3>Ultimos 10 Acessos</h3>
                                <table width="100%" style="font-size: 9pt;">
                                    <tr>
                                        <td><b>Usuário</b></td>
                                        <td><b>Data/Hora</b></td>
                                    </tr>
        <?php
        $logs = json_decode(cadastro_listar('log', " AND tipo='LOGIN' AND titulo='USUÁRIO ENTRANDO NO SISTEMA' AND usuario != 'ADMIN' ", " order by id DESC", ' LIMIT 0,10 '));
        foreach ($logs as $id) {
            $log = json_decode(cadastro_carregar('log', $id));
            echo '<tr>';
            echo '  <td>' . $log->usuario . '</td>';
            echo '  <td>' . my_data(substr($log->data_hora, 0, 12)) . ' ' . substr($log->data_hora, 11) . '</td>';
            echo '</tr>';
        }
        ?>
                                </table>
                            </div>
                                    <?php
                                }
                                ?>
                    </td>
                    <td width="33%" align="center">
                        <?php
                        $usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));

                        if ($usuario->acesso >= 20) {
                            ?>
                            <div class="charts">
                                <div style="width: 100%;height: 10px;"></div>
                                <h3>Ultimos Envios de Cargas para Portais</h3>
                                <table width="100%" style="font-size: 9pt;">
                                    <tr>
                                        <td><b>Portal</b></td>
                                        <td><b>Data/Hora</b></td>
                                        <td><b>Total</b></td>
                                    </tr>
        <?php
        $ports = json_decode(cadastro_listar('portal', "", " ", ' LIMIT 0,10 '));
        foreach ($ports as $id) {
            $port = json_decode(cadastro_carregar('portal', $id));
            $anuns = portal_total_envio($port->nome);
            echo '<tr>';
            echo '  <td>' . $port->nome_completo . '</td>';
            echo '  <td>' . $port->ultimo_envio . '</td>';
            echo '  <td>' . $anuns . '</td>';
            echo '</tr>';
        }
        ?>
                                </table>
                            </div>
                                    <?php
                                }
                                ?>
                    </td>
                    <td width="33%" align="center">
                        &nbsp;
                    </td>
                </tr>
    <?php
}
if ($dados_usuario['admin'] == 'S') {
    ?>
                <tr>
                    <td colspan="3" align="center">
                <?php
                echo '<div style="clear: both;width: 100%; height: 10px;"></div>';
                echo '<h3>Perfil Administrador : </h3>';
                $tipo_cadastro = new tipo_cadastro();
                $aux = $tipo_cadastro->listar_adm();
                foreach ($aux as $id) {
                    $tcad = json_decode(tipo_cadastro_carregar($id));
                    echo '    <div class="submenu-botao" onclick="window.open(\'cadastros.php?id_cadastro=' . $id . '\', \'_self\')">' . $tcad->tipo . '</div>';
                }
                ?>
                    </td>
                </tr>
                        <?php
                    }
                    ?>
        </table>
            <?php
            echo '<br>Atualizado : ' . date('H:i:s');
            ?>
    </body>
</html>


