<?php

include 'conexao.php';

if (!class_exists('caracteristica')) {

    class caracteristica extends conexao {

        public function listar() {

            $sql = " SELECT id FROM caracteristica ORDER BY nome ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $caracteristica = array();
            while ($row = $ret->fetch()) {
                $caracteristica[] = $row[0];
            }

            return $caracteristica;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM caracteristica WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function gravar($id, $nome) {

            $aux = $this->carregar($id);

            $a_nome = $aux['nome'];

            $sql = " UPDATE caracteristica SET nome='$nome' WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $desc = '';
            $eol = "\r\n";

            if ($a_nome != $nome) {
                $desc .= $eol . 'Alterou Nome de [' . $a_nome . '] para [' . $nome . ']';
            }

            if (!empty($desc)) {
                $this->ocorrencia('CARACTERÍSTICA', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function inserir() {

            $sql = " INSERT INTO caracteristica (id) VALUES ('') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('CARACTERÍSTICA', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function excluir($id) {

            $aux = $this->carregar($id);

            $nome = $aux['nome'];

            $sql = " DELETE FROM caracteristica WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('CARACTERÍSTICA', 'Excluiu ' . ' ID:' . $id, 'Nome: ' . $nome, $resultado, $sql);

            return $ret->fetch();
        }

    }

}