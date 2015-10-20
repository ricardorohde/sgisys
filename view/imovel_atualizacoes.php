<?php
session_start();
$ref = $_GET['id'];
include 'func.php';
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
        <link href="css/cadastro.css" rel="stylesheet" />
        <script src="../controller/js/ficha.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div style="width: 100%;height: 50px;float: left;"></div>
        <input type="button"  value="Voltar" class="botao" onclick="window.open('cadastro.php?id=<?php echo $ref; ?>', 'frmPrincipal');">
        <input type="button" value="Novo" class="botao" onclick="window.open('imovel_atualizacoes_novo.php?id=<?php echo $ref; ?>', '_self');">
        <br>
        <br>
        <p>Solicitação de Atualizações da Ficha do Imóvel</p>
        <?php
        include '../controller/ficha.php';
        $aux = json_decode(ficha_listar($ref));
        if ($aux) {
            echo '<table width="100%">';
            echo '<tr>';
            echo '  <td bgcolor="#cdcdcd">Data</td>';
            echo '  <td bgcolor="#cdcdcd">Usuario</td>';
            echo '  <td bgcolor="#cdcdcd">Alteração</td>';
            echo '  <td bgcolor="#cdcdcd">Situação</td>';
            echo '  <td bgcolor="#cdcdcd">Baixar</td>';
            echo '</tr>';
            foreach ($aux as $id) {
                $fich = json_decode(ficha_carregar($id));
                echo '<tr>';
                echo '  <td>' . data_decode($fich->data) . '</td>';
                echo '  <td>' . $fich->solicitante . '</td>';
                echo '  <td>' . $fich->descricao . '</td>';
                echo '  <td>' . $fich->situacao . '</td>';
                if ($fich->situacao == 'Pendente') {
                    echo '  <td><center><a href="javascript: void(0);" onclick="baixa_ficha(' . $ref . ',' . $fich->id . ');"><img src="../img/seta_direita.png" width="18"></a></center></td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Nenhuma Alteração Solicitada.</p>';
        }
        ?>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>
