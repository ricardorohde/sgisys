<?php
session_start();

if (!function_exists('usuario_acesso_carregar')) {
    
    include '../model/usuario_acesso.php';

    function acesso_carregar($id) {

        $acesso = new acesso();

        return json_encode($acesso->carregar($id));
    }
    
    function usuario_acesso_carregar($id) {

        $acesso = new acesso();

        return json_encode($acesso->usuario_carregar($id));
    }
}
