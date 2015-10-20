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
        <script type="text/javascript" src="../controller/js/pagina_home.js"></script>
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';
        include '../controller/site_config.php';
        $site_config = json_decode(site_config_carregar());
        ?>
        <div id="conteudo">
            <h3>Banco de Imagens</h3>
            <?php
            echo '<input type="button"  value="Upload" class="botao" onclick="window.open(\'site_imagem_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');">';
            if (!empty($site_config->servidor_ftp) && !empty($site_config->usuario_ftp) && !empty($site_config->senha_ftp)) {
                echo '<input type="button" value="Atualizar FTP" class="botao" onclick="atualiza_ftp();">';
            }
            ?>
            <style>
                .thumb {
                    width: 200px;
                    height: 100px;
                    border: 2px solid #ccc;
                    overflow: hidden;
                    background: white;
                    margin: 5px;
                }
            </style>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td width="50"><center>DEL</center></td>
                <td>ID</td>
                <td>Nome</td>
                <td><center>Dims</center></td>
                <td>Thumb</td>
                </tr>
                <?php
                include '../controller/site_imagem.php';
                $ret = json_decode(site_imagem_listar());
                $tot = count($ret);
                foreach ($ret as $id) {
                    $site_imagem = json_decode(site_imagem_carregar($id));
                    $imgn = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $site_imagem->img;
                    if (file_exists($imgn)) {
                        $dims = getimagesize($imgn);
                        echo '<tr>';
                        echo '<td><center><a href="../controller/imagem_exclui.php?id=' . $site_imagem->id . '"><img src="img/Button Close-01.png" height="20"></a></center></td>';
                        echo '<td>' . $site_imagem->id . '</td>';
                        echo '<td>' . $site_imagem->nome . '</td>';
                        echo '<td><center>' . $dims[0] . 'x' . $dims[1] . '</center></td>';
                        if ($dims[0] > $dims[1]) {
                            $wh = ' height="200" ';
                        } else {
                            $wh = ' width="200" ';
                        }
                        echo '<td><div class="thumb"><a href="javascript: void(0);" onclick="window.open(\'' . $imgn . '\',\'_blank\',\'width=' . $dims[0] . ',height=' . $dims[1] . ',statusbar=no,address=no\')"><img src="' . $imgn . '" ' . $wh . '></a></thumb></td>';
                        echo '</tr>';
                    } else {
                        echo '<tr>';
                        echo '<td colspan="5">';
                        //site_imagem_excluir($site_imagem->id);
                        echo $imgn . ' Não encontrada.';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                ?>
            </table>
            <?php
            echo '<input type="button" value="Excluir Todas" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
            echo '                window.open(\'../controller/imagens_exclui.php?id=' . $id . '\', \'_self\');';
            echo '    }">';
            ?>
        </div>
    </body>
</html>


