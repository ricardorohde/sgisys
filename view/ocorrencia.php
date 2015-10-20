<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');


if (!isset($_SESSION['ocorrencia_ref'])) {
    die('Ref do Cliente não definida.');
}

$id = '';
$data = date('d/m/Y');
$hora = date('H:i');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
if (empty($id)) {
    $id = 'add';
}

include '../controller/usuario.php';
include '../controller/ocorrencia.php';
$ocorrencia = json_decode(ocorrencia_carregar($id));

if ($id != 'add') {
    $data = data_decode($ocorrencia->data);
    $hora = $ocorrencia->hora;
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
        <link href="css/ocorrencia.css" rel="stylesheet" />
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="../controller/js/calendar.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);"  style="width: 920px !important;">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <!--        <div id="conteudo" style="top:0;"> -->
        <form action="../controller/ocorrencia_grava.php" method="POST" name="form1" id="form1" target="frmOcorrs" style="width: 910px !important;">
            <div class="botoes-form" style="top:0;margin-left: 10px;left: 0;">
                <input type="button"  value="Nova" class="botao" onclick="window.open('ocorrencia.php?id=add', '_self');">
                <?php
                echo '<input type="submit"  value="Gravar" class="botao">';
                if ($id != 'add') {
                    echo '<input type="button" value="Excluir" class="botao" onclick="if (confirm(\'Deseja Realmente Excluir ???\'    )) {';
                    echo '                window.open(\'../controller/ocorrencia_exclui.php?id=' . $id . '\', \'_self\');';
                    echo '    }">';
                }
                ?>
            </div>
            <div class="form-detalhe" id="form-detalhe" style="width: 900px !important;top: 40px; width: auto;margin-left: 20px;;left: 0;">
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                <div class="form-varchar">Data
                    <br><input type="text" name="data"  class="datepicker"  id="data" size="10" maxlength="10" value="<?php echo $data; ?>" required="required" style="border: 1px solid red;">
                </div>
                <div class="form-varchar">Hora
                    <br><input type="hour" list="data_hora" name="hora" id="hora" size="5" maxlength="5" value="<?php echo $hora; ?>" required="required" style="border: 1px solid red;">
                </div>
                <datalist name="data_hora" id="data_hora">
                    <option>08:00</option>
                    <option>08:30</option>
                    <option>09:00</option>
                    <option>09:30</option>
                    <option>10:00</option>
                    <option>10:30</option>
                    <option>11:00</option>
                    <option>11:30</option>
                    <option>12:00</option>
                    <option>12:30</option>
                    <option>13:00</option>
                    <option>13:30</option>
                    <option>14:00</option>
                    <option>14:30</option>
                    <option>15:00</option>
                    <option>15:30</option>
                    <option>16:00</option>
                    <option>16:30</option>
                    <option>17:00</option>
                    <option>17:30</option>
                    <option>18:00</option>
                    <option>18:30</option>
                </datalist>
                <div class="form-varchar">Tipo
                    <?php
                    $tmp = $ocorrencia->tipo;
                    $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = '';
                    if ($tmp == 'Ocorrência') {
                        $sel1 = 'selected';
                    }
                    if ($tmp == 'Pós-Venda') {
                        $sel2 = 'selected';
                    }
                    if ($tmp == 'Treinamento') {
                        $sel3 = 'selected';
                    }
                    if ($tmp == 'Instalação') {
                        $sel4 = 'selected';
                    }
                    if ($tmp == 'Demonstração') {
                        $sel5 = 'selected';
                    }
                    if ($tmp == 'Suporte') {
                        $sel6 = 'selected';
                    }
                    if ($tmp == 'Atualização') {
                        $sel7 = 'selected';
                    }
                    ?>
                    <br><select name="tipo" id="tipo">
                        <option <?php echo $sel1; ?>>Ocorrência</option>
                        <option <?php echo $sel2; ?>>Pós-Venda</option>
                        <option <?php echo $sel3; ?>>Treinamento</option>
                        <option <?php echo $sel4; ?>>Instalação</option>
                        <option <?php echo $sel5; ?>>Demonstração</option>
                        <option <?php echo $sel6; ?>>Suporte</option>
                        <option <?php echo $sel7; ?>>Atualização</option>
                    </select>
                </div>
                <div class="form-varchar">De:
                    <br>
                    <?php
                    $usus = json_decode(usuario_listar(" AND nome !='ADMIN' ", '', ''));
                    echo '<select name="de" id="de">';
                    foreach ($usus as $id) {
                        $usu = json_decode(usuario_carregar($id));
                        $sel = '';
                        if (!empty($ocorrencia->de) && $ocorrencia->de == $usu->id) {
                            $sel = 'selected';
                        }
                        if (empty($ocorrencia->de) && $_SESSION['usuario_id'] == $usu->id) {
                            $sel = 'selected';
                        }
                        echo '<option value="' . $usu->id . '" ' . $sel . '>' . $usu->nome . '</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                <div class="form-varchar">Para:
                    <br>
                    <?php
                    $usus = json_decode(usuario_listar(" AND nome !='ADMIN' ", '', ''));
                    echo '<select name="para" id="para" required="required">';
                    echo '<option></option>';
                    foreach ($usus as $id) {
                        $usu = json_decode(usuario_carregar($id));
                        $sel = '';
                        if ($ocorrencia->para == $usu->id) {
                            $sel = 'selected';
                        }
                        echo '<option value="' . $usu->id . '" ' . $sel . '>' . $usu->nome . '</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                <div class="form-textarea">Histórico
                    <br><textarea name="historico" id="historico" rows="5" cols="45"><?php echo $ocorrencia->historico; ?></textarea>
                </div>
                <div class="form-varchar">Status
                    <?php
                    $tmp = $ocorrencia->status;
                    $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = '';
                    if ($tmp == 'Pendente') {
                        $sel1 = 'selected';
                    }
                    if ($tmp == 'Resolvido') {
                        $sel2 = 'selected';
                    }
                    if ($tmp == 'Andamento') {
                        $sel3 = 'selected';
                    }
                    ?>
                    <br><select name="status" id="status">
                        <option <?php echo $sel1; ?>>Pendente</option>
                        <option <?php echo $sel2; ?>>Resolvido</option>
                        <option <?php echo $sel3; ?>>Andamento</option>
                    </select>
                </div>
                <div class="form-varchar">Avisar qdo Resolver ?
                    <?php
                    $tmp = $ocorrencia->avisar_resolvido;
                    $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = '';
                    if ($tmp == 'Sim') {
                        $sel1 = 'selected';
                    }
                    if ($tmp == 'Não') {
                        $sel2 = 'selected';
                    }
                    ?>
                    <br><select name="avisar_resolvido" id="avisar_resolvido">
                        <option <?php echo $sel1; ?>>Sim</option>
                        <option <?php echo $sel2; ?>>Não</option>
                    </select>
                </div>
                <div style="clear: both;width: 700px;height: 20px;"></div>
                <fieldset>
                    <p>Agendamento</p>
                    <div class="form-varchar">Data
                        <br><input type="text" name="agenda_data"  class="datepicker"  id="agenda_data" size="10" maxlength="10" value="<?php echo data_decode($ocorrencia->agenda_data); ?>">
                    </div>
                    <div class="form-varchar">Hora
                        <br><input type="hour" name="agenda_hora" list="data_hora" id="agenda_hora" size="5" maxlength="5" value="<?php echo $ocorrencia->agenda_hora; ?>">
                    </div>
                    <fieldset>
                        <div class="form-varchar">Lembrar em:
                            <br><input type="text" name="avisar_data"  class="datepicker"  id="avisar_data" size="10" maxlength="10" value="<?php echo data_decode($ocorrencia->avisar_data); ?>">
                        </div>
                        <div class="form-varchar">Hora
                            <br><input type="hour" name="avisar_hora" list="data_hora" id="avisar_hora" size="5" maxlength="5" value="<?php echo $ocorrencia->avisar_hora; ?>">
                        </div>
                        <div class="form-varchar">Avisar Email
                            <br><input type="text" name="avisar_email" id="avisar_email" size="50" value="<?php echo $ocorrencia->avisar_email; ?>">
                        </div>
                    </fieldset>
                </fieldset>

            </div>
        </form>
        <!--</div>-->
    </body>
</html>