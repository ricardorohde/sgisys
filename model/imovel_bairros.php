<?php

include 'conexao.php';

if (!class_exists('imovel_bairros')) {

    class imovel_bairros extends conexao {

        public function listar($cidade) {

            $sql = " SELECT DISTINCT(bairro) FROM imovel WHERE cidade='$cidade' ";
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