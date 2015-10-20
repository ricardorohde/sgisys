<?php

include '../model/detalhe_config.php';

if (!function_exists('detalhe_config_carregar')) {

    function detalhe_config_carregar() {

        $detalhe_config = new detalhe_config();

        return json_encode($detalhe_config->carregar());
    }
    
    function detalhe_config_campos() {

        $detalhe_config = new detalhe_config();

        return $detalhe_config->campos();
    }
    
    function detalhe_config_nome_campos() {

        $detalhe_config = new detalhe_config();

        return $detalhe_config->nome_campos();
    }
    
    function detalhe_config_gravar($dados) {

        $detalhe_config = new detalhe_config();

        return $detalhe_config->gravar($dados);
    }

}