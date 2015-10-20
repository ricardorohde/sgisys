<?php
session_start();

include './func.php';
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
        <script src="../controller/js/creci.js"></script>
    </head>
    <body>
        <div id="conteudo">
            <?php
            include '../controller/portal.php';
            $por = json_decode(portal_carregar('CRECI'));
            //
            echo '<input type="hidden" name="usuario" id="usuario">';
            echo '<input type="hidden" name="senha_ftp" id="senha_ftp">';
            echo '<input type="hidden" name="usuario_ftp" id="usuario_ftp">';
            echo '<input type="hidden" name="servidor_ftp" id="servidor_ftp">';
            echo '<input type="hidden" name="enviar_endereco" id="enviar_endereco" value="N">';
            echo '<table style="border: 3px solid #ccc; margin: 10px;">';
            echo '    <tr>';
            echo '        <td colspan="4" bgcolor="#cccccc"><center>Configuração de Publicação</center></td>';
            echo '    </tr>';
            echo '    <tr>';
            echo '        <td bgcolor="#cccccc">CRECI</td>';
            echo '        <td bgcolor="#cccccc">senha</td>';
            echo '        <td bgcolor="#cccccc">URL Fotos</td>';
            echo '    </tr>';
            echo '    <tr>';
            echo '        <td bgcolor="#cccccc"><input type="text" name="codigo_cliente" id="codigo_cliente" size="45" value="' . $por->codigo_cliente . '" onchange="grava_config();"></td>';
            echo '        <td bgcolor="#cccccc"><input type="password" name="senha" id="senha" size="15" value="' . $por->senha . '" onchange="grava_config();"></td>';
            echo '        <td bgcolor="#cccccc"><input type="text" name="url" id="url" size="50" value="' . $por->url . '" onchange="grava_config();"></td>';
            echo '    </tr>';
//            echo '    <tr>';
//            echo '        <td colspan="4" bgcolor="#cccccc" oncha>';
//            echo '          <select name="enviar_endereco" id="enviar_endereco" onchange="grava_config();">';
//            if ($por->enviar_endereco == 'S') {
//                echo '<option value="N">Não</option>';
//                echo '<option value="S" selected>Sim</option>';
//            } else {
//                echo '<option value="N" selected>Não</option>';
//                echo '<option value="S">Sim</option>';
//            }
//            echo '          </select> Ao não enviar o endereço, poderá perder posição de destaque dependendo do portal.';
//            echo '        </td>';
//            echo '    </tr>';
            echo '</table>';
            //
            $ret = json_decode(publicar_listar('CRECI'));
            $tot = count($ret);
            echo '<h4>Total de Imóveis Marcados : ' . $tot;
            if (!empty($por->ultimo_envio)) {
                echo ' - Ultimo envio : ' . $por->ultimo_envio;
            }
            echo '</h4>';
            if ($ret) {
                echo '<input type="buttton" value="Publicar" class="botao" onclick="window.open(\'../controller/creci.php\',\'_self\');">';
                echo '<table width="95%" style="border: 3px solid #ccc; margin: 10px;">';
                echo '    <tr>';
                echo '        <td bgcolor="#cccccc">Ref</td>';
                echo '        <td bgcolor="#cccccc">Tipo</td>';
                echo '        <td bgcolor="#cccccc">Endereço</td>';
                echo '        <td bgcolor="#cccccc">Bairro</td>';
                echo '        <td bgcolor="#cccccc">Cidade</td>';
                echo '        <td bgcolor="#cccccc">Atualização</td>';
                echo '        <td bgcolor="#cccccc">Ult.Publicação</td>';
                //echo '        <td bgcolor="#cccccc">Tipo Anúncio</td>';
                echo ' </tr>';
                //
                include '../controller/cadastro.php';
                foreach ($ret as $id) {
                    $aux = json_decode(publicar_carregar($id));
                    $imo = json_decode(cadastro_carregar('imovel', $aux->ref));
                    echo '<tr>';
                    echo ' <td bgcolor = "#fff">' . $aux->ref . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->tipo_nome . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->logradouro . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->bairro . '</td>';
                    echo ' <td bgcolor = "#fff">' . $imo->cidade . '</td>';
                    echo ' <td bgcolor = "#fff">' . data_decode($imo->data_atualizacao) . '</td>';
                    echo ' <td bgcolor = "#fff">' . data_decode($aux->data_anuncio) . '</td>';
                    //echo ' <td bgcolor = "#fff">' . $aux->tipo_anuncio . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '&nbsp;Nenhum imóvel marcado.';
            }
            ?>
        </div>
    </body>
</html>