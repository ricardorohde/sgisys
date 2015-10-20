<?php

include '../model/proposta.php';


if (!function_exists('proposta_listar')) {

    function proposta_listar($where, $order, $rows) {

        $proposta = new proposta();

        return json_encode($proposta->listar($where, $order, $rows));
    }

    function proposta_carregar($id) {

        $proposta = new proposta();

        return json_encode($proposta->carregar($id));
    }
    
    function proposta_contar($situacao) {

        $proposta = new proposta();

        return $proposta->contar($situacao);
    }

}