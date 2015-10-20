<?php

include 'conexao.php';

if (!class_exists('portal')) {

    class portal extends conexao {

        public function listar() {

            $sql = " SELECT nome FROM portal ORDER BY nome_completo ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = array();
            while ($row = $ret->fetch()) {
                $id[] = $row[0];
            }

            return $id;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM portal WHERE nome='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function carregar_id($id) {

            $sql = " SELECT nome_completo FROM portal WHERE nome=(SELECT portal FROM publicar WHERE id='$id')";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            return $row[0];
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['codigo_cliente'] = 'Código Cliente';
            $descri['usuario'] = 'email';
            $descri['senha'] = 'senha';
            $descri['url'] = 'URL Fotos';
            $descri['usuario_ftp'] = 'usuário FTP';
            $descri['senha_ftp'] = 'senha FTP';
            $descri['endereco_ftp'] = 'Endereço Servidor FTP';
            $descri['pasta_ftp'] = 'Pasta FTP';
            $descri['enviar_endereco'] = 'Enviar Endereço do imóvel';

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
                $this->ocorrencia('PORTAL', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function publicar_listar($id) {

            $sql = " SELECT id FROM publicar WHERE portal='" . $id . "' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $id = array();
            while ($row = $ret->fetch()) {
                $id[] = $row[0];
            }
            return $id;
        }

        public function publicar_procurar($ref, $id) {

            $sql = " SELECT * FROM publicar WHERE ref='" . $ref . "' AND portal='" . $id . "' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            if ($ret->rowCount() == 1) {
                return true;
            }
            return false;
        }

        public function publicar_carregar($id) {

            $sql = " SELECT * FROM publicar WHERE id='" . $id . "' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function publicar_gravar($ref, $dados) {

            $sql = " DELETE FROM publicar WHERE ref=$ref";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            include 'portal.php';

            $aux = json_decode(portal_listar());
            foreach ($aux as $id) {
                if ($dados[$id] == 'S') {
                    $sql = " INSERT INTO publicar (id, ref, portal) VALUES ('', $ref, '$id')";
                    $ret = $this->db->prepare($sql);
                    $ret->execute();
                }
            }
            return true;
        }

        public function data_envio($id, $tipo_anuncio = '') {

            $sql = " UPDATE publicar SET data_anuncio='" . date('Ymd') . "',tipo_anuncio='$tipo_anuncio' WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $sql = " SELECT portal FROM publicar WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                $sql = " UPDATE portal SET ultimo_envio='" . date('d/m/Y H:i:s') . "' WHERE nome='{$row[0]}'";
                $ret = $this->db->prepare($sql);
                $ret->execute();

                return true;
            }

            return false;
        }
        
        public function total_envio($portal) {

            $sql = " SELECT count(id) FROM publicar WHERE portal='" . $portal . "' and data_anuncio != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();
            
            return $row[0];
        }
        

    }

}