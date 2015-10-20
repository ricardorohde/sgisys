<?php

include '../model/imovel_subtipo.php';

if (!function_exists('imovel_subtipo_listar')) {

    function imovel_subtipo_listar($where, $order, $rows) {

        $imovel_subtipo = new imovel_subtipo();

        return json_encode($imovel_subtipo->listar($where, $order, $rows));
    }

}

if (!function_exists('imovel_subtipo_carregar')) {

    function imovel_subtipo_carregar($id) {

        $imovel_subtipo = new imovel_subtipo();

        return json_encode($imovel_subtipo->carregar($id));
    }

}

if (!function_exists('imovel_subtipo_carregar_tipo')) {

    function imovel_subtipo_carregar_tipo($tipo) {

        $imovel_subtipo = new imovel_subtipo();

        return json_encode($imovel_subtipo->carregar_tipo($tipo));
    }

}