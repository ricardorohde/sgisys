<?php

include 'conexao.php';

class log extends conexao {

    public function listar($log_where, $log_order, $log_rows) {

        if ($_SESSION['usuario_nome'] != 'ADMIN') {
            $log_where = " and usuario != 'admin' " . $log_where;
        }

        $sql = " SELECT id FROM log WHERE id > 0 $log_where $log_order $log_rows ";
        $ret = $this->db->prepare($sql);
        $ret->execute();
        $log = array();
        while ($row = $ret->fetch()) {
            $log[] = $row[0];
        }

        return $log;
    }

    public function carregar($id) {

        $sql = " SELECT * FROM log WHERE id=$id";
        $ret = $this->db->prepare($sql);
        $ret->execute();

        return $ret->fetch();
    }

}
