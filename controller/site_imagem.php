<?php

include '../model/site_imagem.php';

if (!function_exists('site_imagem_listar')) {

    function site_imagem_listar() {

        $site_imagem = new site_imagem();

        return json_encode($site_imagem->listar());
    }

    function site_imagem_carregar($id) {

        $site_imagem = new site_imagem();

        return json_encode($site_imagem->carregar($id));
    }

    function site_imagem_gravar($img, $nome) {

        $site_imagem = new site_imagem();

        return json_encode($site_imagem->gravar($img, $nome));
    }
    
    function site_imagem_excluir($id) {

        $site_imagem = new site_imagem();

        return $site_imagem->excluir($id);
    }

}