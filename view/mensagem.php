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
        <link href="css/mensagem.css" rel="stylesheet" />
        <script src="../controller/js/mensagem.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Home';
        include 'menu.php';
        $pasta = $_GET['pasta'];
        ?>
        <div id="conteudo">
            <h3>Mensagens do Usu&aacute;rio</h3>
            <div class="pastas">
                <ul><a href="javascript: void(0);" onclick="window.open('mensagem_ler.php?id=add', 'frmMensagem');">Nova Mensagem</a></ul>
                <ul>Pastas
                    <li><a href="mensagem.php?pasta=Caixa de entrada">Caixa de entrada</a></li>
                    <li><a href="mensagem.php?pasta=Mensagens enviadas">Mensagens enviadas</a></li>
                    <li><a href="mensagem.php?pasta=Arquivo">Arquivo</a></li>
                    <li><a href="mensagem.php?pasta=Lixeira">Lixeira</a></li>
                </ul>
            </div>
            <div class="mensagens">
                <h4>
                    <?php
                    echo $pasta . ' ';
                    if ($pasta == 'Lixeira') {
                        echo '<input type="button" value="limpar lixeira" onclick="if (confirm(\'Limpar??\')) {window.open(\'../controller/mensagem_limpar_lixeira.php\',\'_self\');}">';
                    }
                    ?>
                </h4>
                <table width="100%">
                    <tr class="listagem-titulo">
                        <?php
                        if ($pasta != 'Mensagens enviadas') {
                            echo '<td width="120">De:</td>';
                        }
                        if ($pasta != 'Caixa de entrada') {
                            echo '<td width="120">Para:</td>';
                        }
                        ?>
                        <td>Assunto:</td>
                        <td width="160">Enviado em:</td>
                        <td width="180">Situação:</td>
                    </tr>
                    <?php
                    include '../controller/mensagem.php';
                    $ret = json_decode(mensagem_listar($pasta, $_SESSION['usuario_id']));
                    $tot = count($ret);
                    foreach ($ret as $id) {
                        $mensagem = json_decode(mensagem_carregar($id));
                        $estilo = 'background-color: #eee;';
                        if ($mensagem->situacao == 'Pendente') {
                            $estilo = 'background-color: #ccc;font-weight: bold;';
                        }
                        echo '<tr class="listagem-item" style="' . $estilo . '"onclick="window.open(\'mensagem_ler.php?id=' . $mensagem->id . '\',\'frmMensagem\');">';
                        include '../controller/usuario.php';
                        if ($pasta != 'Mensagens enviadas') {
                            $usuario = json_decode(usuario_carregar($mensagem->de));
                            echo '<td>' . $usuario->nome . '</td>';
                        }
                        if ($pasta != 'Caixa de entrada') {
                            $usuario = json_decode(usuario_carregar($mensagem->para));
                            echo '<td>' . $usuario->nome . '</td>';
                        }
                        echo '<td>' . $mensagem->assunto . '</td>';
                        echo '<td>' . $mensagem->envio . '</td>';
                        echo '<td>' . $mensagem->situacao . '</td>';
                        echo '</tr>';
                    }
                    echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                    ?>
                </table>
                <hr>
                <div id="ler"><iframe name="frmMensagem" src="about:blanc" width="100%" height="500" frameborder="no"></iframe></div>
            </div>
        </div>
        <div id="fundo-negro" onclick="fecha_mensagem();"></div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>