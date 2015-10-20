<?php

include '../model/ficha.php';

if (!function_exists('ficha_listar')) {

    function ficha_listar($ref) {

        $ficha = new ficha();

        return json_encode($ficha->listar($ref));
    }

    function ficha_carregar($id) {

        $ficha = new ficha();

        return json_encode($ficha->carregar($id));
    }

    function ficha_baixar($id) {

        $ficha = new ficha();

        return $ficha->baixar($id);
    }
    
    function ficha_gravar($ref, $data, $solicitante, $descricao, $situacao) {

        $ficha = new ficha();

        return $ficha->gravar($ref, $data, $solicitante, $descricao, $situacao);
    }

}