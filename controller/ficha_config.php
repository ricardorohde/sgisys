<?php

include '../model/ficha_config.php';

if (!function_exists('ficha_config_carregar')) {

    function ficha_config_carregar() {

        $ficha_config = new ficha_config();

        return json_encode($ficha_config->carregar());
    }

}