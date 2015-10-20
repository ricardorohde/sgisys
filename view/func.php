<?php

include '../controller/funcoes.php';

if (!function_exists('retorna_nome_mes')) {

    function retorna_nome_mes($mes) {
        $meses = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        return $meses[$mes];
    }

    function filtra($pesquisa) {
        $pesquisa = str_replace('"', '', $pesquisa);
        $pesquisa = str_replace("'", '', $pesquisa);
        $pesquisa = str_replace("*", '', $pesquisa);
        $pesquisa = str_replace("/", '', $pesquisa);
        $pesquisa = str_replace("\\", '', $pesquisa);
        $pesquisa = str_replace(".", '', $pesquisa);
        $pesquisa = str_replace(",", '', $pesquisa);
        $pesquisa = str_replace("%", '', $pesquisa);
        $pesquisa = str_replace("?", '', $pesquisa);
        $pesquisa = str_replace("~", '', $pesquisa);
        $pesquisa = str_replace("^", '', $pesquisa);
        $pesquisa = str_replace("#", '', $pesquisa);
        $pesquisa = str_replace("¨", '', $pesquisa);
        $pesquisa = str_replace("´", '', $pesquisa);
        $pesquisa = str_replace("`", '', $pesquisa);
        $pesquisa = str_replace("&", '', $pesquisa);
        return $pesquisa;
    }

    function filtra_campo($pesquisa) {
        $pesquisa = str_replace('"', '_', $pesquisa);
        $pesquisa = str_replace("'", '_', $pesquisa);
        $pesquisa = str_replace("*", '_', $pesquisa);
        $pesquisa = str_replace(";", '_', $pesquisa);
        $pesquisa = str_replace("\\", '_', $pesquisa);
        $pesquisa = str_replace("%", '_', $pesquisa);
        $pesquisa = str_replace("~", '_', $pesquisa);
        $pesquisa = str_replace("^", '_', $pesquisa);
        $pesquisa = str_replace("#", '_', $pesquisa);
        $pesquisa = str_replace("¨", '_', $pesquisa);
        $pesquisa = str_replace("´", '_', $pesquisa);
        $pesquisa = str_replace("`", '_', $pesquisa);
        $pesquisa = str_replace("&", '_', $pesquisa);
        $pesquisa = str_replace("<", '_', $pesquisa);
        $pesquisa = str_replace(">", '_', $pesquisa);
        $pesquisa = str_replace("{", '_', $pesquisa);
        $pesquisa = str_replace("}", '_', $pesquisa);
        return $pesquisa;
    }

} 

