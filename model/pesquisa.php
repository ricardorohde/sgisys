<?php

include 'conexao.php';

class pesquisa extends conexao {

    public function lista($tipo_cadastro) {

        $sql = " SELECT campo FROM pesquisa WHERE nome='$tipo_cadastro' ORDER BY id ";
        $ret = $this->db->prepare($sql);
        $ret->execute();

        $pesquisa = array();
        while ($row = $ret->fetch()) {
            $pesquisa[] = $row[0];
        }

        return $pesquisa;
    }

}
