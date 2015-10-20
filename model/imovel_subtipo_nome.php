<?php

include 'conexao.php';

if (!class_exists('imovel_subtipo_nome')) {

    class imovel_subtipo_nome extends conexao {

        public function listar($q) {

            $sql = " SELECT DISTINCT(subtipo_nome) FROM imovel WHERE tipo_nome='$q' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tmp = array();
            while ($row = $ret->fetch()) {
                $tmp[] = $row[0];
            }

            return $tmp;
        }

    }

}