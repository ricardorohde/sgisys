<?php

include '../model/imovel_caracteristica.php';

if (!function_exists('imovel_caracteristica_listar')) {

    function imovel_caracteristica_listar($ref) {

        $imovel_caracteristica = new imovel_caracteristica();

        return json_encode($imovel_caracteristica->listar($ref));
    }

    function imovel_caracteristica_carregar($id) {

        $imovel_caracteristica = new imovel_caracteristica();

        return json_encode($imovel_caracteristica->carregar($id));
    }
    
    function imovel_caracteristica_carregar_id($id) {

        $imovel_caracteristica = new imovel_caracteristica();

        return $imovel_caracteristica->carregar_id($id);
    }

    function imovel_caracteristica_procurar($ref, $caracteristica) {

        $imovel_caracteristica = new imovel_caracteristica();

        return $imovel_caracteristica->procurar($ref, $caracteristica);
    }

}    