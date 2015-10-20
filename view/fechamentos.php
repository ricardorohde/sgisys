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
        <link href="css/fechamento.css" rel="stylesheet" />
        <!-- <script src="../controller/js/fechamento.js"></script>-->
    </head>
    <body>
        <?php
        $m = 'Financeiro';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Fechamento de Vendas</h3>
            <div id="pesquisa">
            </div>
            <div id="mostrar">
            </div>
            <?php
            echo '<input type="button"  value="Novo" class="botao" onclick="window.open(\'fechamento.php?id=add\', \'_self\');">';
            ?>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td onclick="window.open('fechamentos.php?fechamento_order=data', '_self');" style="cursor:pointer;">Data</td>
                    <td onclick="window.open('fechamentos.php?fechamento_order=nome', '_self');" style="cursor:pointer;">Corretor</td>
                    <td onclick="window.open('fechamentos.php?fechamento_order=historico', '_self');" style="cursor:pointer;">Comprador</td>
                    <td onclick="window.open('fechamentos.php?fechamento_order=valor', '_self');" style="cursor:pointer;">Ref</td>
                    <td onclick="window.open('fechamentos.php?fechamento_order=valor', '_self');" style="cursor:pointer;">Endereço</td>
                </tr>
                <?php
                include '../controller/fechamento.php';
                //
                if (isset($_SESSION['fechamento_where'])) {
                    $fechto_where = $_SESSION['fechamento_where'];
                } else {
                    $fechto_where = '';
                }
                if (isset($_SESSION['fechamento_order'])) {
                    $fechto_order = $_SESSION['fechamento_order'];
                } else {
                    $fechto_order = 'ORDER BY data';
                }
                if (isset($_SESSION['fechamento_asc'])) {
                    $fechto_asc = $_SESSION['fechamento_asc'];
                } else {
                    $fechto_asc = 'DESC';
                }
                //
                if (isset($_GET['fechamento_where'])) {
                    $fechto_where = $_GET['fechamento_where'];
                }
                if (isset($_GET['fechamento_pagina'])) {
                    $fechto_pagina = $_GET['fechamento_pagina'];
                }
                if (isset($_GET['fechamento_order'])) {
                    if (" ORDER BY " . $_GET['fechamento_order'] == $fechto_order) {
                        if ($fechto_asc == 'ASC') {
                            $fechto_asc = 'DESC';
                        } else {
                            $fechto_asc = 'ASC';
                        }
                    }
                    $fechto_order = " ORDER BY " . $_GET['fechamento_order'];
                }

                //
                if (empty($fechto_pagina)) {
                    $fechto_pagina = 1;
                }

                $_SESSION['fechamento_where'] = $fechto_where;
                $_SESSION['fechamento_order'] = $fechto_order;
                $_SESSION['fechamento_asc'] = $fechto_asc;
                $_SESSION['fechamento_pagina'] = $fechto_pagina;

                $i = (($fechto_pagina - 1) * 20);
                $fechto_rows = " LIMIT $i,20 ";

                $ret = json_decode(fechamento_listar($fechto_where, '', ''));
                $tot = count($ret);

                $paginas = ceil($tot / 20);

                $ret = json_decode(fechamento_listar($fechto_where, $fechto_order . ' ' . $fechto_asc, $fechto_rows));
                foreach ($ret as $id) {
                    $fechto = json_decode(fechamento_carregar($id));
                    echo '<tr class="listagem-item" onclick="window.open(\'fechamento.php?id=' . $fechto->id . '\',\'_self\');">';
                    echo '<td>' . data_decode($fechto->data) . '</td>';
                    echo '<td>' . $fechto->corretor1 . '</td>';
                    echo '<td>' . $fechto->comprador . '</td>';
                    echo '<td>' . $fechto->ref . '</td>';
                    echo '<td>' . $fechto->endereco . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)  :: Página ' . $fechto_pagina . ' de ' . $paginas . '</td></tr>';
                ?>
            </table>
            <center>
                <p>
                    <?php
                    if ($fechto_pagina > 1) {
                        echo '  [ <a href="fechamento.php?fechamento_pagina=1">|< Primeira</a> ]  ';
                    }
                    if ($fechto_pagina > 1) {
                        echo '  [ <a href="fechamento.php?fechamento_pagina=' . ($fechto_pagina - 1) . '"><< Voltar</a> ]  ';
                    }
                    if ($fechto_pagina < $paginas) {
                        echo '  [ <a href="fechamento.php?fechamento_pagina=' . ($fechto_pagina + 1) . '">Avançar >></a> ]  ';
                    }
                    if ($fechto_pagina < $paginas) {
                        echo '  [ <a href="fechamento.php?fechamento_pagina=' . $paginas . '">Última >|</a> ]  ';
                    }
                    ?>
                </p>
            </center>
        </div>
    </body>
</html>


