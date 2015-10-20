<?php
session_start();
$ref = $_GET['id'];
include 'func.php';
include '../controller/cadastro.php';
$comprador = json_decode(cadastro_carregar('comprador', $ref));
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
        <script src="../controller/js/oferta.js"></script> 
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div style="width: 100%;height: 50px;float: left;"></div>
        <input type="button"  value="Voltar" class="botao" onclick="window.open('cadastro.php?id=<?php echo $ref; ?>', 'frmPrincipal');">
        <?php
        include '../controller/oferta.php';
        echo oferta_qualidade_imovel($ref);
        //
        $aux = json_decode(oferta_listar_imovel($ref));
        
        if ($aux) {
            $tot = count($aux);
            if (!empty($comprador->email) && $tot > 0) {
                echo '<input type="button" value="Enviar" class="botao" onclick="window.open(\'enviar_ofertas.php?id=' . $ref . '\', \'_self\');">';
            } else {
                echo 'Email do comprador ainda não gravado.';
            }
            echo '<br>';
            echo '<br>(Apenas imóveis disponíveis no site)';
            echo '<br>';


            echo '<table width="100%">';
            echo '<tr>';
            echo '  <td bgcolor="#cdcdcd">Ref</td>';
            echo '  <td bgcolor="#cdcdcd">Tipo</td>';
            echo '  <td bgcolor="#cdcdcd">Endereço</td>';
            echo '  <td bgcolor="#cdcdcd">Bairro</td>';
            echo '  <td bgcolor="#cdcdcd">Cidade</td>';
            echo '  <td  align="right" bgcolor="#cdcdcd">Valor</td>';
            echo '</tr>';
            foreach ($aux as $id) {
                $imov = json_decode(oferta_carregar_imovel($id));
                echo '<tr>';
                echo '  <td bgcolor="#fff">' . $id . '</td>';
                echo '  <td bgcolor="#fff">' . $imov->tipo_nome . '</td>';
                echo '  <td bgcolor="#fff">' . $imov->logradouro . '</td>';
                echo '  <td bgcolor="#fff">' . $imov->bairro . '</td>';
                echo '  <td bgcolor="#fff">' . $imov->cidade . '</td>';
                echo '  <td bgcolor="#fff" align="right">' . us_br($imov->valor_venda) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            if ($tot >= 10) {
                echo '<p>No máximo 20 ofertas são enviadas.</p>';
            } else {
                echo '<p>' . $tot . ' oferta(s) encontrada(s).</p>';
            }
        } else {
            echo '<p>Nenhum imóvel atende requisitos.</p>';
        }
        ?>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>
