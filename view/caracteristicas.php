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
        <link href="css/caracteristica.css" rel="stylesheet" />
    </head>
    <body>
        <?php
        $m = 'Tabelas';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Características de Imóveis</h3>
            <input type="button"  value="Novo" class="botao" onclick="window.open('caracteristica.php?id=add', '_self');">
            <table width="100%">
                <tr class="listagem-titulo">
                    <td>Característica</td>
                </tr>
                <?php
                include '../controller/caracteristica.php';
                $ret = json_decode(caracteristica_listar($caracteristica_where, $caracteristica_order, $caracteristica_rows));
                $tot = count($ret);
                foreach ($ret as $id) {
                    $caracteristica = json_decode(caracteristica_carregar($id));
                    echo '<tr class="listagem-item" onclick="window.open(\'caracteristica.php?id=' . $caracteristica->id . '\',\'_self\');">';
                    echo '<td>' . $caracteristica->nome . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                ?>
            </table>
        </div>
    </body>
</html>


