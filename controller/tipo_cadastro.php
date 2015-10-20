<?php

include '../model/tipo_cadastro.php';


if (!function_exists('tipo_cadastro_listar')) {

    function tipo_cadastro_listar() {

        $tipo_cadastro = new tipo_cadastro();

        return json_encode($tipo_cadastro->listar());
    }

    function tipo_cadastro_carregar($id) {

        $tipo_cadastro = new tipo_cadastro();

        return json_encode($tipo_cadastro->carregar($id));
    }
    
    function tipo_cadastro_carregar_id($tipo_cadastro_nome) {

        $tipo_cadastro = new tipo_cadastro();

        return json_encode($tipo_cadastro->carregar_id($tipo_cadastro_nome));
    }

}