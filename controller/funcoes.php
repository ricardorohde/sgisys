<?php

if (!function_exists('criptog')) {

    function criptog($texto) {
        return crypt($texto, 'RenatuZ');
    }

    function data_encode($data = '') {

        $data = substr($data, 6) . substr($data, 3, 2) . substr($data, 0, 2);

        return $data;
    }

    function data_decode($data = '') {

        if (!empty($data)) {
            $data = substr($data, 6, 2) . '/' . substr($data, 4, 2) . '/' . substr($data, 0, 4);
        }

        return $data;
    }

    function data_my($data = '') {

        if (!empty($data)) {
            $data = substr($data, 6) . '-' . substr($data, 3, 2) . '-' . substr($data, 0, 2);
        }

        return $data;
    }

    function my_data($data = '') {

        if (!empty($data) && $data != '0000-00-00') {
            $data = substr($data, 8, 2) . '/' . substr($data, 5, 2) . '/' . substr($data, 0, 4);
        }

        return $data;
    }

    function us_br($valor = 0) {
        if ($valor > 0) {
            $valor = number_format($valor, 2, ',', '.');
        }
        return $valor;
    }

    function br_us($valor = 0) {
        if ($valor > 0) {
            $valor = str_replace(',', '.', str_replace('.', '', $valor));
        }
        return $valor;
    }

}

