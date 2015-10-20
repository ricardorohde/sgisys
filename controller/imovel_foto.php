<?php

include '../model/imovel_foto.php';

if (!function_exists('imovel_foto_listar')) {

    function imovel_foto_listar($ref) {

        $imovel_foto = new imovel_foto();

        return json_encode($imovel_foto->listar($ref));
    }
    
    function imovel_foto_listar2($ref) {

        $imovel_foto = new imovel_foto();

        return json_encode($imovel_foto->listar2($ref));
    }

    function imovel_foto_carregar($id) {

        $imovel_foto = new imovel_foto();

        return json_encode($imovel_foto->carregar($id));
    }

    function imovel_foto_gravar($ref, $nome) {

        $imovel_foto = new imovel_foto();

        return $imovel_foto->gravar($ref, $nome);
    }

    function imovel_foto_excluir($foto) {

        $imovel_foto = new imovel_foto();

        return $imovel_foto->excluir($foto);
    }
    
    function imovel_testa_fachada($ref) {

        $imovel_foto = new imovel_foto();

        return $imovel_foto->testa_fachada($ref);
    }
    
    function imovel_grava_fachada($id) {

        $imovel_foto = new imovel_foto();

        return $imovel_foto->grava_fachada($id);
    }

}