<?php
session_start();
$ref = $_GET['id'];
include 'func.php';

include '../controller/cadastro.php';
$comprador = json_decode(cadastro_carregar('comprador', $ref));
$corretor = json_decode(cadastro_carregar('corretor', $comprador->corretor));
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
    <body onload="$('#carregando').fadeOut(1000);" style="color: #e6e6e6">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div style="width: 100%;height: 50px;float: left;"></div>
        <input type="button"  value="Voltar" class="botao" onclick="window.open('cadastro.php?id=<?php echo $ref; ?>', 'frmPrincipal');">
        <br>
        <br>
        <?php
        include '../controller/oferta.php';
        include '../controller/site_config.php';
        $site_config = json_decode(site_config_carregar());

        $saida = '';
        if (!empty($site_config->imagem_logo)) {
            $saida .= '<img src="http://sgiplus.com.br/site/fotos/' . $_SESSION['cliente_id'] . '/' . $site_config->imagem_logo . '">';
        }
        $saida .= 'Prezado(a) Sr.(a) ' . $comprador->nome . ', ';
        $saida .= '<br>Conforme suas preferências, segue algumas ofertas que enquadram nos seus requisitos: ';
        $saida .= oferta_qualidade_imovel($ref);
        //
        $aux = json_decode(oferta_listar_imovel($ref));
        if ($aux) {
            $tot = count($aux);
            $saida .= '<table width="100%" style="border: none;margin:10px;">';
            foreach ($aux as $id) {
                $imov = json_decode(oferta_carregar_imovel($id));
                $saida .= '<tr>';
                $saida .= '  <td bgcolor="#e6e6e6" style="padding: 0px;" width="180"><center>';
                if (!empty($site_config->url)) {
                    $saida .= '<a href="' . $site_config->url . '/index.php?q=Ref&id=' . $imov->id . '" target="_blank">';
                }
                $saida .= '  <img src="http://sgiplus.com.br/site/fotos/' . $_SESSION['cliente_id'] . '/' . $imov->foto . '" width="180" height="150">';
                if (!empty($site_config->url)) {
                    $saida .= '</a>';
                }
                $saida .= '  </center></td>';
                $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;">';
                $saida .= '  Referência : ' . $imov->id . '  ';
                $saida .= '  <br><center><strong>' . $imov->tipo_nome . ' em ' . $imov->bairro . '/' . $imov->cidade . '</strong></center>';
                $saida .= '  <br>' . $imov->descricao . '  ';
                $saida .= '  </td>';
                $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;" width="150">';
                if ($imov->dormitorio > 0) {
                    $saida .= '  <br>Dorms ' . $imov->dormitorio . '  ';
                }
                if ($imov->banheiro > 0) {
                    $saida .= '  <br>Banheiros ' . $imov->banheiro . '  ';
                }
                if ($imov->garagem > 0) {
                    $saida .= '  <br>Vagas ' . $imov->garagem . '  ';
                }
                if ($imov->area_util > 0) {
                    $saida .= '  <br>Area Util ' . $imov->area_util . 'm²  ';
                }
                $saida .= '  <p align="right">Venda R$' . us_br($imov->valor_venda) . '&nbsp;&nbsp;</p>';
                $saida .= '  </td>';
                $saida .= '</tr>';
            }
            $saida .= '</table>';

            $saida .= '<p>' . $tot . ' oferta(s) encontrada(s).</p>';

            $saida .= '<br>Atenciosamente,  ' . $corretor->nome;

            $email_envio = $site_config->email_envio;

            if (!empty($corretor->email)) {
                $email_envio = $corretor->email;
            }

            $_SESSION['email_envio'] = $email_envio;
            $_SESSION['email_mensagem'] = $saida;
            $_SESSION['email_para'] = $comprador->email;
            $_SESSION['email_assunto'] = 'Envio de Ofertas';

            echo '<iframe name="frmEmail" frameborder="no" scroll="yes" width="700" heigh="400" src="../controller/email.php"></iframe>';
        } else {
            echo '<p>Nenhum imóvel atende requisitos.</p>';
        }
        ?>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>
