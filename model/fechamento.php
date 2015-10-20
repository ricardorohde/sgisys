<?php

include 'conexao.php';

if (!class_exists('fechamento')) {

    class fechamento extends conexao {

        public function listar($fechamento_where, $fechamento_order, $fechamento_rows) {

            $sql = " SELECT id FROM fechamento WHERE id > 0 $fechamento_where $fechamento_order $fechamento_rows ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $fechamento = array();
            while ($row = $ret->fetch()) {
                $fechamento[] = $row[0];
            }

            return $fechamento;
        }

        public function carregar($id) {

            $sql = " SELECT * FROM fechamento WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function inserir() {

            $sql = " INSERT INTO fechamento (id, cadastro_data,cadastro_por) VALUES ('','" . date('Y-m-d H:i:s') . "','" . $_SESSION['fechamento_id'] . "') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('MOVIMENTO', 'Inseriu ' . 'ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['proposta'] = 'Número Proposta';
            $descri['data_proposta'] = 'Data Emissão Proposta';
            $descri['validade_proposta'] = 'Data Validade Proposta';
            $descri['valor_proposta'] = 'Valor Proposta';
            $descri['data'] = 'Data Fechamento';
            $descri['corretor1'] = 'Corretor #1';
            $descri['corretor2'] = 'Corretor #2';
            $descri['corretor3'] = 'Corretor #3';
            $descri['corretor4'] = 'Corretor #4';
            $descri['corretor5'] = 'Corretor #5';
            $descri['comissao1'] = '% Corretor #1';
            $descri['comissao2'] = '% Corretor #2';
            $descri['comissao3'] = '% Corretor #3';
            $descri['comissao4'] = '% Corretor #4';
            $descri['comissao5'] = '% Corretor #5';
            $descri['valor_comissao1'] = 'Comissão Corretor #1';
            $descri['valor_comissao2'] = 'Comissão Corretor #2';
            $descri['valor_comissao3'] = 'Comissão Corretor #3';
            $descri['valor_comissao4'] = 'Comissão Corretor #4';
            $descri['valor_comissao5'] = 'Comissão Corretor #5';
            $descri['comissao_imobiliaria'] = '% Comissão Imobiliária';
            $descri['valor_comissao_imobiliaria'] = 'Valor Comissão Imobiliária';
            $descri['data_pagamento_imobiliaria'] = 'Data Pagamento Imobiliária';
            $descri['forma_pagamento1'] = 'Forma Pagamento #1';
            $descri['forma_pagamento2'] = 'Forma Pagamento #2';
            $descri['forma_pagamento3'] = 'Forma Pagamento #3';
            $descri['valor_pagamento1'] = 'Valor Pagamento #1';
            $descri['valor_pagamento2'] = 'Valor Pagamento #1';
            $descri['valor_pagamento3'] = 'Valor Pagamento #1';
            $descri['data_pagamento1'] = 'Data Pagamento #1';
            $descri['data_pagamento2'] = 'Data Pagamento #2';
            $descri['data_pagamento3'] = 'Data Pagamento #3';
            $descri['comprador'] = 'Nome Comprador';
            $descri['proprietario'] = 'Nome Proprietário';
            $descri['endereco'] = 'Endereço Imóvel';
            $descri['ref'] = 'Referência Imóvel';
            $descri['valor'] = 'Valor Fechamento';

            $desc = '';
            $eol = "\r\n";
            $sql = " UPDATE fechamento SET id=$id ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value) {
                    $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                }
            }
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());
            //echo $sql.$resultado;exit();

            if (!empty($desc)) {
                $this->ocorrencia('FECHAMENTO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function excluir($id) {

            $sql = " DELETE FROM fechamento WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('MOVIMENTO', 'Excluiu ' . ' ID:' . $id, '', $resultado, $sql);

            return $ret->fetch();
        }

    }

}
