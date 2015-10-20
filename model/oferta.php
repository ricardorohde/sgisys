<?php

include 'conexao.php';

if (!class_exists('oferta')) {

    class oferta extends conexao {

        public function listar_imovel($id) {

            $sql = " SELECT * FROM comprador WHERE id=$id and nome != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {

                $where = '';
                if (!empty($row['tipo_imovel1']) || !empty($row['tipo_imovel2']) || !empty($row['tipo_imovel3'])) {
                    $where .= ' And ( ';
                    if (!empty($row['tipo_imovel1'])) {
                        $where .= " tipo = " . $row['tipo_imovel1'] . " ";
                        if (!empty($row['tipo_imovel2']) || !empty($row['tipo_imovel3'])) {
                            $where .= ' or ';
                        }
                    }
                    if (!empty($row['tipo_imovel2'])) {
                        $where .= " tipo = " . $row['tipo_imovel2'] . " ";
                        if (!empty($row['tipo_imovel3'])) {
                            $where .= ' or ';
                        }
                    }
                    if (!empty($row['tipo_imovel3'])) {
                        $where .= " tipo = " . $row['tipo_imovel3'] . " ";
                    }
                    $where .= ' ) ';
                }
                if (!empty($row['bairro1']) || !empty($row['bairro2']) || !empty($row['bairro3'])) {
                    $where .= ' aNd ( ';
                    if (!empty($row['bairro1'])) {
                        $where .= " bairro LIKE  '%" . $row['bairro1'] . "%' ";
                        if (!empty($row['bairro2']) || !empty($row['bairro3'])) {
                            $where .= ' or ';
                        }
                    }
                    if (!empty($row['bairro2'])) {
                        $where .= " bairro LIKE  '%" . $row['bairro2'] . "%' ";
                        if (!empty($row['bairro3'])) {
                            $where .= ' or ';
                        }
                    }
                    if (!empty($row['bairro3'])) {
                        $where .= " bairro LIKE  '%" . $row['bairro3'] . "%' ";
                    }
                    $where .= ' ) ';
                }                
                $ordem = ' RAND() ';
                
                if ($row['valor_de'] > 0 || $row['valor_ate'] > 0) {
                    $where .= ' anD ( ';
                    if ($row['valor_de'] > 0 && $row['valor_ate'] == 0) {
                        $where .= ' (valor_venda >= ' . $row['valor_de'] . ' OR valor_locacao >= ' . $row['valor_de'] . ') ';
                    }
                    if ($row['valor_de'] == 0 && $row['valor_ate'] > 0) {
                        $where .= ' (valor_venda <= ' . $row['valor_ate'] . ' OR valor_locacao <= ' . $row['valor_ate'] . ') ';
                    }
                    if ($row['valor_de'] > 0 && $row['valor_ate'] > 0) {
                        $where .= ' (valor_venda BETWEEN ' . $row['valor_de'] . ' AND ' . $row['valor_ate'] . ') OR (valor_locacao BETWEEN ' . $row['valor_de'] . ' AND ' . $row['valor_ate'] . ') ';
                    }
                    $where .= ' ) ';
                }
                
                
                if ($row['com_foto'] == 'Sim') {
                    $where .= " and foto != '' and foto != 'sem_foto.jpg' ";
                } 
                
                if ($row['apenas_novidade'] == 'Sim') {
                    $where .= " and novidade='S' ";
                } 
                
                $sql = " SELECT id FROM imovel WHERE situacao = 'Ativo' AND internet = 'Sim' AND (valor_venda > 0 OR valor_locacao > 0) $where ORDER BY $ordem LIMIT 0,20 ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
                $resultado = implode('|', $ret->errorInfo());
                if ($_SESSION['cliente_id'] == '0002' || $_SESSION['cliente_id'] == '0000') {
                    if (substr($resultado, 0, 4) != '0000') {
                        echo '<br>' . $sql . '<br>' . $resultado;
                        sleep(3);
                    }
                }
                $ref = array();
                while ($row = $ret->fetch()) {
                    $ref[] = $row[0];
                }

                return $ref;
            }
        }

        public function carregar_imovel($id) {

            $sql = " SELECT * FROM imovel WHERE id='$id'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function qualidade_imovel($id) {

            $sql = " SELECT * FROM comprador WHERE id=$id and nome != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {

                $where = 'Imóveis disponíveis';
                if (!empty($row['tipo_imovel1']) || !empty($row['tipo_imovel2']) || !empty($row['tipo_imovel3'])) {
                    $where .= ' tipo ';
                    if (!empty($row['tipo_imovel1'])) {
                        $sql = " SELECT tipo FROM imovel_tipo WHERE id=" . $row['tipo_imovel1'] . "  ";
                        $ret = $this->db->prepare($sql);
                        $ret->execute();
                        $aux = $ret->fetch();
                        $tipo = $aux[0];
                        $where .= " <i>$tipo</i>  ";
                        if (!empty($row['tipo_imovel2']) || !empty($row['tipo_imovel3'])) {
                            $where .= ' ou ';
                        }
                    }
                    if (!empty($row['tipo_imovel2'])) {
                        $sql = " SELECT tipo FROM imovel_tipo WHERE id=" . $row['tipo_imovel2'] . "  ";
                        $ret = $this->db->prepare($sql);
                        $ret->execute();
                        $aux = $ret->fetch();
                        $tipo = $aux[0];
                        $where .= " <i>$tipo</i>  ";
                        if (!empty($row['tipo_imovel3'])) {
                            $where .= ' ou ';
                        }
                    }
                    if (!empty($row['tipo_imovel3'])) {
                        $sql = " SELECT tipo FROM imovel_tipo WHERE id=" . $row['tipo_imovel3'] . "  ";
                        $ret = $this->db->prepare($sql);
                        $ret->execute();
                        $aux = $ret->fetch();
                        $tipo = $aux[0];
                        $where .= " <i>$tipo</i>  ";
                    }
                }
                if (!empty($row['bairro1']) || !empty($row['bairro2']) || !empty($row['bairro3'])) {
                    $where .= ' no bairro ';
                    if (!empty($row['bairro1'])) {
                        $where .= "  <i>" . $row['bairro1'] . "</i> ";
                        if (!empty($row['bairro2']) || !empty($row['bairro3'])) {
                            $where .= ' ou ';
                        }
                    }
                    if (!empty($row['bairro2'])) {
                        $where .= " <i>" . $row['bairro2'] . "</i> ";
                        if (!empty($row['bairro3'])) {
                            $where .= ' ou ';
                        }
                    }
                    if (!empty($row['bairro3'])) {
                        $where .= " <i>" . $row['bairro3'] . "</i> ";
                    }
                }
                if ($row['valor_de'] > 0 || $row['valor_ate'] > 0) {
                    $where .= ' e valor ';
                    if ($row['valor_de'] > 0 && $row['valor_ate'] == 0) {
                        $where .= ' maior que <i>' . us_br($row['valor_de']) . '</i> ';
                    }
                    if ($row['valor_de'] == 0 && $row['valor_ate'] > 0) {
                        $where .= ' até <i>' . us_br($row['valor_ate']) . '</i> ';
                    }
                    if ($row['valor_de'] > 0 && $row['valor_ate'] > 0) {
                        $where .= ' entre <i>' . us_br($row['valor_de']) . '</i> e <i>' . us_br($row['valor_ate']) . '</i> ';
                    }
                }
                if ($row['com_foto'] == 'Sim') {
                    $where .= ' com fotos';
                }
                
                if ($row['apenas_novidade'] == 'Sim') {
                    $where .= ' e apenas novidades';
                }
                return $where;
            }
        }

        public function Pesquisa_Ofertas() {
            
            
            $sql = " SELECT id FROM comprador WHERE radar='Sim' and nome != '' ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $saida = array();
            while ($row = $ret->fetch()) {
                
                $id = $row['id'];
                
                $saida[] = $this->listar_imovel($id);
            }
            
            return json_encode($saida);
            
        }
        
        public function sem_novidades() {
            
            
            $sql = " UPDATE imovel SET novidade=''  ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return true;
            
        }

    }

}
    