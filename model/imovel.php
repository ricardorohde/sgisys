<?php

include 'conexao.php';

if (!class_exists('imovel')) {

    class imovel extends conexao {

        public function estat_cap($datai, $dataf) {

            $sql = " SELECT count(id) as total FROM imovel WHERE (data_captacao_venda BETWEEN '$datai' AND '$dataf') or (data_captacao_locacao BETWEEN '$datai' AND '$dataf') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            return $row[0];
        }

        public function estat_atu($datai, $dataf) {

            $sql = " SELECT count(id) as total FROM imovel WHERE situacao='Ativo' and data_atualizacao BETWEEN '$datai' AND '$dataf' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            return $row[0];
        }

        public function estat_atu_maior($datai) {

            $sql = " SELECT count(id) as total FROM imovel WHERE situacao='Ativo' and data_atualizacao > '$datai'  and data_atualizacao != ''";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            return $row[0];
        }

        public function estat_atu_menor($datai) {

            $sql = " SELECT count(id) as total FROM imovel WHERE situacao='Ativo' and data_atualizacao < '$datai' and data_atualizacao != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            return $row[0];
        }
        
        public function estat_atu_sem() {

            $sql = " SELECT count(id) as total FROM imovel WHERE situacao='Ativo' and data_atualizacao = '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            return $row[0];
        }

    }

}