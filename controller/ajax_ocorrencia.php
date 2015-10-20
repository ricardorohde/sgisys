<?php

session_start();

if ($_SESSION['cliente_id'] == '0000') {

    include 'ocorrencia.php';
    $ret = json_decode(ocorrencia_listar(" WHERE para='{$_SESSION['usuario_id']}' AND status!='Resolvido'   ", "", ""));
    $tot = 0;
    $ocor = '';
    foreach ($ret as $id) {
        $ocorrencia = json_decode(ocorrencia_carregar($id));
        if ($ocorrencia->agenda_data == date('Ymd')) {
            $tot++;
        }
        if ($tot == 1) {
            $ocor = ' as ' . $ocorrencia->agenda_hora;
        }
        if ($ocorrencia->avisar_data == date('Ymd') && $ocorrencia->avisar_hora == date('H:i')) {
            die('ALARMVocê tem uma ocorrência agendada agora.');
        }
    }

    if ($tot == 1) {
        echo ' ♪  Você tem uma ocorrência agendada para hoje ' . $ocor . '.';
    } elseif ($tot > 1) {
        echo " ♫  Você tem $tot ocorrências agendadas para hoje.";
    } else {
        echo ' ✔ Você não tem nenhuma ocorrência agendada para hoje.';
    }
}
