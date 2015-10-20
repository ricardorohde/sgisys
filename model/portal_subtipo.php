<?php

include 'conexao.php';

if (!class_exists('portal_subtipo')) {

    class portal_subtipo extends conexao {

        public function listar($id) {

            $sql = " SELECT nome FROM portal_subtipo WHERE portal='$id' ORDER BY subtipo ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = array();
            while ($row = $ret->fetch()) {
                $id[] = $row[0];
            }

            return $id;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM portal_subtipo WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function procurar($id, $subtipo) {

            $sql = " SELECT * FROM portal_subtipo WHERE portal='$id' AND subtipo=$subtipo ";
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