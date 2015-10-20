<?php
session_start();

if (isset($_GET['ref'])) {
    $_SESSION['ocorrencia_ref'] = $_GET['ref'];
}

if (isset($_SESSION['ocorrencia_ref'])) {
    $ref = $_SESSION['ocorrencia_ref'];
} else {
    die('Ref do Cliente não definida.');
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
        <link href="css/ocorrencia.css" rel="stylesheet" />
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div id="conteudo" style="top:0;">
            <iframe name="frmOcorr" src="ocorrencia.php?id=add" width="98%" height="460px" frameborder="no" scrollbars="no"></iframe>
            <table width="99%">
                <tr class="listagem-titulo">
                    <td>Data e Hora</td>
                    <td>Tipo</td>
                    <td>de:</td>
                    <td>para:</td>
                    <td>Histórico</td>
                    <td>Agendado</td>
                    <td>Status</td>

                </tr>
                <?php
                include '../controller/ocorrencia.php';
                include '../controller/usuario.php';
                //
                if (isset($_SESSION['ocorrencia_where'])) {
                    $ocorrencia_where = $_SESSION['ocorrencia_where'];
                } else {
                    $ocorrencia_where = '';
                }

                if (empty($ocorrencia_where) || isset($_GET['ref'])) {
                    $ocorrencia_where = " WHERE ref='{$_SESSION['ocorrencia_ref']}' ";
                }

                if (isset($_SESSION['ocorrencia_order'])) {
                    $ocorrencia_order = $_SESSION['ocorrencia_order'];
                } else {
                    $ocorrencia_order = 'id';
                }
                if (isset($_SESSION['ocorrencia_asc'])) {
                    $ocorrencia_asc = $_SESSION['ocorrencia_asc'];
                } else {
                    $ocorrencia_asc = 'DESC';
                }
                //
                if (isset($_GET['ocorrencia_where'])) {
                    $ocorrencia_where = $_GET['ocorrencia_where'];
                }
                if (isset($_GET['ocorrencia_pagina'])) {
                    $ocorrencia_pagina = $_GET['ocorrencia_pagina'];
                }
                if (isset($_GET['ocorrencia_order'])) {
                    if ($_GET['ocorrencia_order'] == $ocorrencia_order) {
                        if ($ocorrencia_asc == 'ASC') {
                            $ocorrencia_asc = 'DESC';
                        } else {
                            $ocorrencia_asc = 'ASC';
                        }
                    }
                    $ocorrencia_order = $_GET['ocorrencia_order'];
                }

                //
                if (empty($ocorrencia_pagina)) {
                    $ocorrencia_pagina = 1;
                }

//                if ($dados_usuario['acesso'] < 90) {
//                    $whe = " AND para='{$_SESSION['usuario_id']}' ";
//                } else {
//                    $whe = '';
//                }

                $_SESSION['ocorrencia_where'] = $ocorrencia_where;
                $_SESSION['ocorrencia_order'] = $ocorrencia_order;
                $_SESSION['ocorrencia_asc'] = $ocorrencia_asc;
                $_SESSION['ocorrencia_pagina'] = $ocorrencia_pagina;

                $i = (($ocorrencia_pagina - 1) * 10);
                $ocorrencia_rows = " LIMIT $i,10 ";

                $ret = json_decode(ocorrencia_listar($ocorrencia_where, '', ''));
                $tot = count($ret);

                $paginas = ceil($tot / 10);

                $ret = json_decode(ocorrencia_listar($ocorrencia_where . $whe, ' ORDER BY  ' . $ocorrencia_order . ' ' . $ocorrencia_asc, $ocorrencia_rows));
                foreach ($ret as $id) {
                    $ocorrencia = json_decode(ocorrencia_carregar($id));

                    $estilo = 'background: #ccc;';
                    if ($ocorrencia->status == 'Pendente') {
                        $estilo = 'background: #ebfe6f;';
                    } elseif ($ocorrencia->status == 'Andamento') {
                        $estilo = 'background: #befc91;';
                    }

                    echo '<tr class="listagem-item" style="' . $estilo . '" onclick="window.open(\'ocorrencia.php?id=' . $ocorrencia->id . '\',\'frmOcorr\');">';
                    echo '<td>' . data_decode($ocorrencia->data) . ' as ' . $ocorrencia->hora . '</td>';
                    echo '<td>' . $ocorrencia->tipo . '</td>';
                    $de = json_decode(usuario_carregar($ocorrencia->de));
                    echo '<td>' . $de->nome . '</td>';
                    $para = json_decode(usuario_carregar($ocorrencia->para));
                    if ($ocorrencia->para == $_SESSION['usuario_id']) {
                        $para_quem = '<b><font color="red">' . $para->nome . '</font></b>';
                    } else {
                        $para_quem = $para->nome;
                    }
                    echo '<td>' . $para_quem . '</td>';
                    echo '<td>' . $ocorrencia->historico . '</td>';
                    if (!empty($ocorrencia->agenda_data) && !empty($ocorrencia->agenda_hora)) {
                        $as = ' as ';
                    } else {
                        $as = '';
                    }
                    echo '<td>' . data_decode($ocorrencia->agenda_data) . $as . $ocorrencia->agenda_hora . '</td>';
                    echo '<td>' . $ocorrencia->status . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="7">' . $tot . ' registro(s) - Ref: ' . $_SESSION['ocorrencia_ref'] . '</td></tr>';
                ?>
            </table>
            <center>
                <p>
                    <?php
                    if ($ocorrencia_pagina > 1) {
                        echo '  [ <a href="ocorrencias.php?ocorrencia_pagina=1">|< Primeira</a> ]  ';
                    }
                    if ($ocorrencia_pagina > 1) {
                        echo '  [ <a href="ocorrencias.php?ocorrencia_pagina=' . ($ocorrencia_pagina - 1) . '"><< Voltar</a> ]  ';
                    }
                    if ($ocorrencia_pagina < $paginas) {
                        echo '  [ <a href="ocorrencias.php?ocorrencia_pagina=' . ($ocorrencia_pagina + 1) . '">Avançar >></a> ]  ';
                    }
                    if ($ocorrencia_pagina < $paginas) {
                        echo '  [ <a href="ocorrencias.php?ocorrencia_pagina=' . $paginas . '">Última >|</a> ]  ';
                    }
                    ?>
                </p>
            </center>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>