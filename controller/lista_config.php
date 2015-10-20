<?php

include '../model/lista_config.php';

if (!function_exists('lista_config_carregar')) {

    function lista_config_carregar() {

        $lista_config = new lista_config();

        return json_encode($lista_config->carregar());
    }
    
    function lista_config_campos() {

        $lista_config = new lista_config();

        return $lista_config->campos();
    }
    
    function lista_config_nome_campos() {

        $lista_config = new lista_config();

        return $lista_config->nome_campos();
    }
    
    function lista_config_gravar($dados) {

        $lista_config = new lista_config();

        return $lista_config->gravar($dados);
    }

}