<?php

include '../model/pesquisa.php';
include 'tabela.php';

if (!function_exists('pesquisa_lista')) {

    function pesquisa_lista($tipo_cadastro) {

        $pesquisa = new pesquisa();

        $lista = $pesquisa->lista($tipo_cadastro);

        return $lista;
    }

    function pesquisa_get_set($campo) {

        if (isset($_SESSION['tipo_cadastro'])) {

            $tipo_cadastro = $_SESSION['tipo_cadastro'];

            if (!isset($_SESSION['pesquisa'])) {
                $_SESSION['pesquisa'] = array();
            }
            if (!isset($_SESSION['pesquisa'][$tipo_cadastro])) {
                $_SESSION['pesquisa'][$tipo_cadastro] = array();
            }

            if (!isset($_SESSION['pesquisa'][$tipo_cadastro][$campo])) {
                $_SESSION['pesquisa'][$tipo_cadastro][$campo] = '';
            }

            if (isset($_GET[$campo])) {
                $_SESSION['pesquisa'][$tipo_cadastro][$campo] = $_GET[$campo];
            }
        }
    }

}