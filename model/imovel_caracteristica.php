<?php

include 'conexao.php';

if (!class_exists('imovel_caracteristica')) {

    class imovel_caracteristica extends conexao {

        public function listar($ref) {

            $sql = " SELECT id FROM imovel_caracteristica WHERE ref=$ref ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_caracteristica = array();
            while ($row = $ret->fetch()) {
                $imovel_caracteristica[] = $row[0];
            }

            return $imovel_caracteristica;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM imovel_caracteristica WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }
        
        public function carregar_id($id) {

            $sql = " SELECT nome FROM caracteristica WHERE id=(SELECT caracteristica FROM imovel_caracteristica WHERE id=$id)";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            return $row[0];
        }

        public function procurar($ref, $caracteristica) {

            $sql = " SELECT * FROM imovel_caracteristica WHERE ref=$ref AND caracteristica=$caracteristica";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            if ($ret->rowCount() == 1) {
                return true;
            }
            return false;
        }

        public function gravar($ref, $dados) {

            $sql = " DELETE FROM imovel_caracteristica WHERE ref=$ref";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            include 'caracteristica.php';

            $aux = json_decode(caracteristica_listar());
            foreach ($aux as $id) {
                if ($dados[$id] == 'S') {
                    $sql = " INSERT INTO imovel_caracteristica (id, ref, caracteristica) VALUES ('', $ref, $id)";
                    $ret = $this->db->prepare($sql);
                    $ret->execute();
                }
            }
            return true;
        }

    }

}