<?php

include 'conexao.php';

if (!class_exists('movimento')) {

    class movimento extends conexao {

        public function listar($movimento_where, $movimento_order, $movimento_rows) {

            $sql = " SELECT id FROM movimento WHERE id > 0 $movimento_where $movimento_order $movimento_rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $movimento = array();
            while ($row = $ret->fetch()) {
                $movimento[] = $row[0];
            }

            return $movimento;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM movimento WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function inserir() {

            $sql = " INSERT INTO movimento (id, cadastro_data,cadastro_por) VALUES ('','" . date('Y-m-d H:i:s') . "','" . $_SESSION['movimento_id'] . "') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('MOVIMENTO', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function gravar($id, $data, $tipo, $nome, $historico, $sentido, $valor) {

            $aux = $this->carregar($id);

            $campos = array();

            $campos[] = 'data';
            $campos[] = 'tipo';
            $campos[] = 'nome';
            $campos[] = 'historico';
            $campos[] = 'sentido';
            $campos[] = 'valor';

            $descri = array();

            $descri[] = 'Data';
            $descri[] = 'Tipo';
            $descri[] = 'Nome';
            $descri[] = 'Histórico';
            $descri[] = 'Sentido';
            $descri[] = 'Valor';

            $sql = " UPDATE movimento SET ";
            $sql .= "  data='$data' ";
            $sql .= " ,tipo='$tipo' ";
            $sql .= " ,nome='$nome' ";
            $sql .= " ,historico='$historico' ";
            $sql .= " ,sentido='$sentido' ";
            $sql .= " ,valor='$valor' ";
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());
            //echo $sql.$resultado;exit();
            $desc = '';
            $eol = "\r\n";

            for ($i = 0; $i < count($campos); $i++) {
                if ($aux[$campos[$i]] != $$campos[$i] && !empty($campos[$i])) {
                    $desc .= $eol . 'Alterou ' . $descri[$i] . ' de [' . $aux[$campos[$i]] . '] para [' . $$campos[$i] . ']';
                }
            }

            if (!empty($desc)) {
                $this->ocorrencia('MOVIMENTO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function excluir($id) {

            if ($alt == 'S') {
                $alt = 'N';
            } else {
                $alt = 'S';
            }

            $sql = " DELETE FROM movimento WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MOVIMENTO', 'Excluiu ' . ' ID:' . $id, '', $resultado, $sql);

            return $ret->fetch();
        }

    }

}
