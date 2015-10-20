<?php
session_start();

$id = '';
$agenda_titulo = '';

$assunto = '';
$de = '';
$para = '';
$agenda = '';
$envio = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if (empty($id)) {
    $id = 'add';
}
if ($id == 'add') {
    $agenda_titulo = 'Nova Mensagem';
} else {
    include '../controller/agenda.php';
    include '../controller/usuario.php';

    $agenda = json_decode(agenda_carregar($id));

    $assunto = $agenda->assunto;
    $usuario = json_decode(usuario_carregar($agenda->de));
    $de = $usuario->nome;
    $usuario = json_decode(usuario_carregar($agenda->para));
    $para = $usuario->nome;
    $envio = $agenda->envio;
    $xagenda = $agenda->agenda;
    $pasta = $agenda->pasta;

    $agenda_titulo = "Mensagem de: <strong>$de</strong> para: <strong>$para</strong> enviada em: <strong>$envio</strong>...";

    if ($agenda->confirmar == 'S') {
        agenda_confirmar($agenda->para, $agenda->de, "[[[ Assunto : $assunto / $agenda_titulo ]]]");
    }

    if ($agenda->para == $_SESSION['usuario_id']) {
        agenda_ler($id);
    }
}
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
        <link href="css/agenda.css" rel="stylesheet" />
        <script src="../controller/js/agenda.js"></script>
        <script src="ckeditor/ckeditor.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div id="conteudo" style="top: 0;width:90%;padding: 20px;">
            <div class="form-titulo"><?php echo $agenda_titulo; ?></div>
            <div style="width: 100%;height: 50px;float: left;"></div>
            <form action="../controller/agenda_grava.php" method="POST" name="form1" id="form1">
                <?php
                echo '  <input type="hidden" name="id" id="id" value="' . $id . '">';
                echo '  <input type="hidden" name="pasta" id="pasta" value="' . $pasta . '">';
                if ($id == 'add') {
                    echo '<input type="button" value="Enviar" class="botao" onclick="form1.submit();">';
                    echo '<div class="form-varchar">Para :';
                    echo '  <br><select name="para" id="para">';
                    include '../controller/tabela.php';
                    $tabela_vinculo = json_decode(tabela_vinculo('usuario'));
                    foreach ($tabela_vinculo as $campo_vinculo) {
                        if (!empty($campo_vinculo->nome)) {
                            $sel = '';
                            if ($para == $campo_vinculo->id) {
                                $sel = 'selected';
                            }
                            if ($_SESSION['usuario_id'] != $campo_vinculo->id) {
                                echo '      <option value="' . $campo_vinculo->id . '" ' . $sel . '>' . $campo_vinculo->nome . '</option>';
                            }
                        }
                    }
                    echo '  </select>';
                    echo '</div>';
                    echo '<div class="form-varchar">';
                    echo '  <br><input type="checkbox" name="confirmar" id="confirmar" value="S"> Receber Confirmar Leitura';
                    echo '</div>';
                    echo '<div class="form-varchar">Assunto :';
                    echo '  <br><input type="text" name="assunto" id="assunto" size="100" maxlength="100" required="required" style="border: 1px solid red;">';
                    echo '</div>';
                } else {
                    echo '  Mover ';
                    echo '<select name="mover" id="mover">';
                    echo '  <option></option>';
                    echo '  <option>Caixa de entrada</option>';
                    echo '  <option>Mensagens enviadas</option>';
                    echo '  <option>Arquivo</option>';
                    echo '  <option>Lixeira</option>';
                    echo '</select>';
                    echo ' / Encaminhar ou Responder ';
                    echo '  <select name="para" id="para">';
                    include '../controller/tabela.php';
                    $tabela_vinculo = json_decode(tabela_vinculo('usuario'));
                    foreach ($tabela_vinculo as $campo_vinculo) {
                        if (!empty($campo_vinculo->nome)) {
                            $sel = '';
                            if ($agenda->de == $campo_vinculo->id) {
                                $sel = 'selected';
                            }
                            if ($_SESSION['usuario_id'] != $campo_vinculo->id) {
                                echo '      <option value="' . $campo_vinculo->id . '" ' . $sel . '>' . $campo_vinculo->nome . '</option>';
                            }
                        }
                    }
                    echo '  </select>';
                    echo '  <input type="button" value="Enviar" class="botao" onclick="form1.submit();">';
                    if ($agenda->pasta == 'Lixeira') {
                        echo '<br><input type="button" value="Excluir" onclick="window.open(\'../controller/agenda_exclui.php?id=' . $id . '\',\'frmPrincipal\');">';
                    }
                    echo '<div class="form-varchar">Assunto :';
                    echo '  <br><input type="text" name="assunto" id="assunto" value="Enc: ' . $assunto . '" size="100" maxlength="100" required="required" style="border: 1px solid red;">';
                    echo '</div>';
                    $xagenda = "<br>$agenda_titulo<br>$xagenda";
                }
                ?>
                Mensagem:
                <br><textarea cols="100" id="agenda" name="agenda" rows="50"><?php echo $xagenda; ?></textarea>
                <script>
                    CKEDITOR.config.autoGrow_onStartup = true;
                    CKEDITOR.replace('agenda', {
                        toolbarGroups: [
                            {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                            {name: 'styles'},
                            {name: 'colors'}
                        ]
                    });
                    CKEDITOR.config.width = 750;
                    CKEDITOR.config.height = 480;
                </script>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>