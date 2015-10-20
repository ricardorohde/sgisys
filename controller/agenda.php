<?php

session_start();

if (!function_exists('agenda_carregar')) {

    include '../model/agenda.php';

    function agenda_carregar($id) {

        $agenda = new agenda();

        return json_encode($agenda->carregar($id));
    }

    function agenda_ler($id) {

        $agenda = new agenda();

        return json_encode($agenda->ler($id));
    }

    function agenda_mover($id, $pasta) {

        $agenda = new agenda();

        return json_encode($agenda->mover($id, $pasta));
    }

    function agenda_listar($data, $usuario) {

        $agenda = new agenda();

        return json_encode($agenda->listar($data, $usuario));
    }

    function agenda_confirmar($de, $para, $agenda_titulo) {

        $agenda = new agenda();

        $agenda->enviar($de, $para, 'Confirmação de Leitura', "Mensagem $agenda_titulo Aberta pelo Usuário", '');
    }

    function pesquisa_compromisso_dia($usuario, $data) {
        $agenda = new agenda();

        return $agenda->compromisso_dia($usuario, $data);
    }

}
