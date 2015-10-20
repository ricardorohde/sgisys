<?php
session_start();

include '../model/debug.php';

$id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="../view/css/fontes.css" rel="stylesheet" />
        <link href="../view/css/base.css" rel="stylesheet" />
        <link href="../view/css/forms.css" rel="stylesheet" />
        <link href="../view/css/fotos.css" rel="stylesheet" />
        <script src="../controller/js/fotos.js"></script>
    </head>
    <body style="background: #fff;">
        <br>
        <form action="../controller/cadastro_foto_grava.php" method="POST"> 
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
            include '../controller/imovel_foto.php';
            $ret = json_decode(imovel_foto_listar($id));
            $tot = count($ret);

            imovel_testa_fachada($id);

            echo 'Total ' . $tot . ' fotos enviadas de 30 fotos disponível por imóvel.';

            echo '  <div id="foto-grande"></div>';
            echo '<div class="galeria">';

            if (!empty($ret)) {
                $x = 0;
                foreach ($ret as $index) {
                    if ($x <= 30) {
                        $foto = json_decode(imovel_foto_carregar($index));
                        if (file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto->foto)) {
                            echo '<div class="foto-album"><center>';
                            echo '  <a href="javascript: void(0);" onclick="mostra_foto(\'../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto->foto . '\');"><div class="foto-thumb"><center><img src="../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto->foto . '" width="190"></center></div></a>';
                            if ($foto->fachada == 'S') {
                                $fachada = 'checked';
                            } else {
                                $fachada = '';
                            }
                            echo '  <br><input type="radio" name="fachada" value="' . $foto->id . '" ' . $fachada . ' onclick="grava_fachada(\'' . $foto->id . '\');"> Fachada';
                            echo '  <br><input type="checkbox" name="foto[]" value="' . $foto->foto . '"> Marcar';
                            echo '</center></div>';
                        }
                    } else {
                        imovel_foto_excluir($foto->foto);
                    }
                    $x++;
                }
                echo '<div style="clear: both; width: 100%; height: 20px;"></div>';
                echo '<p align="center"><input type="submit" name="bbb" value="Apagar Fotos Selecionadas"></p>';
            } else {
                echo '<p>Nenhuma foto enviada.';
            }
            echo '</div>';

            if ($tot < 30) {
                ?>

            </form>
            <div style="clear: both; width: 100%; height: 10px;"></div>
            <hr>
            <form action="../controller/cadastro_foto_upload1.php#topo" method="post" enctype="multipart/form-data" target="cadastro_foto">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="upload">
                    <img src="http://sgiplus.com.br/img/foto_recomenda.png" width="250">
                    <h2>Enviar Fotos - <?php echo (30 - $tot) . ' foto(s) pode ser enviada(s) neste imóvel.'; ?></h2>
                    <em>Enviar o arquivo .jpg (Recomendado 2048x1536px QXGA 3MP de até 2Mbytes Max): </em>
                    <br><input type="file" multiple="multiple" name="arquivo[]" id="fotos">
                    <br><input type="submit" value="enviar fotos">
                </div>
            </form>
            <?php
        } else {
            echo 'Já alcançado máximo permitido de fotos deste imóvel.';
        }
        ?>
        <br>
        <br>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>