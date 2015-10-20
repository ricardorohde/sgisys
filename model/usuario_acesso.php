<?php

include 'conexao.php';

if (!class_exists('acesso')) {

    class acesso extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM usuario_acesso WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }
        
        public function usuario_carregar($id) {

            $sql = " SELECT * FROM usuario_acesso WHERE id =  (SELECT acesso FROM usuario WHERE id=$id)";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }
        
        
    }

}

