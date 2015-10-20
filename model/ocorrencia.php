<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('ocorrencia')) {

    class ocorrencia extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM ocorrencia WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function listar($where, $order, $rows) {

            $sql = " SELECT id FROM ocorrencia $where $order $rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $ocorrencia = array();
            while ($row = $ret->fetch()) {
                $ocorrencia[] = $row[0];
            }

            return $ocorrencia;
        }

        public function inserir() {

            $sql = " INSERT INTO ocorrencia (id, ref) VALUES ('','{$_SESSION['ocorrencia_ref']}') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('OCORRÊNCIA', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['data'] = 'Data';
            $descri['hora'] = 'Hora';
            $descri['tipo'] = 'Tipo';
            $descri['de'] = 'De';
            $descri['para'] = 'Para';
            $descri['agenda_data'] = 'Agendar Data';
            $descri['agenda_hora'] = 'Agendar Hora';
            $descri['historico'] = 'Histórico';
            $descri['status'] = 'Status';
            $descri['avisar_data'] = 'Avisar Data';
            $descri['avisar_hora'] = 'Avisar Hora';
            $descri['avisar_resolvido'] = 'Avisar qdo Resolver?';
            $descri['avisar_email'] = 'Avisar Lembrete por Email';

            $desc = '';
            $eol = "\r\n";

            $sql = " UPDATE ocorrencia SET id=$id ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value) {
                    if (strpos($campos[$i], 'data') > 0) {
                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . data_decode($aux[$key]) . '] para [' . data_decode($value) . ']';
                    } elseif ($campos[$i] == 'de' || $campos[$i] == 'para') {

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $aux[$campos[$i]] . "'");
                        $ur = $ua->fetch();
                        $a_acesso = $ur[0];

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $dados[$campos[$i]] . "'");
                        $ur = $ua->fetch();
                        $acesso = $ur[0];

                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $a_acesso . '] para [' . $acesso . ']';
                    } else {
                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                        if ($key == 'status' && $dados['avisar_resolvido'] == 'Sim' && $dados['status'] == 'Resolvido') {
                           
                            include 'mensagem.php';
                            $mens = new mensagem();

                            include 'cadastro.php';
                            $cada = new cadastro();
                            
                            $cli = $cada->carregar('cliente', $aux->ref);
                            
                            $mensagem = "A ocorrência nº $id"; // do cliente ".$cli->nome." ";
                            
                            $mens->enviar($_SESSION['usuario_id'], $dados['de'], 'Conclusão de Ocorrência', $mensagem, '');
                        }
                    }
                }
            }
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            if (!empty($desc)) {
                $this->ocorrencia('OCORRÊNCIA', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }

            if ($aux->avisado != 'S' && !empty($dados['avisar'])) {

                include 'mensagem.php';

                $msg = new mensagem();

                $msg->enviar($_SESSION['usuario_id'], $dados['avisar'], 'Ligação : ' . $dados['assunto'], $dados['mensagem'], 'S');

                $sql = " UPDATE ocorrencia SET avisado='S' WHERE id=$id LIMIT 1 ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
            }

            return;
        }

        public function excluir($id) {

            $aux = $this->carregar($id);

            $sql = " DELETE FROM ocorrencia WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());
            $this->ocorrencia('OCORRÊNCIA', ' ID:' . $id, '', $resultado, $sql);

            return $ret->fetch();
        }

    }

}