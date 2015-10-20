<?php

date_default_timezone_set('America/Sao_Paulo');


$id = '';

$atendente = '';
$data = date('d/m/Y');
$hora = date('H:i');
$assunto = '';
$departamento = '';
$mensagem = '';
$avisar = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if (empty($id)) {
    $id = 'add';
}

include '../controller/usuario.php';

if ($id != 'add') {
    include '../controller/ligacao.php';

    $ligacao = json_decode(ligacao_carregar($id));

    $atendente = $ligacao->atendente;
    $data = data_decode($ligacao->data);
    $hora = $ligacao->hora;
    $assunto = $ligacao->assunto;
    $departamento = $ligacao->departamento;
    $mensagem = $ligacao->mensagem;
    $avisar = $ligacao->avisar;

    $atf = json_decode(usuario_carregar($ligacao->atendente));

    $xatendente = $atf->nome;
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
        <link href="css/ligacao.css" rel="stylesheet" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="../controller/js/calendar.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Home';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/ligacao_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Controle de Ligações Recebidas</h3>
                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('ligacoes.php', '_self');">
                    <?php
                    echo '<input type="submit"  value="Gravar" class="botao">';
                    if ($id != 'add') {
                        echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                        echo '                window.open(\'../controller/ligacao_exclui.php?id=' . $id . '\', \'_self\');';
                        echo '    }">';
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Registro de Ligação Recebida</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <?php
                    if ($id != 'add') {
                        echo '<fieldset>';
                        echo '  <div class="form-varchar">Aberto por';
                        echo '  <br><input type="text" name="atd" size="15"value="' . $xatendente . '" class="desativado">';
                        echo '  </div>';
                        echo '</fieldset>';
                    }
                    ?>
                    <div class="form-varchar">Momento da ligação
                        <br><input type="text" name="data"  class="datepicker"  id="data" size="10" maxlength="10" value="<?php echo $data; ?>" required="required" style="border: 1px solid red;"><input type="hour" name="hora" id="data" size="5" maxlength="5" value="<?php echo $hora; ?>" required="required" style="border: 1px solid red;">
                    </div>
                    <div class="form-varchar">Assunto
                        <br><input type="text" name="assunto" list="data_assunto" id="assunto" size="30" value="<?php echo $assunto; ?>">
                        <datalist id="data_assunto">
                            <option>Contato de Cliente Interessado</option>
                            <option>Comercial Vendas</option>
                            <option>Outros Assuntos</option>
                        </datalist>
                    </div>
                    <div class="form-varchar">Departamento
                        <br><input type="text" name="departamento" list="data_departamento" id="departamento" size="30" value="<?php echo $departamento; ?>">
                        <datalist id="data_departamento">
                            <option>Comercial</option>
                            <option>Financeiro</option>
                            <option>Diretoria</option>
                        </datalist>
                    </div>
                    <div class="form-varchar">Avisar
                        <br>
                        <?php
                        if ($id == 'add') {
                            $usus = json_decode(usuario_listar(" AND nome !='ADMIN' AND nome !='{$_SESSION['usuario_nome']}' ", '', ''));
                            echo '<select name="avisar" id="avisar">';
                            echo '<option></option>';
                            foreach ($usus as $id) {
                                $usu = json_decode(usuario_carregar($id));
                                $sel = '';
                                if ($avisar == $usu->id) {
                                    $sel = 'selected';
                                }
                                echo '<option value="' . $usu->id . '" ' . $sel . '>' . $usu->nome . '</option>';
                            }
                            echo '</select>';
                        } else {
                            $usu = json_decode(usuario_carregar($avisar));
                            echo '<input type="text" size="30" name="xavisar" id="xavisar" value="' . $usu->nome . '" readonly style="background: #ccc;">';
                            echo '<input type="hidden" name="avisar" id="avisar" value="' . $avisar . '">';
                        }
                        ?>
                    </div>
                    <div class="form-textarea">Mensagem
                        <br><textarea name="mensagem" id="mensagem" rows="10" cols="50"><?php echo $mensagem; ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>