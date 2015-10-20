<?php

include '../model/imovel_tipo.php';

if (!function_exists('imovel_tipo_listar')) {

    function imovel_tipo_listar($where, $order, $rows) {

        $imovel_tipo = new imovel_tipo();

        return json_encode($imovel_tipo->listar($where, $order, $rows));
    }

}

if (!function_exists('imovel_tipo_carregar')) {

    function imovel_tipo_carregar($id) {

        $imovel_tipo = new imovel_tipo();

        return json_encode($imovel_tipo->carregar($id));
    }

}