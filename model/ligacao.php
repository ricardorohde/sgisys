<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('ligacao')) {

    class ligacao extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM ligacao WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function listar($where, $order, $rows) {

            $sql = " SELECT id FROM ligacao $where $order $rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $ligacao = array();
            while ($row = $ret->fetch()) {
                $ligacao[] = $row[0];
            }

            return $ligacao;
        }

        public function inserir() {

            $sql = " INSERT INTO ligacao (id, cadastro_data,cadastro_por) VALUES ('','" . date('Y-m-d H:i:s') . "','" . $_SESSION['usuario_id'] . "') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('LIGAÇÃO', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['atendente'] = 'Atendente';
            $descri['data'] = 'Data';
            $descri['hora'] = 'Hora';
            $descri['assunto'] = 'Assunto';
            $descri['departamento'] = 'Departamento';
            $descri['mensagem'] = 'Mensagem';
            $descri['avisar'] = 'Avisar';

            $desc = '';
            $eol = "\r\n";

            $sql = " UPDATE ligacao SET id=$id ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value) {
                    if ($campos[$i] == 'avisar') {

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $aux['avisar'] . "'");
                        $ur = $ua->fetch();
                        $a_acesso = $ur[0];

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $dados['avisar'] . "'");
                        $ur = $ua->fetch();
                        $acesso = $ur[0];

                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $a_acesso . '] para [' . $acesso . ']';
                    } else {
                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                    }
                }
            }
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            if (!empty($desc)) {
                $this->ocorrencia('LIGAÇÃO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }

            if ($aux->avisado != 'S' && !empty($dados['avisar'])) {

                include 'mensagem.php';

                $msg = new mensagem();

                $msg->enviar($_SESSION['usuario_id'], $dados['avisar'], 'Ligação : ' . $dados['assunto'], $dados['mensagem'], 'S');

                $sql = " UPDATE ligacao SET avisado='S' WHERE id=$id LIMIT 1 ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
            }

            return;
        }

        public function excluir($id) {

            $aux = $this->carregar($id);

            $sql = " DELETE FROM ligacao WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());
            $this->ocorrencia('LIGAÇÃO', ' ID:' . $id, '', $resultado, $sql);

            return $ret->fetch();
        }

        public function estat_1($datai, $dataf) {

            $sql = " SELECT count(id) as total FROM ligacao WHERE data BETWEEN '$datai' AND '$dataf' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            return $row[0];
        }

    }

}