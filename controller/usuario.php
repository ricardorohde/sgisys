<?php

if (!function_exists('usuario_carregar')) {

    include '../model/usuario.php';

    function usuario_carregar($id) {

        $usuario = new usuario();

        return json_encode($usuario->carregar($id));
    }

    function usuario_id($nome) {

        $usuario = new usuario();

        return json_encode($usuario->carregar_id($nome));
    }

    function usuario_listar($usuario_where, $usuario_order, $usuario_rows) {

        $usuario = new usuario();

        return json_encode($usuario->listar($usuario_where, $usuario_order, $usuario_rows));
    }

    function usuario_foto_gravar($id, $foto) {

        $usuario = new usuario();

        return $usuario->foto_gravar($id, $foto);
    }

    function usuario_grava_senha($id, $senha_atual, $nova_senha) {

        $usuario = new usuario();

        return $usuario->senha_gravar($id, $senha_atual, $nova_senha);
    }

    function usuario_gravar_reset($id, $hash) {

        $usuario = new usuario();

        return $usuario->gravar_reset($id, $hash);
    }
    
    function usuario_corretor($id) {

        $usuario = new usuario();

        return $usuario->usuario_corretor($id);
    }
    
    function usuario_corretor_restrito($id) {

        $usuario = new usuario();

        return $usuario->usuario_corretor_restrito($id);
    }
    
    function usuario_corretor_nome($id) {

        $usuario = new usuario();

        return $usuario->usuario_corretor_nome($id);
    }

}

