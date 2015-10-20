<?php

include 'conexao.php';

if (!class_exists('tabela')) {

    class tabela extends conexao {

        public function tabela_carregar($nome) {

            $sql = " SELECT * FROM tabela WHERE nome='$nome' ORDER BY campo_grupo, id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tabela = array();

            while ($tabela[] = $ret->fetch());

            return $tabela;
        } 

        public function tabela_campo($nome, $campo) {

            $sql = " SELECT * FROM tabela WHERE nome='$nome' and campo='$campo' ORDER BY campo_grupo, id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function tabela_vinculo($tabela_vinculo, $ordem = '') {

            if (!empty($ordem)) {
                $ordem = ' ORDER BY ' . $ordem;
            }
            $sql = " SELECT * FROM $tabela_vinculo $ordem ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tabela = array();

            while ($tabela[] = $ret->fetch());

            return $tabela;
        }
        
        public function tabela_vinculo_unico($tabela_vinculo, $campo) {

            $sql = " SELECT distinct($campo) as $campo FROM $tabela_vinculo ORDER BY $campo ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tabela = array();

            while ($tabela[] = $ret->fetch());

            return $tabela;
        }

        public function tabela_carregar_campo($tabela, $campo, $id) {

            $sql = " SELECT $campo FROM $tabela WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            return $row[0];
        }

        public function tabela_lista($nome) {

            $sql = " SELECT * FROM lista WHERE nome='$nome'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $tabela = array();

            while ($tabela[] = $ret->fetch());

            return $tabela;
        }

    }

}