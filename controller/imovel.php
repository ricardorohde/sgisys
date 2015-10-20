<?php

include '../model/imovel.php';

if (!function_exists('imovel')) {

    function imovel_estat_cap($datai, $dataf) {

        $imovel = new imovel();

        return $imovel->estat_cap($datai, $dataf);
    }
    
    function imovel_estat_atu($datai, $dataf) {

        $imovel = new imovel();

        return $imovel->estat_atu($datai, $dataf);
    }
    
    function imovel_estat_atu_maior($datai) {

        $imovel = new imovel();

        return $imovel->estat_atu_maior($datai);
    }
    
    function imovel_estat_atu_menor($datai) {

        $imovel = new imovel();

        return $imovel->estat_atu_menor($datai);
    }
    
    function imovel_estat_atu_sem() {

        $imovel = new imovel();

        return $imovel->estat_atu_sem();
    }

}
