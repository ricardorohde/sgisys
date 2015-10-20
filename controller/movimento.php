<?php

include '../model/movimento.php';

if (!function_exists('movimento_listar')) {

    function movimento_listar($movimento_where, $movimento_order, $movimento_rows) {

        $movimento = new movimento();

        return json_encode($movimento->listar($movimento_where, $movimento_order, $movimento_rows));
    }

    function movimento_carregar($id) {

        $movimento = new movimento();

        return json_encode($movimento->carregar($id));
    }

}