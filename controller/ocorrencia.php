<?php

session_start();

if (!function_exists('ocorrencia_carregar')) {

    include '../model/ocorrencia.php';

    function ocorrencia_carregar($id) {

        $ocorrencia = new ocorrencia();

        return json_encode($ocorrencia->carregar($id));
    }

    function ocorrencia_ler($id) {

        $ocorrencia = new ocorrencia();

        return json_encode($ocorrencia->ler($id));
    }

    function ocorrencia_mover($id, $pasta) {

        $ocorrencia = new ocorrencia();

        return json_encode($ocorrencia->mover($id, $pasta));
    }

    function ocorrencia_listar($ocorrencia_where, $ocorrencia_order, $ocorrencia_rows) {

        $ocorrencia = new ocorrencia();

        return json_encode($ocorrencia->listar($ocorrencia_where, $ocorrencia_order, $ocorrencia_rows));
    }

    function ocorrencia_confirmar($de, $para, $ocorrencia_titulo) {

        $ocorrencia = new ocorrencia();

        $ocorrencia->enviar($de, $para, 'Confirmação de Leitura', "Mensagem $ocorrencia_titulo Aberta pelo Usuário", '');
    }

}
