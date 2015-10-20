<?php

include '../model/oferta.php';

if (!function_exists('oferta_listar_imovel')) {

    function oferta_listar_imovel($id) {

        $oferta = new oferta();

        return json_encode($oferta->listar_imovel($id));
    }
    
    function oferta_qualidade_imovel($id) {

        $oferta = new oferta();

        return $oferta->qualidade_imovel($id);
    }

    function oferta_carregar_imovel($id) {

        $oferta = new oferta();

        return json_encode($oferta->carregar_imovel($id));
    }
    
    function oferta_sem_novidades() {

        $oferta = new oferta();

        return $oferta->sem_novidades();
    }

}
