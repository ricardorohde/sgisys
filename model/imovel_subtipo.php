<?php

include 'conexao.php';

if (!class_exists('imovel_subtipo')) {

    class imovel_subtipo extends conexao {

        public function listar($where, $order, $rows) {

            $sql = " SELECT id FROM imovel_subtipo $where $order $rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_subtipo = array();
            while ($row = $ret->fetch()) {
                $imovel_subtipo[] = $row[0];
            }

            return $imovel_subtipo;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM imovel_subtipo WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }
        
        public function carregar_tipo($tipo) {

            $sql = " SELECT id,subtipo FROM imovel_subtipo WHERE tipo=$tipo";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_subtipo = array();
            while ($row = $ret->fetch()) {
                $imovel_subtipo[] = $row;
            }

            return $imovel_subtipo;
        }

    }

}