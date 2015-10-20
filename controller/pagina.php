<?php

session_start();

if (!function_exists('pagina_carregar')) {

    include '../model/pagina.php';

    function pagina_carregar($id) {

        $pagina = new pagina();

        return json_encode($pagina->carregar($id));
    }

    function pagina_listar() {

        $pagina = new pagina();

        return json_encode($pagina->listar());
    }

    function pagina_gravar($id, $opcao, $titulo) {

        $pagina = new pagina();

        return $pagina->gravar($id, $opcao, $titulo);
    }

}

