<?php

include '../model/portal_caracteristica.php';

if (!function_exists('portal_caracteristica_listar')) {

    function portal_caracteristica_listar() {

        $portal = new portal_caracteristica();

        return json_encode($portal->listar());
    }

    function portal_caracteristica_carregar($id) {

        $portal = new portal_caracteristica();

        return json_encode($portal->carregar($id));
    }

    function portal_caracteristica_gravar($id, $dados) {

        $portal = new portal_caracteristica();

        return $portal->gravar($id, $dados);
    }

    function portal_caracteristica_procurar($id, $caracteristica) {

        $portal = new portal_caracteristica();

        return json_encode($portal->procurar($id, $caracteristica));
    }

}