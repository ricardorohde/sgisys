<?php

include 'conexao.php';

if (!class_exists('site_imagem')) {

    class site_imagem extends conexao {

        public function listar() {

            $sql = " SELECT id FROM site_imagem ORDER BY nome ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $site_imagem = array();
            while ($row = $ret->fetch()) {
                $site_imagem[] = $row[0];
            }
            
            return $site_imagem;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM site_imagem WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function gravar($img, $nome) {

            $sql = " INSERT INTO site_imagem (img, nome) VALUES ('$img', '$nome')";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return;
        }
        
        public function excluir($id) {
            
            $aux = $this->carregar($id);
            
            echo '<br>Apagando : '.$aux['img'].'('.$aux['nome'].')...';
            
            $uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
            $arq = $uploaddir.$aux['img'];
            
            if (file_exists($arq)) {
                unlink($arq);
            }

            $sql = " DELETE FROM site_imagem WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

    }

}
