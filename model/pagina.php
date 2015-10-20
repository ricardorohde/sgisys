<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('pagina')) {

    class pagina extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM menu_superior WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function listar() {

            $sql = " SELECT id FROM menu_superior ORDER BY id ASC ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $pagina = array();
            while ($row = $ret->fetch()) {
                $pagina[] = $row[0];
            }

            return $pagina;
        }

        public function gravar($id, $opcao, $titulo, $pagina, $conteudo) {

            $menu = $this->carregar($id);

            $a_opcao = $menu['opcao'];
            $a_titulo = $menu['titulo'];
            $a_pagina = $menu['pagina'];

            $sql = " UPDATE menu_superior SET titulo='$titulo',opcao='$opcao',pagina='$pagina',conteudo='$conteudo' WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $desc = '';
            $eol = "\r\n";

            if ($a_opcao != $opcao) {
                $desc .= $eol . 'Alterou Opção de [' . $a_opcao . '] para [' . $opcao . ']';
            }
            if ($a_titulo != $titulo) {
                $desc .= $eol . 'Alterou Título de [' . $a_titulo . '] para [' . $titulo . ']';
            }
            if ($a_pagina != $pagina) {
                $desc .= $eol . 'Alterou Página Destino de [' . $a_pagina . '] para [' . $pagina . ']';
            }

            if (!empty($desc)) {
                $this->ocorrencia('CONTEÚDO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function inserir() {

            $sql = " INSERT INTO menu_superior (id) VALUES ('') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('CONTEÚDO', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function excluir($id) {

            $menu = $this->carregar($id);

            $a_opcao = $menu['opcao'];

            $sql = " DELETE FROM menu_superior WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('CONTEÚDO', 'Excluiu ' . ' ID:' . $id, ' Opção: ' . $a_opcao, $resultado, $sql);

            return $ret->fetch();
        }

    }

}