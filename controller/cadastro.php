<?php

include '../model/cadastro.php';

if (!function_exists('cadastro_listar')) {

    function cadastro_listar($tipo, $where, $order, $rows) {

        $cadastro = new cadastro();

        return json_encode($cadastro->listar($tipo, $where, $order, $rows));
    }

    function cadastro_carregar($tipo, $id) {

        $cadastro = new cadastro();

        return json_encode($cadastro->carregar($tipo, $id));
    }
    
    function tipo_campo($tipo) {

        $cadastro = new cadastro();

        return json_encode($cadastro->tipo_campo($tipo));
    }

    function cadastro_campo($tipo_cadastro) {

        $cadastro = new cadastro();

        return $cadastro->campo($tipo_cadastro);
    }

    function cadastro_foto_gravar($tipo_cadastro, $id, $foto) {

        $cadastro = new cadastro();

        return $cadastro->foto_gravar($tipo_cadastro, $id, $foto);
    }

}