<?php
ini_set('display_errors', 'on');
ini_set('max_execution_time', '600');
set_time_limit(600);

if (!function_exists('mensagem_carregar')) {

    include '../model/mensagem.php';

    function mensagem_carregar($id) {

        $mensagem = new mensagem();

        return json_encode($mensagem->carregar($id));
    }

    function mensagem_ler($id) {

        $mensagem = new mensagem();

        return json_encode($mensagem->ler($id));
    }
    
    function mensagem_mover($id, $pasta) {

        $mensagem = new mensagem();

        return json_encode($mensagem->mover($id, $pasta));
    }

    function mensagem_listar($pasta, $usuario) {

        $mensagem = new mensagem();

        return json_encode($mensagem->listar($pasta, $usuario));
    }

    function mensagem_confirmar($de, $para, $mensagem_titulo) {

        $mensagem = new mensagem();

        $mensagem->enviar($de, $para, 'Confirmação de Leitura', "Mensagem $mensagem_titulo Aberta pelo Usuário", '');
    }

}
