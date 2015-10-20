<?php

include 'conexao.php';

if (!class_exists('proposta')) {

    class proposta extends conexao {

        public function listar($where, $order, $rows) {

            $sql = " SELECT id FROM proposta WHERE id>0 $where $order $rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $proposta = array();
            while ($row = $ret->fetch()) {
                $proposta[] = $row[0];
            }

            return $proposta;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM proposta WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function contar($situacao) {

            $sql = " SELECT count(id) as total FROM proposta WHERE ";
            if ($situacao == 'P') {
                $sql .= " situacao='Pendente' ";
            } elseif ($situacao == 'E') {
                $sql .= " situacao='Enviada' ";
            } elseif ($situacao == 'B') {
                $sql .= " situacao='Baixada' ";
            }
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            return $row[0];
        }

    }

}