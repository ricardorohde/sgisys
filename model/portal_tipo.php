<?php

include 'conexao.php';

if (!class_exists('portal_tipo')) {

    class portal_tipo extends conexao {

        public function listar($id) {

            $sql = " SELECT nome FROM portal_tipo WHERE portal='$id' ORDER BY tipo ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = array();
            while ($row = $ret->fetch()) {
                $id[] = $row[0];
            }

            return $id;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM portal_tipo WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function procurar($id, $tipo) {

            $sql = " SELECT * FROM portal_tipo WHERE portal='$id' AND tipo=$tipo ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['codigo_cliente'] = 'Código Cliente';
            $descri['usuario'] = 'email';
            $descri['senha'] = 'senha';

            $desc = '';
            $eol = "\r\n";
            $sql = " UPDATE portal SET nome='$id' ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value) {
                    $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                }
            }
            $sql .= " WHERE nome='$id' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());
            //echo $sql.$resultado;exit();

            if (!empty($desc)) {
                $this->ocorrencia('TIPO PORTAL', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

    }

}