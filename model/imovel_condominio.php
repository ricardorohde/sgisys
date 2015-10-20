<?php

include 'conexao.php';

if (!class_exists('imovel_condominio')) {

    class imovel_condominio extends conexao {

        public function listar() {

            $sql = " SELECT DISTINCT(condominio) FROM imovel WHERE condominio != '' ORDER BY condominio";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_condominio = array();
            while ($row = $ret->fetch()) {
                $imovel_condominio[] = $row[0];
            }
            
            return $imovel_condominio;
        }

    }

}