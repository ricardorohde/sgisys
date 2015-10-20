<?php

include 'conexao.php';

if (!class_exists('ficha_config')) {

    class ficha_config extends conexao {

        public function carregar() {

            $sql = " SELECT * FROM ficha_config";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

    }

}