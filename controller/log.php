<?php

include '../model/log.php';

function log_listar($log_where, $log_order, $log_rows) {

    $log = new log();

    return json_encode($log->listar($log_where, $log_order, $log_rows));
}

function log_carregar($id) {

    $log = new log();

    return json_encode($log->carregar($id));
}
