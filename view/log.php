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
        <link href="css/log.css" rel="stylesheet" />
        <script src="../controller/js/log.js"></script>
    </head>
    <body>
        <?php
        $m = 'Tabelas';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Log do Sistema</h3>
            <div id="pesquisa">
            </div>
            <div id="mostrar">
            </div>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td onclick="window.open('log.php?log_order=data_hora', '_self');" style="cursor:pointer;">Data e Hora</td>
                    <td onclick="window.open('log.php?log_order=usuario', '_self');" style="cursor:pointer;">Usuário</td>
                    <td onclick="window.open('log.php?log_order=ip', '_self');" style="cursor:pointer;">IP</td>
                    <td onclick="window.open('log.php?log_order=tipo', '_self');" style="cursor:pointer;">Tipo</td>
                    <td onclick="window.open('log.php?log_order=titulo', '_self');" style="cursor:pointer;">Título</td>
                </tr>
                <?php
                include '../controller/log.php';
                //
                if (isset($_SESSION['log_where'])) {
                    $log_where = $_SESSION['log_where'];
                } else {
                    $log_where = '';
                }
                if (isset($_SESSION['log_order'])) {
                    $log_order = $_SESSION['log_order'];
                } else {
                    $log_order = 'ORDER BY data_hora';
                }
                if (isset($_SESSION['log_asc'])) {
                    $log_asc = $_SESSION['log_asc'];
                } else {
                    $log_asc = 'DESC';
                }
                //
                if (isset($_GET['log_where'])) {
                    $log_where = $_GET['log_where'];
                }
                if (isset($_GET['log_pagina'])) {
                    $log_pagina = $_GET['log_pagina'];
                }
                if (isset($_GET['log_order'])) {
                    if (" ORDER BY " . $_GET['log_order'] == $log_order) {
                        if ($log_asc == 'ASC') {
                            $log_asc = 'DESC';
                        } else {
                            $log_asc = 'ASC';
                        }
                    }
                    $log_order = " ORDER BY " . $_GET['log_order'];
                }
                
                //
                if (empty($log_pagina)) {
                    $log_pagina = 1;
                }

                $_SESSION['log_where'] = $log_where;
                $_SESSION['log_order'] = $log_order;
                $_SESSION['log_asc'] = $log_asc;
                $_SESSION['log_pagina'] = $log_pagina;

                $i = (($log_pagina - 1) * 20);
                $log_rows = " LIMIT $i,20 ";

                $ret = json_decode(log_listar($log_where, '', ''));
                $tot = count($ret);

                $paginas = ceil($tot / 20);
                
                $ret = json_decode(log_listar($log_where, $log_order . ' ' . $log_asc, $log_rows));
                foreach ($ret as $id) {
                    $log = json_decode(log_carregar($id));
                    echo '<tr class="listagem-item" onclick="log_mostrar(\'' . $log->id . '\');">';
                    echo '<td>' . $log->data_hora . '</td>';
                    echo '<td>' . $log->usuario . '</td>';
                    echo '<td>' . $log->ip . '</td>';
                    echo '<td>' . $log->tipo . '</td>';
                    echo '<td>' . $log->titulo . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)  :: Página ' . $log_pagina . ' de ' . $paginas . '</td></tr>';
                ?>
            </table>
            <center>
                <p>
                    <?php
                    if ($log_pagina > 1) {
                        echo '  [ <a href="log.php?log_pagina=1">|< Primeira</a> ]  ';
                    }
                    if ($log_pagina > 1) {
                        echo '  [ <a href="log.php?log_pagina=' . ($log_pagina - 1) . '"><< Voltar</a> ]  ';
                    }
                    if ($log_pagina < $paginas) {
                        echo '  [ <a href="log.php?log_pagina=' . ($log_pagina + 1) . '">Avançar >></a> ]  ';
                    }
                    if ($log_pagina < $paginas) {
                        echo '  [ <a href="log.php?log_pagina=' . $paginas . '">Última >|</a> ]  ';
                    }
                    ?>
                </p>
            </center>
        </div>
    </body>
</html>


