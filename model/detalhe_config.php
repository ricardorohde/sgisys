<?php

include 'conexao.php';

if (!class_exists('detalhe_config')) {

    class detalhe_config extends conexao {

        public function carregar() {

            $sql = " SELECT * FROM detalhe_config";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function gravar($dados) {
            $alteracao = '';

            $campos = $this->campos();
            $nome_campo = $this->nome_campos();

            $sql = " SELECT ";
            $x = 0;
            foreach ($campos as $campo) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                $sql .= " $campo";
                $x++;
            }
            $sql .= " FROM detalhe_config";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            $sql = " UPDATE detalhe_config SET ";
            $x = 0;

            foreach ($dados as $campo => $valor) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                $valor = str_replace("'", "∑", $valor);
                $sql .= " $campo = '$valor' ";
                $x++;
            }
            $sql = str_replace('∑', "\\'", $sql);
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());
            if ($_SESSION['cliente_id'] == '0002' || $_SESSION['cliente_id'] == '0000' || $_SESSION['usuario_nome'] == 'ADMIN') {
                if (substr($resultado, 0, 4) != '0000') {
                    echo '<br>' . $resultado . '<br>' . $sql;
                    sleep(3);
                }
            }
            $eol = "\r\n";
            $i = 0;
            foreach ($campos as $campo) {
                if ($row[$campo] != $dados[$campo]) {
                    $alteracao .= " " . $nome_campo[$i] . " de [" . $row[$campo] . "] para [" . $dados[$campo] . "] $eol";
                }
                $i++;
            }

            if (!empty($alteracao)) {
                $this->ocorrencia('CONFIG', 'Alterou Configurações do Site ', $alteracao, $resultado, $sql);
            }

            return;
        }

        public function campos() {

            $campos = array();

            $campos[] = 'detalhe_contato_background';
            $campos[] = 'detalhe_titulo_background';
            $campos[] = 'cor_fonte_padrao';
            $campos[] = 'cor_fonte_titulo';
            $campos[] = 'cor_fonte_destacada';
            $campos[] = 'imoveis_semelhantes';
            $campos[] = 'mostrar_mapa';
            $campos[] = 'mostrar_caracteristica';
            $campos[] = 'mostrar_galeria';
            $campos[] = 'mostrar_contato';
            $campos[] = 'mostrar_descricao';
            $campos[] = 'mostrar_endereco';
            $campos[] = 'pagina_corretor';


            return $campos;
        }

        public function nome_campos() {

            $campos = array();

            $campos[] = 'Cor Fundo Quadro Contato';
            $campos[] = 'Cor Fundo Título Quadro Contato';
            $campos[] = 'Cor Fonte Padrão Detalhes';
            $campos[] = 'Cor Fonte Título Detalhes';
            $campos[] = 'Cor Fonte Destacada Detalhes';
            $campos[] = 'Imóveis Semelhantes';
            $campos[] = 'Mostrar Mapa Bairro Imóvel';
            $campos[] = 'Mostrar Características Imóvel';
            $campos[] = 'Mostrar Galeria de Fotos Imóvel';
            $campos[] = 'Mostrar Formulario Contato Imóvel';
            $campos[] = 'Mostrar Descrição Imóvel';
            $campos[] = 'Mostrar Endereço Imóvel';
            $campos[] = 'Exibir Página do Corretor';


            return $campos;
        }

    }

}