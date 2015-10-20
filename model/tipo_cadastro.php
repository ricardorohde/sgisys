<?php

include 'conexao.php';

if (!function_exists('tipo_cadastro')) {

    class tipo_cadastro extends conexao {

        public function listar() {

            $sql = " SELECT id FROM tipo_cadastro WHERE admin != 'S' ORDER BY id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tipo_cadastro = array();
            while ($row = $ret->fetch()) {
                $tipo_cadastro[] = $row[0];
            }

            return $tipo_cadastro;
        }

        public function listar_adm() {

            $sql = " SELECT id FROM tipo_cadastro WHERE admin = 'S' ORDER BY id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tipo_cadastro = array();
            while ($row = $ret->fetch()) {
                $tipo_cadastro[] = $row[0];
            }

            return $tipo_cadastro;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM tipo_cadastro WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function carregar_id($tipo_cadastro_nome) {

            $sql = " SELECT * FROM tipo_cadastro WHERE tabela='$tipo_cadastro_nome'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

    }

}
