<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('agenda')) {

    class agenda extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM agenda WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function listar($data, $usuario) {

            $sql = " SELECT id FROM agenda WHERE usuario=$usuario and data='$data' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            if ($ret->rowCount() == 0) {
                $hora = 0000;
                while ($hora <= 2330) {
                    $hora = str_pad($hora, 4, '0', 0);
                    $this->db->query(" INSERT INTO agenda (usuario,data,hora) VALUES ('$usuario','$data','$hora');");
                    if (substr($hora, 2) == 00) {
                        $hora += 30;
                    } elseif (substr($hora, 2) == 30) {
                        $hora += 70;
                    }
                }
            }

            $sql = " SELECT id FROM agenda WHERE usuario=$usuario and data='$data' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $agenda = array();
            while ($row = $ret->fetch()) {
                $agenda[] = $row[0];
            }

            return $agenda;
        }

        public function enviar($de, $para, $assunto, $agenda, $confirmar) {

            $sql = " INSERT INTO agenda (id, de, para, assunto, agenda, envio, confirmar) VALUES ('', '$de', '$para', '$assunto', '$agenda', '" . date('d/m/Y H:i:s') . "', '$confirmar') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $sql = " INSERT INTO agenda (id, de, para, assunto, agenda, envio, pasta, situacao) VALUES ('', '$de', '$para', '$assunto', '$agenda', '" . date('d/m/Y H:i:s') . "','Mensagens enviadas','Enviado') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = $this->db->lastInsertId('id');

            $ua = $this->db->query("SELECT nome FROM usuario WHERE id='$para'");
            $ur = $ua->fetch();
            $a_para = $ur[0];

            $this->ocorrencia('MENSAGEM', 'Enviou ', 'Para : ' . $a_para . ' Assunto: ' . $assunto, $resultado, $sql);
            return $id;
        }

        public function excluir($id) {

            $aux = $this->carregar($id);

            $sql = " DELETE FROM agenda WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Excluiu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'], $resultado, $sql);

            return $ret->fetch();
        }

        public function ler($id) {

            $aux = $this->carregar($id);

            $sql = " UPDATE agenda SET situacao = 'Lida " . date('d/m/Y H:i:s') . "',confirmar='' WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Leu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'], $resultado, $sql);

            return $ret->fetch();
        }

        public function mover($id, $pasta) {

            $aux = $this->carregar($id);

            $sql = " UPDATE agenda SET pasta = '$pasta' WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Moveu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'] . ' Para Pasta: ' . $pasta, $resultado, $sql);

            return $ret->fetch();
        }

        public function compromisso_dia($usuario, $data) {

            $sql = " SELECT id FROM agenda WHERE usuario='$usuario' and data='$data' and compromisso != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->rowCount();
        }

    }

}