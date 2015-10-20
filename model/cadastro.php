<?php

include 'conexao.php';


if (!class_exists('cadastro')) {

    class cadastro extends conexao {

        public function listar($tipo, $where, $order, $rows) {

            $sql = " SELECT id FROM $tipo WHERE id > 0 $where $order $rows "; 
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $cadastro = array();
            while ($row = $ret->fetch()) {
                $cadastro[] = $row[0];
            }

            return $cadastro;
        }

        public function carregar($tipo, $id) {

            $sql = " SELECT * FROM $tipo WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function inserir($tipo) {

            $nome_campo = $this->nome_campo($tipo);

            $sql = " INSERT INTO $tipo (id, cadastro_data,cadastro_por) VALUES ('','" . date('Y-m-d H:i:s') . "','" . $_SESSION['usuario_id'] . "') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('CADASTRO', 'Inseriu ' . mb_convert_case($tipo, MB_CASE_TITLE) . ' ID: ' . $id, '', $resultado, $sql);
            return $id;
        }

        public function gravar($tipo, $campos, $dados, $id) {

            $alteracao = '';

            $nome_campo = $this->nome_campo($tipo);

            $tp_camp = $this->tipo_campo($_SESSION['tipo_cadastro']);

            $sql = " SELECT ";
            $x = 0;
            foreach ($campos as $campo) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                $sql .= " $campo";
                $x++;
            }
            $sql .= " FROM $tipo WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            $sql = " UPDATE $tipo SET ";
            $x = 0;

            foreach ($dados as $campo => $valor) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                $sql .= " $campo = '$valor' ";
                $x++;
            }
            $sql .= ", alterado_data = NOW() ";
            $sql .= ", alterado_por = '" . $_SESSION['usuario_id'] . "' ";
            $sql .= " WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());
            if (empty($id)) {
                echo '<br>Adicionar ID estrutura de tabelas';
                sleep(2);
            }
            if (substr($resultado, 0, 4) != '0000') {
                echo '<br>' . $resultado . '<br>' . $sql;
                sleep(5);
            }
            $eol = "\r\n";
            $i = 0;
            foreach ($campos as $campo) {
                if (($row[$campo] != $dados[$campo]) && (!empty($row[$campo]) || !empty($dados[$campo]))) {
                    $de = $row[$campo];
                    $para = $dados[$campo];
                    if ($tp_camp[$i] == 'DECIMAL') {
                        $de = us_br($de);
                        $para = us_br($para);
                    }
                    if ($tp_camp[$i] == 'DATE') {
                        $de = data_decode($de);
                        $para = data_decode($para);
                    }
                    $alteracao .= " " . $nome_campo[$i] . " de [" . $de . "] para [" . $para . "] $eol";
                }
                $i++;
            }

            if (!empty($alteracao)) {
                $this->ocorrencia('CADASTRO', 'Alterou ' . mb_convert_case($tipo, MB_CASE_TITLE) . ' ID:' . $id, $alteracao, $resultado, $sql);
            }

            if ($_SESSION['tipo_cadastro'] == 'imovel') {
                $sql = "update imovel a set a.tipo = (select b.id FROM imovel_tipo b where b.tipo LIKE a.tipo_nome) ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
                
                $sql = "update imovel a set a.subtipo = (select b.id FROM imovel_subtipo b where b.subtipo LIKE a.subtipo_nome) ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
                
                include 'imovel_foto.php';
                $imovel_foto = new imovel_foto();
                $imovel_foto->testa_fachada($id);
                
            }

            return; //'x'
        }

        public function excluir($tipo, $id) {

            $sql = " DELETE FROM $tipo WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());

            $this->ocorrencia('CADASTRO', 'Excluiu ' . $_SESSION['nome_tipo_cadastro'] . ' ID:' . $id, '', $resultado, $sql);

            return $ret->fetch();
        }

        public function nome_campo($tipo) {

            $sql = " SELECT campo_nome FROM tabela WHERE nome='$tipo' ORDER BY campo_grupo, campo";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $cadastro = array();
            while ($row = $ret->fetch()) {
                $cadastro[] = $row[0];
            }

            return $cadastro;
        }

        public function campo($tipo) {

            $sql = " SELECT campo FROM tabela WHERE nome='$tipo' ORDER BY campo_grupo, campo";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $cadastro = array();
            while ($row = $ret->fetch()) {
                $cadastro[] = $row[0];
            }

            return $cadastro;
        }

        public function tipo_campo($tipo) {

            $sql = " SELECT campo_tipo FROM tabela WHERE nome='$tipo' ORDER BY campo_grupo, campo";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $cadastro = array();
            while ($row = $ret->fetch()) {
                $cadastro[] = $row[0];
            }

            return $cadastro;
        }

        public function tamanho_campo($tipo) {

            $sql = " SELECT campo_tamanho FROM tabela WHERE nome='$tipo' ORDER BY campo_grupo, campo";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $cadastro = array();
            while ($row = $ret->fetch()) {
                $cadastro[] = $row[0];
            }

            return $cadastro;
        }

        public function foto_gravar($tipo, $id, $foto) {

            $sql = " UPDATE $tipo SET foto='$foto' WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return;
        }

        public function gravar_imovel($dados, $id) {

            $desc = '';
            $eol = "\r\n";

            $sql = " SELECT * FROM imovel WHERE id=$id ";
            $ret = $this->db->query($sql);
            $aux = $ret->fetch();

            $camps = $this->campo_imovel();
            $sql = " UPDATE imovel SET id=$id ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value && !empty($aux[$key]) && !empty($value)) {
                    if ($key == 'acesso') {

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $aux['acesso'] . "'");
                        $ur = $ua->fetch();
                        $a_acesso = $ur[0];

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $dados['acesso'] . "'");
                        $ur = $ua->fetch();
                        $acesso = $ur[0];

                        $desc .= $eol . 'Alterou ' . $camps[$key] . ' de [' . $a_acesso . '] para [' . $acesso . ']';
                    } else {
                        $desc .= $eol . 'Alterou ' . $camps[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                    }
                }
            }
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            if (substr($resultado, 0, 4) != '0000') {
                echo '<br>Imóvel: ' . $resultado . '<br>' . $sql;
                sleep(5);
            }

            if (!empty($desc)) {
                $this->ocorrencia('IMÓVEL', 'Alterou Imóvel ' . 'Ref: ' . $id, $desc, $resultado, $sql);
            }

            $sql = "update imovel a set a.tipo = (select b.id FROM imovel_tipo b where b.tipo LIKE a.tipo_nome) ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $sql = "update imovel a set a.foto = (select b.foto FROM imovel_foto b where b.ref=a.id) ORDER BY ID LIMIT 1 ";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return;
        }

        function campo_imovel() {

            $campos = array();

            $campos['tipo_nome'] = 'Tipo Imóvel';
            $campos['localizacao'] = 'Localização';
            $campos['condominio'] = 'Condominío';
            $campos['cep'] = 'CEP';
            $campos['tipo_logradouro'] = 'Tipo Logradouro';
            $campos['logradouro'] = 'Logradouro';
            $campos['numero'] = 'Número';
            $campos['bairro'] = 'Bairro';
            $campos['cidade'] = 'Cidade';
            $campos['uf'] = 'UF';
            $campos['subtipo_nome'] = 'Subtipo';
            $campos['complemento'] = 'Complemento';
            $campos['area_terreno'] = 'Area Terreno';
            $campos['area_total'] = 'Area Total';
            $campos['area_util'] = 'Area Útil';
            $campos['area_escritorio'] = 'Area Escritório';
            $campos['area_galpao'] = 'Area Galpão';
            $campos['area_fabril'] = 'Area Fabril';
            $campos['area_patio'] = 'Area Pátio';
            $campos['vao_livre'] = 'Vão Livre';
            $campos['cabine_primaria'] = 'Cabine Primária';
            $campos['numero_galpao'] = 'Número Galpão';
            $campos['forca_tri'] = 'Força Trifásica';
            $campos['torre'] = 'Torre';
            $campos['conjunto'] = 'Conjunto';
            $campos['quadra'] = 'Quadra';
            $campos['lote'] = 'Lote';
            $campos['andar'] = 'Andar';
            $campos['video_youtube'] = 'Vídeo Youtube';
            $campos['topografia'] = 'Topografia';
            $campos['metragem'] = 'Metragem';
            $campos['aps_por_andar'] = 'Aps Por Andar';
            $campos['vagas_visitante'] = 'Vagas para Visitante';
            $campos['area_construida'] = 'Area Construída';
            $campos['exclusividade_ate'] = 'Exclusividade Até';
            $campos['edificio'] = 'Edifício';
            $campos['numero_apartamento'] = 'Número Apartamento';
            $campos['proprietario'] = 'Proprietário';
            $campos['finalidade'] = 'Finalidade';
            $campos['obra'] = 'Obra';
            $campos['estado_construcao'] = 'Estado Construção';
            $campos['ano_construcao'] = 'Ano Construção';
            $campos['ano_reforma'] = 'Ano Reforma';
            $campos['sala'] = 'Sala';
            $campos['foto'] = 'Foto';
            $campos['dormitorio'] = 'Dormitório';
            $campos['suite'] = 'Suíte';
            $campos['banheiro'] = 'Banheiro';
            $campos['garagem'] = 'Garagem';
            $campos['frente'] = 'Frente';
            $campos['fundos'] = 'M2 Fundos';
            $campos['situacao'] = 'Situação';
            $campos['chaves'] = 'Chaves';
            $campos['valor_locacao'] = 'Valor Locação';
            $campos['valor_venda'] = 'Valor Venda';
            $campos['para_locacao'] = 'Para Locação';
            $campos['para_venda'] = 'Para Venda';
            $campos['valor_iptu'] = 'Valor IPTU';
            $campos['valor_condominio'] = 'Valor Condomínio';
            $campos['valor_metro'] = 'Valor M²';
            $campos['pe_direito'] = 'Pé Direito';
            $campos['permuta'] = 'Permuta por';
            $campos['internet'] = 'Publicar Internet';
            $campos['destaque'] = 'Destaque/oferta';
            $campos['observacao'] = 'Observação';
            $campos['captador1'] = 'Captador1';
            $campos['captador2'] = 'Captador2';
            $campos['descricao'] = 'Descricao';
            $campos['pendencias'] = 'Pendências';
            $campos['zoneamento'] = 'Zoneamento';
            $campos['condicoes_pagamento'] = 'Condições de Pagamento';
            $campos['captado_por'] = 'Captado por';
            $campos['captado_venda_por'] = 'Captado (Venda) por';
            $campos['data_captacao'] = 'Data Captação';
            $campos['data_captacao_venda'] = 'Data Captação (Venda)';
            $campos['placa_venda'] = 'Placa (Venda)';
            $campos['data_placa_venda'] = 'Data placa (Venda)';
            $campos['participante_venda'] = 'Participante (Venda)';
            $campos['captado_locacao_por'] = 'Captador (Locação) por';
            $campos['data_captacao_locacao'] = 'Data Captação (Venda)';
            $campos['placa_locacao'] = 'Placa (Locação)';
            $campos['data_placa_locacao'] = 'Data placa (Locação)';
            $campos['participante_locacao'] = 'Participante (Locação)';
            $campos['prev_fim_locacao'] = 'Previsão Fim Locação';
            $campos['atualizado_por'] = 'Atualizado Por';
            $campos['alterado_por'] = 'Alterado Por';
            $campos['data_atualizacao'] = 'Data Atualização';
            $campos['cadastro_por'] = 'Cadastrado Por';
            $campos['cadastro_data'] = 'Data Cadastramento';
            $campos['alterado_data'] = 'Data Alteração';
            $campos['cadastro_hora'] = 'Hora Cadastramento';
            $campos['lancamento'] = 'Lançamento';
            $campos['data_lancamento'] = 'Data Lançamento';
            $campos['construtora'] = 'Construtora';
            $campos['nunca_usado'] = 'Nunca Usado';
            $campos['profundidade'] = 'M2 Profundidade';
            $campos['m2_frente'] = 'M2 Frente';
            $campos['escritorio'] = 'Escritório';
            $campos['piscina'] = 'Piscina';

            return $campos;
        }

    }

}
