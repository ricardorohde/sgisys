<?php

include '../model/site_config.php';

if (!function_exists('site_config_carregar')) {

    function site_config_carregar() {

        $site_config = new site_config();

        return json_encode($site_config->carregar());
    }
    
    function site_config_campos() {

        $site_config = new site_config();

        return $site_config->campos();
    }
    
    function site_config_nome_campos() {

        $site_config = new site_config();

        return $site_config->nome_campos();
    }
    
    function site_config_gravar($dados) {

        $site_config = new site_config();

        return $site_config->gravar($dados);
    }

}