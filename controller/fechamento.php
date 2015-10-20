<?php

include '../model/fechamento.php';

if (!function_exists('fechamento_listar')) {

    function fechamento_listar($fechamento_where, $fechamento_order, $fechamento_rows) {

        $fechamento = new fechamento();

        return json_encode($fechamento->listar($fechamento_where, $fechamento_order, $fechamento_rows));
    }

    function fechamento_carregar($id) {

        $fechamento = new fechamento();

        return json_encode($fechamento->carregar($id));
    }

}