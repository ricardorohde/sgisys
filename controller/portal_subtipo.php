<?php

include '../model/portal_subtipo.php';

if (!function_exists('portal_subtipo_listar')) {

    function portal_subtipo_listar() {

        $portal = new portal_subtipo();

        return json_encode($portal->listar());
    }

    function portal_subtipo_carregar($id) {

        $portal = new portal_subtipo();

        return json_encode($portal->carregar($id));
    }

    function portal_subtipo_gravar($id, $dados) {

        $portal = new portal_subtipo();

        return $portal->gravar($id, $dados);
    }

    function portal_subtipo_procurar($id, $subtipo) {

        $portal = new portal_subtipo();

        return json_encode($portal->procurar($id, $subtipo));
    }

}