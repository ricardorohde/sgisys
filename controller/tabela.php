<?php

include '../model/tabela.php';

if (!function_exists('tabela_carregar')) {

    function tabela_carregar($tabela_nome) {

        $tabela = new tabela();

        return json_encode($tabela->tabela_carregar($tabela_nome));
    }

    function tabela_campo($tabela_nome, $campo_nome) {

        $tabela = new tabela();

        return json_encode($tabela->tabela_campo($tabela_nome, $campo_nome));
    }

    function tabela_vinculo($tabela_vinculo, $ordem = '') {

        $tabela = new tabela();

        return json_encode($tabela->tabela_vinculo($tabela_vinculo, $ordem));
    }
    
    function tabela_vinculo_unico($tabela_vinculo, $campo) {

        $tabela = new tabela();

        return json_encode($tabela->tabela_vinculo_unico($tabela_vinculo, $campo));
    }

    function tabela_lista($tabela_nome) {

        $tabela = new tabela();

        return json_encode($tabela->tabela_lista($tabela_nome));
    }

    function tabela_carregar_campo($tabela_nome, $nome_campo, $id) {

        $tabela = new tabela();

        return $tabela->tabela_carregar_campo($tabela_nome, $nome_campo, $id);
    }

}