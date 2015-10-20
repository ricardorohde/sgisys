<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');
ini_set('register_globals', 'off');
ini_set('display_errors', 'on');

include 'func.php';

include '../controller/cadastro.php';
include '../controller/tipo_cadastro.php';
include '../controller/usuario_acesso.php';
include '../controller/usuario.php';
include '../controller/imovel_caracteristica.php';
include '../controller/caracteristica.php';
include '../controller/portal.php';

$usuario = json_decode(usuario_carregar($_SESSION['usuario_id']));

if ($usuario->acesso < 20) {
    echo '<script>alert("Apenas Gerente pode exportar.");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}


$tipo_cadastro = $_GET['tipo_cadastro'];

include '../model/cadastro.php';

$cadastro = new cadastro();

$tcad = json_decode(tipo_cadastro_carregar_id($tipo_cadastro));
$name_tipo_cadastro = $tcad->tipo;

$cadastro_where = $_SESSION['pesquisa'][$tipo_cadastro]['where'];
$cadastro_order = $_SESSION['pesquisa'][$tipo_cadastro]['order'];


$cadastro_rows = '';
$ret = json_decode(cadastro_listar($tipo_cadastro, $cadastro_where, $cadastro_order, $cadastro_rows));
$tot = count($ret);

$saida = '';

if ($tipo_cadastro != 'imovel') {

    include '../controller/tabela.php';
    $camps_tabela = json_decode(tabela_carregar($tipo_cadastro));

    $saida .= '<table border="1">';
    $saida .= ' <td colspan="' . (count($camps_tabela) - 2) . '">';
    $saida .= '     <b>Cadastro de ' . $name_tipo_cadastro . '</b> | Salvo por ' . $_SESSION['usuario_nome'] . ' em ' . date('d/m/Y H:i:s');
    $saida .= ' </td>';
    $saida .= '<tr>';

    foreach ($camps_tabela as $camp_tabela) {
        if ($camp_tabela->campo != 'id' && $camp_tabela->campo != 'foto' && !empty($camp_tabela)) {
            $saida .= '<td><b>';
            $saida .= $camp_tabela->campo_nome;
            $saida .= '</b></td>';
        }
    }

    $saida .= '</tr>';

    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        $reg = (array) $cad;

        $saida .= '<tr>';
        include '../controller/tabela.php';
        $camps_tabela = json_decode(tabela_carregar($tipo_cadastro));

        foreach ($camps_tabela as $camp_tabela) {
            if ($camp_tabela->campo != 'id' && $camp_tabela->campo != 'foto' && !empty($camp_tabela)) {
                $cont = $camp_tabela->campo;
                if (empty($camp_tabela->tabela_vinculo)) {
                    $dado = $reg[$cont];
                    if ($camp_tabela->campo_tipo == 'DATE') {
                        $dado = data_decode($dado);
                    } elseif ($camp_tabela->campo_tipo == 'DECIMAL') {
                        $dado = us_br($dado);
                    } elseif ($camp_tabela->campo_tipo == 'CHECKBOX') {
                        if ($dado == 'S') {
                            $dado = 'Sim';
                        } else {
                            $dado = 'Não';
                        }
                    }

                    $saida .= '<td>';
                    $saida .= $dado;
                    $saida .= '</td>';
                } else {
                    $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
                    $tmp = $camp_tabela->tabela_vinculo . '_consultar';
                    if (trim($usu->$tmp) == 'Sim' || $_SESSION['usuario_nome'] == 'ADMIN') {

                        $auxs = json_decode(cadastro_carregar($camp_tabela->tabela_vinculo, $reg[$cont]));
                        $aux = (array) $auxs;
                        $cont = $camp_tabela->tabela_texto;
                        $saida .= '<td>';
                        $saida .= $aux[$cont];
                        $saida .= '</td>';
                    }
                }
            }
        }
        $saida .= '</tr>';
    }
    $saida .= '<tr>';
    $saida .= ' <td colspan="' . (count($camps_tabela) - 2) . '">';
    $saida .= '     <b>Total ' . $tot . ' Registro(s).</b>';
    $saida .= ' </td>';
    $saida .= '</tr>';
    $saida .= '<table>';
} else {

    $camps_tabela = $cadastro->campo_imovel();

    $saida .= '<table border="1">';
    $saida .= ' <td colspan="' . (count($camps_tabela) + 4) . '">';
    $saida .= '     <b>Cadastro de ' . $name_tipo_cadastro . '</b> | Salvo por ' . $_SESSION['usuario_nome'] . ' em ' . date('d/m/Y H:i:s');
    $saida .= ' </td>';
    $saida .= '<tr>';

    $saida .= '<td><b>';
    $saida .= 'Ref';
    $saida .= '</b></td>';

    foreach ($camps_tabela as $camp => $value) {
        if ($camp == 'proprietario') {
            $saida .= '<td><b>';
            $saida .= 'Proprietário Nome';
            $saida .= '</b></td>';
            $saida .= '<td><b>';
            $saida .= 'Proprietário Fone';
            $saida .= '</b></td>';
            $saida .= '<td><b>';
            $saida .= 'Proprietário Email';
            $saida .= '</b></td>';
        } elseif ($camp != 'foto') {
            $saida .= '<td><b>';
            $saida .= $value;
            $saida .= '</b></td>';
        }
    }

    $saida .= '<td><b>';
    $saida .= 'Características';
    $saida .= '</b></td>';

    $saida .= '<td><b>';
    $saida .= 'Portais';
    $saida .= '</b></td>';

    $saida .= '</tr>';

    foreach ($ret as $id) {
        $cad = json_decode(cadastro_carregar($tipo_cadastro, $id));

        $reg = (array) $cad;
        $saida .= '<tr>';

        $saida .= '<td>';
        $saida .= $id;
        $saida .= '</td>';

        foreach ($camps_tabela as $camp => $value) {
            if ($camp == 'proprietario') {
                $dado = $reg[$camp];
                $props = json_decode(cadastro_carregar('proprietario', $dado));
                $prop = (array) $props;
                $saida .= '<td>';
                $saida .= $prop['nome'];
                $saida .= '</td>';
                $saida .= '<td>';
                $saida .= $prop['fone1'];
                $saida .= ' ' . $prop['fone2'];
                $saida .= ' ' . $prop['cel'];
                $saida .= '</td>';
                $saida .= '<td>';
                $saida .= $prop['email'];
                $saida .= '</td>';
            } elseif ($camp != 'foto') {
                $dado = $reg[$camp];

                if ($camp == 'exclusividade_ate' || $camp == 'data_captacao' || $camp == 'data_atualizacao' || $camp == 'cadastro_data' || $camp == 'alterado_data' || $camp == 'data_captacao_venda' || $camp == 'data_placa_venda' || $camp == 'data_captacao_locacao' || $camp == 'data_placa_locacao' || $camp == 'prev_fim_locacao' || $camp == 'data_lancamento') {
                    $dado = data_decode($dado);
                } elseif ($camp == 'valor_venda' || $camp == 'valor_locacao' || $camp == 'valor_metro' || $camp == 'valor_iptu' || $camp == 'valor_condominio') {
                    $dado = us_br($dado);
                }

                $saida .= '<td>';
                $saida .= $dado;
                $saida .= '</td>';
            }
        }

        //caracteristicas
        $aux = json_decode(cadastro_listar('imovel_caracteristica', " and ref='$id' ", $order, $rows));
        $saida .= '<td>';
        $x = 0;
        foreach ($aux as $car_id) {
            $carac = imovel_caracteristica_carregar_id($car_id);
            if ($x > 0) {
                $saida .= ', ';
            }
            $saida .= $carac;
            $x++;
        }
        $saida .= '</td>';

        //portais
        $aux = json_decode(cadastro_listar('publicar', " and ref='$id' ", $order, $rows));
        $saida .= '<td>';
        $x = 0;
        foreach ($aux as $por_id) {
            $carac = portal_carregar_id($por_id);
            if ($x > 0) {
                $saida .= ', ';
            }
            $saida .= $carac;
            $x++;
        }
        $saida .= '</td>';


        $saida .= '</tr>';
    }
    $saida .= '<tr>';
    $saida .= ' <td colspan="' . (count($camps_tabela) + 4) . '">';
    $saida .= '     <b>Total ' . $tot . ' Registro(s).</b>';
    $saida .= ' </td>';
    $saida .= '</tr>';
    $saida .= '<table>';
}

$nome = (mb_convert_case($name_tipo_cadastro, MB_CASE_UPPER));

$arquivo_xls = 'SGIFACIL_' . $_SESSION['cliente_id'] . '_' . $nome . "_" . date("d-m-Y_H.i.s") . ".xls";
header("Content-type: application/msexcel; charset=UTF-8");
header("Content-Disposition: attachment; filename=$arquivo_xls");

echo utf8_decode($saida);

$cadastro->ocorrencia($nome, 'Exportou para Excel ' . $tot . ' Registro(s) ', 'Gerou arquivo ' . $arquivo_xls, '', '');
