<?php

include 'conexao.php';

if (!class_exists('imovel_tipo')) {

    class imovel_tipo extends conexao {

        public function listar($where, $order, $rows) {

            $sql = " SELECT id FROM imovel_tipo $where $order $rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_tipo = array();
            while ($row = $ret->fetch()) {
                $imovel_tipo[] = $row[0];
            }

            return $imovel_tipo;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM imovel_tipo WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

    }

}