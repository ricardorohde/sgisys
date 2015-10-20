<?php
session_start();

include '../controller/funcoes.php';
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
        <link href="css/movimento.css" rel="stylesheet" />
        <!-- <script src="../controller/js/movimento.js"></script>-->
    </head>
    <body>
        <?php
        $m = 'Financeiro';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Movimento</h3>
            <div id="pesquisa">
            </div>
            <div id="mostrar">
            </div>
            <?php
            echo '<input type="button"  value="Novo" class="botao" onclick="window.open(\'movimento.php?id=add\', \'_self\');">';
            ?>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td onclick="window.open('movimentos.php?movimento_order=data', '_self');" style="cursor:pointer;">Data</td>
                    <td onclick="window.open('movimentos.php?movimento_order=tipo', '_self');" style="cursor:pointer;">Conta</td>
                    <td onclick="window.open('movimentos.php?movimento_order=nome', '_self');" style="cursor:pointer;">Nome</td>
                    <td onclick="window.open('movimentos.php?movimento_order=historico', '_self');" style="cursor:pointer;">Historico</td>
                    <td onclick="window.open('movimentos.php?movimento_order=valor', '_self');" style="cursor:pointer;">Valor</td>
                </tr>
                <?php
                include '../controller/movimento.php';
                //
                if (isset($_SESSION['movimento_where'])) {
                    $mvto_where = $_SESSION['movimento_where'];
                } else {
                    $mvto_where = '';
                }
                if (isset($_SESSION['movimento_order'])) {
                    $mvto_order = $_SESSION['movimento_order'];
                } else {
                    $mvto_order = 'ORDER BY data';
                }
                if (isset($_SESSION['movimento_asc'])) {
                    $mvto_asc = $_SESSION['movimento_asc'];
                } else {
                    $mvto_asc = 'DESC';
                }
                //
                if (isset($_GET['movimento_where'])) {
                    $mvto_where = $_GET['movimento_where'];
                }
                if (isset($_GET['movimento_pagina'])) {
                    $mvto_pagina = $_GET['movimento_pagina'];
                }
                if (isset($_GET['movimento_order'])) {
                    if (" ORDER BY " . $_GET['movimento_order'] == $mvto_order) {
                        if ($mvto_asc == 'ASC') {
                            $mvto_asc = 'DESC';
                        } else {
                            $mvto_asc = 'ASC';
                        }
                    }
                    $mvto_order = " ORDER BY " . $_GET['movimento_order'];
                }

                //
                if (empty($mvto_pagina)) {
                    $mvto_pagina = 1;
                }

                $_SESSION['movimento_where'] = $mvto_where;
                $_SESSION['movimento_order'] = $mvto_order;
                $_SESSION['movimento_asc'] = $mvto_asc;
                $_SESSION['movimento_pagina'] = $mvto_pagina;

                $i = (($mvto_pagina - 1) * 20);
                $mvto_rows = " LIMIT $i,20 ";

                $ret = json_decode(movimento_listar($mvto_where, '', ''));
                $tot = count($ret);

                $paginas = ceil($tot / 20);

                $ret = json_decode(movimento_listar($mvto_where, $mvto_order . ' ' . $mvto_asc, $mvto_rows));
                foreach ($ret as $id) {
                    $mvto = json_decode(movimento_carregar($id));
                    echo '<tr class="listagem-item" onclick="window.open(\'movimento.php?id=' . $mvto->id . '\',\'_self\');">';
                    echo '<td>' . data_decode($mvto->data) . '</td>';
                    echo '<td>' . $mvto->tipo . '</td>';
                    echo '<td>' . $mvto->nome . '</td>';
                    echo '<td>' . $mvto->historico . '</td>';
                    if ($mvto->sentido == 'Entrada') {
                        echo '<td align="right">R$ ' . us_br($mvto->valor) . '&nbsp;</td>';
                    } else {
                        echo '<td align="right">R$ (-' . us_br($mvto->valor) . ')&nbsp;</td>';
                    }
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)  :: Página ' . $mvto_pagina . ' de ' . $paginas . '</td></tr>';
                ?>
            </table>
            <center>
                <p>
                    <?php
                    if ($mvto_pagina > 1) {
                        echo '  [ <a href="movimento.php?movimento_pagina=1">|< Primeira</a> ]  ';
                    }
                    if ($mvto_pagina > 1) {
                        echo '  [ <a href="movimento.php?movimento_pagina=' . ($mvto_pagina - 1) . '"><< Voltar</a> ]  ';
                    }
                    if ($mvto_pagina < $paginas) {
                        echo '  [ <a href="movimento.php?movimento_pagina=' . ($mvto_pagina + 1) . '">Avançar >></a> ]  ';
                    }
                    if ($mvto_pagina < $paginas) {
                        echo '  [ <a href="movimento.php?movimento_pagina=' . $paginas . '">Última >|</a> ]  ';
                    }
                    ?>
                </p>
            </center>
        </div>
    </body>
</html>


