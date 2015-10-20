<?php

include '../model/portal_tipo.php';

if (!function_exists('portal_tipo_listar')) {

    function portal_tipo_listar() {

        $portal = new portal_tipo();

        return json_encode($portal->listar());
    }

    function portal_tipo_carregar($id) {

        $portal = new portal_tipo();

        return json_encode($portal->carregar($id));
    }

    function portal_tipo_gravar($id, $dados) {

        $portal = new portal_tipo();

        return $portal->gravar($id, $dados);
    }

    function portal_tipo_procurar($id, $tipo) {

        $portal = new portal_tipo();

        return json_encode($portal->procurar($id, $tipo));
    }

}