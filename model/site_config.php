<?php

include 'conexao.php';

if (!class_exists('site_config')) {

    class site_config extends conexao {

        public function campos() {

            $campos = array();

            $campos[] = 'nome';
            $campos[] = 'background';
            $campos[] = 'background_altura';
            $campos[] = 'titulo_pagina';
            $campos[] = 'url';
            $campos[] = 'email_envio';
            $campos[] = 'tag_description';
            $campos[] = 'tag_keywords';
            $campos[] = 'imagem_logo';
            $campos[] = 'imagem_altura';
            $campos[] = 'imagem_largura';
            $campos[] = 'imagem_mtop';
            $campos[] = 'imagem_mleft';
            $campos[] = 'script';

            $campos[] = 'pagina_background';
            $campos[] = 'pagina_fonte_padrao';
            $campos[] = 'pagina_cor_padrao';
            $campos[] = 'pagina_fonte_tamanho';

            $campos[] = 'secoes_background';
            $campos[] = 'tipo_destaques';

            $campos[] = 'menu_superior_li';

            $campos[] = 'secao1_tipo';
            $campos[] = 'secao1_background';
            $campos[] = 'secao1_img';

            $campos[] = 'secao2_tipo';
            $campos[] = 'secao2_background';
            $campos[] = 'secao2_atendimento';
            $campos[] = 'secao2_atendimento_fonte';
            $campos[] = 'secao2_atendimento_cor';
            $campos[] = 'secao2_atendimento_tamanho';

            $campos[] = 'secao3_tipo';
            $campos[] = 'secao3_background';
            $campos[] = 'secao3_border_color';
            $campos[] = 'secao3_cor_botao';
            $campos[] = 'secao3_cor_fonte_botao';
            $campos[] = 'secao3_tipocidade';
            $campos[] = 'secao3_tipobairro';
            $campos[] = 'secao3_subtipo';
            $campos[] = 'finalidade_lancamento';

            $campos[] = 'secao4_background';

            $campos[] = 'secao5_tipo';
            $campos[] = 'secao5_img';
            $campos[] = 'secao5_background';
            $campos[] = 'secao5_banner1';
            $campos[] = 'secao5_banner2';
            $campos[] = 'secao5_banner3';
            $campos[] = 'secao5_banner4';
            $campos[] = 'secao5_banner5';
            $campos[] = 'secao5_efeito';
            $campos[] = 'secao5_velocidade_efeito';
            $campos[] = 'secao5_duracao_efeito';
            $campos[] = 'secao5_intervalo_efeito';

            $campos[] = 'secao6_tipo';
            $campos[] = 'secao6_img';
            $campos[] = 'secao6_background';
            $campos[] = 'secao6_cor_fonte';
            $campos[] = 'ordem_padrao';

            $campos[] = 'secao7_tipo';
            $campos[] = 'secao7_img';
            $campos[] = 'secao7_background';
            $campos[] = 'secao7_conteudo';

            $campos[] = 'secao8_tipo';
            $campos[] = 'secao8_img';
            $campos[] = 'secao8_background';
            $campos[] = 'secao8_cor_fonte';

            $campos[] = 'secao9_background';
            $campos[] = 'secao9_tipo';
            $campos[] = 'secao9_img';

            $campos[] = 'rodape_background';
            $campos[] = 'rodape_conteudo';
            $campos[] = 'rodape_css';


            $campos[] = 'servidor_ftp';
            $campos[] = 'usuario_ftp';
            $campos[] = 'senha_ftp';
            $campos[] = 'pasta_ftp';

            return $campos;
        }

        public function nome_campos() {

            $campos = array();

            $campos[] = 'Nome da Empresa';
            $campos[] = 'Background Página';
            $campos[] = 'Altura Background Página';
            $campos[] = 'Título da Página';
            $campos[] = 'Endereço URL Site';
            $campos[] = 'E-mail padrão envio';
            $campos[] = 'Tag [description]';
            $campos[] = 'Tag [keywords]';
            $campos[] = 'Imagem Logo';
            $campos[] = 'Imagem Logo Largura Pxs';
            $campos[] = 'Imagem Logo Altura Pxs';
            $campos[] = 'Imagem Logo Margem Topo Pxs';
            $campos[] = 'Imagem Logo Margem Esquerda Pxs';
            $campos[] = 'Scripts';

            $campos[] = 'Cor Fundo da Página';
            $campos[] = 'Fonte Padrão da Página';
            $campos[] = 'Cor Fonte Padrão da Página';
            $campos[] = 'Tamanho da Fonte Padrão';

            $campos[] = 'Cor Fundo das Seções';
            $campos[] = 'Tipo de Destaques (Imóveis)';

            $campos[] = 'Botões Menu Superior';

            $campos[] = 'Tipo da Seção 1';
            $campos[] = 'Cor Fundo da Seção 1';
            $campos[] = 'Imagem da Seção 1';

            $campos[] = 'Tipo da Seção 2';
            $campos[] = 'Cor Fundo da Seção 2';
            $campos[] = 'Frase de Atendimento da Seção 2';
            $campos[] = 'Fonte Frase de Atendimento da Seção 2';
            $campos[] = 'Cor Frase de Atendimento da Seção 2';
            $campos[] = 'Tamanho Frase de Atendimento da Seção 2';

            $campos[] = 'Tipo Menu da Seção 3';
            $campos[] = 'Cor Fundo da Seção 3';
            $campos[] = 'Cor Borda da Seção 3';
            $campos[] = 'Cor Botão Buscar da Seção 3';
            $campos[] = 'Cor Fonte Botão Buscar da Seção 3';
            $campos[] = 'Tipo Bairro';
            $campos[] = 'Tipo Cidade';
            $campos[] = 'Mostrar Subtipo';
            $campos[] = 'Finalidade exibir Lançamentos';

            $campos[] = 'Cor Fundo da Seção 4';

            $campos[] = 'Tipo da Seção 5';
            $campos[] = 'Imagem da Seção 5';
            $campos[] = 'Cor Fundo da Seção 5';
            $campos[] = 'Imagem 1 Banner Seção 5';
            $campos[] = 'Imagem 2 Banner Seção 5';
            $campos[] = 'Imagem 3 Banner Seção 5';
            $campos[] = 'Imagem 4 Banner Seção 5';
            $campos[] = 'Imagem 5 Banner Seção 5';
            $campos[] = 'Efeito do Banner Seção 5';
            $campos[] = 'Velocidade Efeito do Banner Seção 5';
            $campos[] = 'Duração Efeito do Banner Seção 5';
            $campos[] = 'Intervalo Efeito do Banner Seção 5';

            $campos[] = 'Tipo da Seção 6';
            $campos[] = 'Imagem da Seção 6';
            $campos[] = 'Cor Fundo da Seção 6';
            $campos[] = 'Cor Fonte da Seção 6';
            $campos[] = 'Ordem Padrão';

            $campos[] = 'Tipo da Seção 7';
            $campos[] = 'Imagem da Seção 7';
            $campos[] = 'Cor Fundo da Seção 7';
            $campos[] = 'Conteúdo da Seção 7';

            $campos[] = 'Tipo da Seção 8';
            $campos[] = 'Imagem da Seção 8';
            $campos[] = 'Cor Fundo da Seção 8';
            $campos[] = 'Cor Fonte da Seção 8';

            $campos[] = 'Cor Fundo da Seção 9';
            $campos[] = 'Tipo da Seção 9';
            $campos[] = 'Imagem da Seção 9';

            $campos[] = 'Cor Fundo Rodapé';
            $campos[] = 'Conteúdo do Rodapé';
            $campos[] = 'CSS do Rodapé';



            $campos[] = 'Servidor FTP';
            $campos[] = 'Usuário FTP';
            $campos[] = 'Senha FTP';
            $campos[] = 'Pasta FTP';

            return $campos;
        }

        public function carregar() {

            $sql = " SELECT * FROM site_config";
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
            $sql .= " FROM site_config";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $row = $ret->fetch();

            $sql = " UPDATE site_config SET ";
            $x = 0;

            foreach ($dados as $campo => $valor) {
                if ($x > 0) {
                    $sql .= ', ';
                }
                //$valor = str_replace("'", "∑", $valor);
                $sql .= " $campo = '$valor' ";
                $x++;
            }
            //$sql = str_replace('∑', "\\'", $sql);
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

        public function modelo_grava($modelo) {

            $sql = " UPDATE site_config SET modelo='$modelo'";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return;
        }

    }

}