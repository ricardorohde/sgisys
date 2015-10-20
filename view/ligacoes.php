<?php

$estilo = '';

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
        <link href="css/ligacao.css" rel="stylesheet" />
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Home';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Controle de Ligações Recebidas</h3>
            <?php
            echo '<input type="button"  value="Novo" class="botao" onclick="window.open(\'ligacao.php?id=add\', \'_self\');">';
            ?>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td width="80">Data</td>
                    <td width="50">Hora</td>
                    <td width="150">Aberto por:</td>
                    <td>Assunto</td>
                    <td width="180">Departamento</td>
                    <td width="150">Avisar a:</td>
                </tr>
                <?php
                include '../controller/ligacao.php';
                //
                if (isset($_SESSION['ligacao_where'])) {
                    $ligacao_where = $_SESSION['ligacao_where'];
                } else {
                    $ligacao_where = '';
                }
                if (isset($_SESSION['ligacao_order'])) {
                    $ligacao_order = $_SESSION['ligacao_order'];
                } else {
                    $ligacao_order = 'id';
                }
                if (isset($_SESSION['ligacao_asc'])) {
                    $ligacao_asc = $_SESSION['ligacao_asc'];
                } else {
                    $ligacao_asc = 'DESC';
                }
                //
                if (isset($_GET['ligacao_where'])) {
                    $ligacao_where = $_GET['ligacao_where'];
                }
                if (isset($_GET['ligacao_pagina'])) {
                    $ligacao_pagina = $_GET['ligacao_pagina'];
                }
                if (isset($_GET['ligacao_order'])) {
                    if ($_GET['ligacao_order'] == $ligacao_order) {
                        if ($ligacao_asc == 'ASC') {
                            $ligacao_asc = 'DESC';
                        } else {
                            $ligacao_asc = 'ASC';
                        }
                    }
                    $ligacao_order = $_GET['ligacao_order'];
                }

                //
                if (empty($ligacao_pagina)) {
                    $ligacao_pagina = 1;
                }

                $_SESSION['ligacao_where'] = $ligacao_where;
                $_SESSION['ligacao_order'] = $ligacao_order;
                $_SESSION['ligacao_asc'] = $ligacao_asc;
                $_SESSION['ligacao_pagina'] = $ligacao_pagina;

                $i = (($ligacao_pagina - 1) * 20);
                $ligacao_rows = " LIMIT $i,20 ";

                $ret = json_decode(ligacao_listar($ligacao_where, '', ''));
                $tot = count($ret);

                $paginas = ceil($tot / 20);

                $ret = json_decode(ligacao_listar($ligacao_where, ' ORDER BY  ' . $ligacao_order . ' ' . $ligacao_asc, $ligacao_rows));
                foreach ($ret as $id) {
                    $ligacao = json_decode(ligacao_carregar($id));
                    echo '<tr class="listagem-item" style="' . $estilo . '"onclick="window.open(\'ligacao.php?id=' . $ligacao->id . '\',\'_self\');">';
                    echo '<td>' . data_decode($ligacao->data) . '</td>';
                    echo '<td>' . $ligacao->hora . '</td>';
                    $atf = json_decode(usuario_carregar($ligacao->atendente));
                    echo '<td>' . $atf->nome . '</td>';
                    echo '<td>' . $ligacao->assunto . '</td>';
                    echo '<td>' . $ligacao->departamento . '</td>';
                    $usu = json_decode(usuario_carregar($ligacao->avisar));
                    echo '<td>' . $usu->nome . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                ?>
            </table>
            <center>
                <p>
                    <?php
                    if ($ligacao_pagina > 1) {
                        echo '  [ <a href="ligacoes.php?ligacao_pagina=1">|< Primeira</a> ]  ';
                    }
                    if ($ligacao_pagina > 1) {
                        echo '  [ <a href="ligacoes.php?ligacao_pagina=' . ($ligacao_pagina - 1) . '"><< Voltar</a> ]  ';
                    }
                    if ($ligacao_pagina < $paginas) {
                        echo '  [ <a href="ligacoes.php?ligacao_pagina=' . ($ligacao_pagina + 1) . '">Avançar >></a> ]  ';
                    }
                    if ($ligacao_pagina < $paginas) {
                        echo '  [ <a href="ligacoes.php?ligacao_pagina=' . $paginas . '">Última >|</a> ]  ';
                    }
                    ?>
                </p>
            </center>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>