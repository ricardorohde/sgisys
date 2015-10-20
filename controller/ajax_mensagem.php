<?php

session_start();
ini_set('display_errors', 'on');
ini_set('max_execution_time', '600');
set_time_limit(600);

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    destroy();
}

include 'usuario.php';
$row = json_decode(usuario_carregar($_SESSION['usuario_id']));

if (((date('w') >= 1 and date('w') <= 5) and ( (str_replace(':', '', $row->seg_sex_hi) > date('Hi')) || (str_replace(':', '', $row->seg_sex_hf) < date('Hi')) )) ||
        (date('w') == 6 and ( (str_replace(':', '', $row->sab_hi) > date('Hi')) || (str_replace(':', '', $row->sab_hf) < date('Hi')) )) ||
        (date('w') == 0 and ( (str_replace(':', '', $row->dom_hi) > date('Hi')) || (str_replace(':', '', $row->dom_hf) < date('Hi')) )) ||
        ((!empty($row->ip1) || !empty($row->ip2)) && ($row->ip1 != $_SERVER['REMOTE_ADDR'] || $row->ip2 != $_SERVER['REMOTE_ADDR']))) {
    destroy();
}

if (isset($_SESSION['usuario_assinatura'])) {
    if ($_SESSION['usuario_assinatura'] != $row->assinatura) {
        destroy();
    }
} else {
    destroy();
}



include 'mensagem.php';

$ret = json_decode(mensagem_listar('Caixa de entrada', $_SESSION['usuario_id']));
$tot = 0;
foreach ($ret as $id) {
    $mensagem = json_decode(mensagem_carregar($id));
    if ($mensagem->situacao == 'Pendente') {
        $tot++;
    }
}
echo '<a href="mensagem.php?pasta=Caixa de entrada" target="frmPrincipal" title="Ir para Minha Caixa de Entrada">';
if ($tot == 0) {
    echo '<font color="gray">✔ Nenhuma mensagem pendente.';
} elseif ($tot == 1) {
    echo "<font color='orange'>✉ Você tem 1 mensagem não lida.";
} elseif ($tot > 1) {
    echo "<font color='red'>✉ Você tem <en>$tot</en> mensagens não lidas.";
}
echo '</font></a>';

$_SESSION['qtd_mensagens'] = $tot;


include 'agenda.php';

$ret = json_decode(agenda_listar(date('Ymd'), $_SESSION['usuario_id']));
$tot = 0;
$compro = '';
$alerta = '';
foreach ($ret as $id) {
    $agenda = json_decode(agenda_carregar($id));
    if (!empty($agenda->compromisso) && $agenda->hora >= date('Hi')) {
        $tot++;
        if ($tot == 1) {
            $compro = substr($agenda->hora, 0, 2) . ':' . substr($agenda->hora, 2) . '-' . substr($agenda->compromisso, 0, 30);
        }
        if ($agenda->hora == date('Hi')) { //$agenda->hora
            $alerta = $compro;
        }
    }
}

echo '<br><a href="agenda.php" target="frmPrincipal" title="Ir para Minha Agenda de Hoje">';
if ($tot == 0) {
    echo '<font color="gray">✔ Nenhum compromisso hoje.';
} elseif (!empty($alerta)) {
    echo "<font color='red'>♫ $alerta";
} elseif ($tot == 1) {
    echo "<font color='orange'>► $compro";
} elseif ($tot > 1) {
    echo "<font color='darkorange'>► Você tem <en>$tot</en> compromissos hoje.";
}
echo '</font></a>';

function destroy() {
    echo '<font color="red"><strong>USUÁRIO DESCONECTADO.</strong></font>';
    $_SESSION['usuario_id'] = null;
    session_destroy();
    exit();
}
