<?php

include '../model/caracteristica.php';

if (!function_exists('caracteristica_listar')) {

    function caracteristica_listar() {

        $caracteristica = new caracteristica();

        return json_encode($caracteristica->listar());
    }

    function caracteristica_carregar($id) {

        $caracteristica = new caracteristica();

        return json_encode($caracteristica->carregar($id));
    }

}