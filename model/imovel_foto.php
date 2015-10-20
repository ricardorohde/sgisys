<?php

include 'conexao.php';

if (!class_exists('imovel_foto')) {

    class imovel_foto extends conexao {

        public function listar($ref) {

            $sql = " SELECT id FROM imovel_foto WHERE ref=$ref ORDER BY id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_foto = array();
            while ($row = $ret->fetch()) {
                $imovel_foto[] = $row[0];
            }

            return $imovel_foto;
        }
        
        public function listar2($ref) {

            $sql = " SELECT id FROM imovel_foto WHERE ref=$ref AND fachada='S' ";
            $sql .= " UNION SELECT id FROM imovel_foto WHERE ref=$ref AND fachada!='S' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $imovel_foto = array();
            while ($row = $ret->fetch()) {
                $imovel_foto[] = $row[0];
            }

            return $imovel_foto;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM imovel_foto WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function gravar($ref, $nome) {

            $sql = " INSERT INTO imovel_foto (ref,foto) VALUES ($ref,'$nome')";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $this->testa_fachada($ref);

            return;
        }

        public function excluir($foto) {

            $sql = " SELECT ref FROM imovel_foto WHERE foto LIKE '$foto' LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();
            $id = $row[0];

            $sql = " DELETE FROM imovel_foto WHERE foto LIKE '$foto' LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $this->testa_fachada($id);

            return;
        }

        public function testa_fachada($ref) {

            $sql = " SELECT id FROM imovel_foto WHERE ref=$ref and fachada='S' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            } else {
                $sql = " UPDATE imovel_foto SET fachada='' WHERE ref=$ref ";
                $ret = $this->db->prepare($sql);
                $ret->execute();

                $sql = " UPDATE imovel_foto SET fachada='S' WHERE ref=$ref LIMIT 1 ";
                $ret = $this->db->prepare($sql);
                $ret->execute();

                $sql = " SELECT id,foto,ref FROM imovel_foto WHERE ref=$ref and fachada='S' ";
                $ret = $this->db->prepare($sql);
                $ret->execute();

                $row = $ret->fetch();

                if ($row) {

                    $this->grava_foto_imovel($row['ref'], $row['foto']);

                    return $row[0];
                } else {

                    $this->grava_foto_imovel($ref, 'sem_foto.jpg');

                    return 0;
                }
            }

            return false;
        }

        public function grava_fachada($id) {

            $sql = " SELECT ref FROM imovel_foto WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();
            if ($row) {
                $sql = " UPDATE imovel_foto SET fachada='' WHERE ref='{$row['ref']}' ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
            }

            $sql = " UPDATE imovel_foto SET fachada='S' WHERE id=$id LIMIT 1 ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $sql = " SELECT foto,ref FROM imovel_foto WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {

                $this->grava_foto_imovel($row['ref'], $row['foto']);

                return true;
            }
        }

        public function grava_foto_imovel($ref, $nome_foto = 'sem_foto.jpg') {

            if (empty($nome_foto)) {
                $nome_foto = 'sem_foto.jpg';
            }

            $sql = "update imovel set foto = '$nome_foto' WHERE id='$ref' LIMIT 1 ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
        }

    }

}