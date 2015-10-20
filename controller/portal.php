<?php

include '../model/portal.php';

if (!function_exists('portal_listar')) {

    function portal_listar() {

        $portal = new portal();

        return json_encode($portal->listar());
    }

    function portal_carregar($id) {

        $portal = new portal();

        return json_encode($portal->carregar($id));
    }
    
    function portal_carregar_id($id) {

        $portal = new portal();

        return $portal->carregar_id($id);
    }
    
    function portal_gravar($id,$dados) {

        $portal = new portal();

        return json_encode($portal->gravar($id,$dados));
    }

    function publicar_procurar($ref, $id) {

        $portal = new portal();

        return $portal->publicar_procurar($ref, $id);
    }

    function publicar_listar($id) {

        $portal = new portal();

        return json_encode($portal->publicar_listar($id));
    }
    
    function publicar_carregar($id) {

        $portal = new portal();

        return json_encode($portal->publicar_carregar($id));
    }
    
    function publicar_data_envio($id, $tipo_anuncio) {

        $portal = new portal();

        return $portal->data_envio($id, $tipo_anuncio);
    }
    
    function portal_total_envio($nome) {

        $portal = new portal();

        return $portal->total_envio($nome);
    }

}