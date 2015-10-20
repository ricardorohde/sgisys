<?php

include 'conexao.php';

if (!class_exists('ficha')) {

    class ficha extends conexao {

        public function listar($ref) {

            $sql = " SELECT id FROM ficha WHERE ref=$ref ORDER BY data DESC ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = array();
            while ($row = $ret->fetch()) {
                $id[] = $row[0];
            }

            return $id;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM ficha WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function baixar($id) {

            $sql = " UPDATE ficha SET situacao='Baixado em " . date('d/m/Y H:i:s') . "' WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return 'OK';
        }
        
        public function gravar($ref, $data, $solicitante, $descricao, $situacao) {

            $sql = " INSERT INTO ficha (ref,data,solicitante,descricao,situacao) VALUES ('$ref','$data','$solicitante','$descricao','$situacao')";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return ;
        }

    }

}