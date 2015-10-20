<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('mensagem')) {

    class mensagem extends conexao {

        public function carregar($id) {

            $sql = " SELECT * FROM mensagem WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function listar($pasta, $usuario) {

            $sql = " SELECT id FROM mensagem WHERE pasta='$pasta' ";
            if ($pasta == 'Mensagens enviadas') {
                $sql .= " AND de = '$usuario' ";
            } elseif ($pasta == 'Caixa de entrada') {
                $sql .= " AND para = '$usuario' ";
            } else {
                $sql .= " AND (de = '$usuario' OR para = '$usuario') ";
            }
            $sql .= " ORDER BY ts DESC ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $mensagem = array();
            while ($row = $ret->fetch()) {
                $mensagem[] = $row[0];
            }

            return $mensagem;
        }

        public function enviar($de, $para, $assunto, $mensagem, $confirmar) {

            $sql = " INSERT INTO mensagem (id, de, para, assunto, mensagem, envio, confirmar) VALUES ('', '$de', '$para', '$assunto', '$mensagem', '" . date('d/m/Y H:i:s') . "', '$confirmar') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $sql = " INSERT INTO mensagem (id, de, para, assunto, mensagem, envio, pasta, situacao) VALUES ('', '$de', '$para', '$assunto', '$mensagem', '" . date('d/m/Y H:i:s') . "','Mensagens enviadas','Enviado') ";
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

            $sql = " DELETE FROM mensagem WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Excluiu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'], $resultado, $sql);

            return $ret->fetch();
        }

        public function ler($id) {

            $aux = $this->carregar($id);

            $sql = " UPDATE mensagem SET situacao = 'Lida " . date('d/m/Y H:i:s') . "',confirmar='' WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Leu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'], $resultado, $sql);

            return $ret->fetch();
        }

        public function mover($id, $pasta) {

            $aux = $this->carregar($id);

            $sql = " UPDATE mensagem SET pasta = '$pasta' WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MENSAGEM', 'Moveu ' . ' ID:' . $id, 'Assunto: ' . $aux['assunto'] . ' Para Pasta: ' . $pasta, $resultado, $sql);

            return $ret->fetch();
        }

    }

}