<?php

include 'conexao.php';

if (!class_exists('lista_config')) {

    class lista_config extends conexao {

        public function carregar() {

            $sql = " SELECT * FROM lista_config";
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
            $sql .= " FROM lista_config";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            $sql = " UPDATE lista_config SET ";
            $x = 0;

            foreach ($dados as $campo => $valor) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                $sql .= " $campo = '" . mysql_escape_string($valor) . "' ";
                $x++;
            }
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

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

            $campos[] = 'cor_fonte_padrao';
            $campos[] = 'cor_fonte_destacada';
            $campos[] = 'itenspp';
            $campos[] = 'cor_imovel_background';
            $campos[] = 'cor_filtro_background';
            

            return $campos;
        }

        public function nome_campos() {

            $campos = array();

            $campos[] = 'Cor Fonte Padrão Detalhes';
            $campos[] = 'Cor Fonte Destacada Detalhes';
            $campos[] = 'Qtd Itens por Página';
            $campos[] = 'Cor Fundo Imóvel Lista';
            $campos[] = 'Cor Fundo Filtros Lista';
            

            return $campos;
        }

    }

}