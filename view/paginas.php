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
        <link href="css/pagina.css" rel="stylesheet" />
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Páginas de Conteúdo</h3>
            <input type="button"  value="Novo" class="botao" onclick="window.open('pagina.php?id=add', '_self');">
            <table width="100%">
                <tr class="listagem-titulo">
                    <td>ID</td>
                    <td>Opção no Menu</td>
                    <td>Título da Página</td>
                    <td>Página Destino</td>
                </tr>
                <?php
                include '../controller/pagina.php';
                $ret = json_decode(pagina_listar($pagina_where, $pagina_order, $pagina_rows));
                $tot = count($ret);
                foreach ($ret as $id) {
                    $pagina = json_decode(pagina_carregar($id));
                    echo '<tr class="listagem-item" onclick="window.open(\'pagina.php?id=' . $pagina->id . '\',\'_self\');">';
                    echo '<td>' . $pagina->id . '</td>';
                    echo '<td>' . $pagina->opcao . '</td>';
                    echo '<td>' . $pagina->titulo . '</td>';
                    echo '<td>' . $pagina->pagina . '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                ?>
            </table>
        </div>
    </body>
</html>


