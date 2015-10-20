<?php

session_start();

if (!function_exists('ligacao_carregar')) {

    include '../model/ligacao.php';

    function ligacao_carregar($id) {

        $ligacao = new ligacao();

        return json_encode($ligacao->carregar($id));
    }

    function ligacao_ler($id) {

        $ligacao = new ligacao();

        return json_encode($ligacao->ler($id));
    }

    function ligacao_mover($id, $pasta) {

        $ligacao = new ligacao();

        return json_encode($ligacao->mover($id, $pasta));
    }

    function ligacao_listar($ligacao_where, $ligacao_order, $ligacao_rows) {

        $ligacao = new ligacao();

        return json_encode($ligacao->listar($ligacao_where, $ligacao_order, $ligacao_rows));
    }

    function ligacao_confirmar($de, $para, $ligacao_titulo) {

        $ligacao = new ligacao();

        $ligacao->enviar($de, $para, 'Confirmação de Leitura', "Mensagem $ligacao_titulo Aberta pelo Usuário", '');
    }

    function ligacao_estat_1($datai, $dataf) {

        $ligacao = new ligacao();

        return $ligacao->estat_1($datai, $dataf);
    }

}
