<?php

include 'conexao.php';
include '../controller/funcoes.php';

if (!class_exists('usuario')) {

    class usuario extends conexao {

        public function autenticar($user, $password) {
            if (empty($user) || empty($password)) {
                return false;
            }
            $password = criptog($password);
            $_SESSION['usuario_nome'] = $user;
            $sql = " SELECT * FROM usuario WHERE nome = '$user' AND senha='$password'";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            if ($ret->rowCount() == 0) {
                $sql = " SELECT * FROM usuario WHERE nome = '$user' ";
                $ret = $this->db->prepare($sql);
                $ret->execute();
                if ($ret->rowCount() == 1) {
                    $row = $ret->fetch();
                    $usuario_id = $row['id'];

                    if ($row['bloqueado'] == 'S') {
                        $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : USUÁRIO BLOQUEADO', $_SERVER['HTTP_USER_AGENT']);
                        return 'Usuário Bloqueado.';
                    }

                    if ($row['login_erro'] > 2) {
                        $sql = " UPDATE usuario SET bloqueado='S' WHERE id = '$usuario_id'";
                        $ret = $this->db->prepare($sql);
                        $ret->execute();
                        $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : USUÁRIO BLOQUEADO POR TENTATIVAS', $_SERVER['HTTP_USER_AGENT']);
                        return 'Usuário Bloqueado por número de tentativas.';
                    }

                    $sql = " UPDATE usuario SET login_erro = (login_erro + 1) WHERE id = '$usuario_id'";
                    $ret = $this->db->prepare($sql);
                    $ret->execute();
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : SENHA INVÁLIDA', $_SERVER['HTTP_USER_AGENT']);
                } else {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : USUÁRIO INVÁLIDO', $_SERVER['HTTP_USER_AGENT']);
                }
                return 'Usuário ou Senha inválidos.';
            } else {
                // usuario e senha ok
                $row = $ret->fetch();
                $usuario_id = $row['id'];

                if ($row['bloqueado'] == 'S') {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : USUÁRIO BLOQUEADO', $_SERVER['HTTP_USER_AGENT']);
                    return 'Usuário Bloqueado.';
                }

                if ($row['login_erro'] > 2) {
                    $sql = " UPDATE usuario SET bloqueado='S' WHERE id = '$usuario_id'";
                    $ret = $this->db->prepare($sql);
                    $ret->execute();
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : USUÁRIO BLOQUEADO POR TENTATIVAS', $_SERVER['HTTP_USER_AGENT']);
                    return 'Usuário Bloqueado por número de tentativas.';
                }

                if (empty($row['seg_sex_hi']) || empty($row['seg_sex_hf']) || empty($row['sab_hi']) || empty($row['sab_hf']) || empty($row['dom_hi']) || empty($row['dom_hf'])) {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : HORÁRIOS PERMITIDOS NÃO DEFINIDOS CORRETAMENTE', $_SERVER['HTTP_USER_AGENT']);
                    return 'Horários permitidos não definidos corretamente, peça ao Gerente preencher todos os campos de horários permitidos.';
                }

                if ((date('w') >= 1 and date('w') <= 5) and ( (str_replace(':', '', $row['seg_sex_hi']) > date('Hi')) || (str_replace(':', '', $row['seg_sex_hf']) < date('Hi')) )) {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : HORÁRIO/DIA NÃO PERMITIDO', $_SERVER['HTTP_USER_AGENT']);
                    return 'Horário não permitido. Agora são ' . date('H:i') . ' e o permitido é de Segunda à Sexta, das ' . $row['seg_sex_hi'] . ' até ' . $row['seg_sex_hf'];
                }

                if (date('w') == 6 and ( (str_replace(':', '', $row['sab_hi']) > date('Hi')) || (str_replace(':', '', $row['sab_hf']) < date('Hi')) )) {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : HORÁRIO/DIA NÃO PERMITIDO', $_SERVER['HTTP_USER_AGENT']);
                    return 'Horário não permitido. Agora são ' . date('H:i') . ' e o permitido é de Sábado das ' . $row['sab_hi'] . ' até ' . $row['sab_hf'];
                }

                if (date('w') == 0 and ( (str_replace(':', '', $row['dom_hi']) > date('Hi')) || (str_replace(':', '', $row['dom_hf']) < date('Hi')) )) {
                    $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : HORÁRIO/DIA NÃO PERMITIDO', $_SERVER['HTTP_USER_AGENT']);
                    return 'Horário não permitido. Agora são ' . date('H:i') . ' e o permitido é de Domingo das ' . $row['dom_hi'] . ' até ' . $row['dom_hf'];
                }

                if (!empty($row['ip1']) || !empty($row['ip2'])) {
                    if ($row['ip1'] != $_SERVER['REMOTE_ADDR'] || $row['ip2'] != $_SERVER['REMOTE_ADDR']) {
                        $this->ocorrencia('LOGIN', 'USUÁRIO TENTANDO LOGAR-SE : IP NÃO PERMITIDO', $_SERVER['HTTP_USER_AGENT']);
                        $sql = " UPDATE usuario SET login_erro = (login_erro + 1) WHERE id = '$usuario_id'";
                        $ret = $this->db->prepare($sql);
                        $ret->execute();
                        return 'Endereço IP não permitido';
                    }
                }

                //sucesso

                $assinatura = md5(uniqid(""));
                $sql = " UPDATE usuario SET login_ok = (login_ok + 1),login_erro=0,ultimo_login='" . date('d/m/Y H:i:s') . "',assinatura='$assinatura',reset='' WHERE id = '$usuario_id'";
                $ret = $this->db->prepare($sql);
                $ret->execute();

                $rwus = $this->carregar($row['id']);

                if ($_SESSION['usuario_assinatura'] != $row->assinatura) {
                    die('Erro de autenticacao');
                }


                $_SESSION['usuario_assinatura'] = $assinatura;
                $this->set($row['id'], $row['nome'], $row['foto'], $assinatura);

                $this->ocorrencia('LOGIN', 'USUÁRIO ENTRANDO NO SISTEMA', $_SERVER['HTTP_USER_AGENT']);
                return 'OK';
            }
        }

        public function set($id, $nome, $foto, $assinatura = '') {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_foto'] = $foto;
        }

        public function get() {
            $user = array();
            $user['id'] = $_SESSION['usuario_id'];
            $user['nome'] = $_SESSION['usuario_nome'];
            $user['foto'] = $_SESSION['usuario_foto'];
            $user['assinatura'] = $_SESSION['usuario_assinatura'];
        }

        public function carregar($id) {

            $sql = " SELECT * FROM usuario WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            return $ret->fetch();
        }

        public function carregar_id($nome) {

            $sql = " SELECT id FROM usuario WHERE nome='$nome';";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            }

            return false;
        }

        public function foto_gravar($id, $foto) {

            $aux = $this->carregar($id);

            $a_foto = $aux['foto'];

            $sql = " UPDATE usuario SET foto='$foto' WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            if ($a_foto != $foto) {
                $desc .= $eol . 'Alterou Foto de [' . $a_foto . '] para [' . $foto . ']';
                $this->ocorrencia('USUÁRIO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }

            return;
        }

        public function senha_gravar($id, $password_atual, $nova_senha) {

            if (!empty($password_atual)) {
                $password_atual = criptog($password_atual);
                $nova_senha = criptog($nova_senha);

                $aux = $this->carregar($id);
                if ($aux['senha'] != $password_atual) {
                    return false;
                }
            } else {
                $nova_senha = criptog('123456');
            }

            $sql = " UPDATE usuario SET senha='$nova_senha',reset='',bloqueado='',login_erro=0 WHERE id=$id";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());
            if (empty($id)) {
                echo '<br>Adicionar ID estrutura de tabelas';
                sleep(2);
            }
            if ($_SESSION['usuario_nome'] == 'ADMIN') {
                if (substr($resultado, 0, 4) != '0000') {
                    echo '<br>' . $resultado . '<br>' . $sql;
                    sleep(3);
                }
            }

            $desc .= $eol . 'Alterou Senha';
            $this->ocorrencia('USUÁRIO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);

            return true;
        }

        public function listar($where, $order, $rows) {

            if (empty($order)) {
                $order = 'nome';
            }
            if (empty($rows)) {
                $rows = '0,20';
            }

            $sql = " SELECT id FROM usuario WHERE id>0 $where ORDER BY $order LIMIT $rows";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $user = array();
            while ($row = $ret->fetch()) {
                $user[] = $row[0];
            }

            return $user;
        }

        public function gravar($id, $dados) {

            $aux = $this->carregar($id);

            $descri = array();

            $descri['acesso'] = 'Acesso';
            $descri['home'] = 'Página Home';
            //$descri['home1'] = 'Home #1';
            //$descri['home2'] = 'Home #2';
            //$descri['home3'] = 'Home #3';
            $descri['foto'] = 'Foto';
            $descri['seg_sex_hi'] = 'Horário de Seg a Sex hora inicial';
            $descri['seg_sex_hf'] = 'Horário de Seg a Sex hora final';
            $descri['sab_hi'] = 'Horário de Sab hora inicial';
            $descri['sab_hf'] = 'Horário de Sab hora final';
            $descri['dom_hi'] = 'Horário de Dom hora inicial';
            $descri['dom_hf'] = 'Horário de Dom hora final';
            $descri['ip1'] = 'Endereço IP #1';
            $descri['ip2'] = 'Endereço IP #2';

            $desc = '';
            $eol = "\r\n";

            $sql = " UPDATE usuario SET id=$id ";
            foreach ($dados as $key => $value) {
                $sql .= ", $key='$value' ";
                if ($aux[$key] != $value) {
                    if ($key == 'acesso') {

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $aux['acesso'] . "'");
                        $ur = $ua->fetch();
                        $a_acesso = $ur[0];

                        $ua = $this->db->query("SELECT acesso FROM usuario_acesso WHERE id='" . $dados['acesso'] . "'");
                        $ur = $ua->fetch();
                        $acesso = $ur[0];

                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $a_acesso . '] para [' . $acesso . ']';
                    } else {
                        $desc .= $eol . 'Alterou ' . $descri[$key] . ' de [' . $aux[$key] . '] para [' . $value . ']';
                    }
                }
            }
            $sql .= " WHERE id=$id ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            if ($_SESSION['usuario_nome'] == 'ADMIN') {
                if (substr($resultado, 0, 4) != '0000') {
                    echo '<br>' . $resultado . '<br>' . $sql;
                    sleep(3);
                }
            }

            if (!empty($desc)) {
                $this->ocorrencia('USUÁRIO', 'Alterou ' . 'ID: ' . $id, $desc, $resultado, $sql);
            }
            return;
        }

        public function inserir($nome) {

            $sql = " INSERT INTO usuario (id, nome, senha,cadastro_data,cadastro_por) VALUES ('','$nome','ReDfp6mzF2Uo.' ,'" . date('Y-m-d H:i:s') . "','" . $_SESSION['usuario_id'] . "') ";
            $ret = $this->db->prepare($sql);
            $ret->execute();
            $resultado = implode('|', $ret->errorInfo());

            $id = $this->db->lastInsertId('id');

            $this->ocorrencia('USUÁRIO', 'Inseriu ' . 'ID: ' . $id, 'Nome: ' . $nome, $resultado, $sql);
            return $id;
        }

        public function excluir($id) {

            $aux = $this->carregar($id);

            $nome = $aux['nome'];
            $alt = $aux['bloqueado'];

            if ($alt == 'S') {
                $alt = 'N';
                $oco = 'Desbloqueou ';
            } else {
                $alt = 'S';
                $oco = 'Bloqueou ';
            }

            $sql = " UPDATE usuario SET bloqueado='$alt',login_erro=0 WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());
            $this->ocorrencia('USUÁRIO', $oco . ' ID:' . $id, 'Nome: ' . $nome, $resultado, $sql);

            return $ret->fetch();
        }

        public function gravar_reset($id, $hash) {

            $sql = " UPDATE usuario SET reset='$hash',login_erro=0,bloqueado='' WHERE id=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $resultado = implode('|', $ret->errorInfo());
            $this->ocorrencia('USUÁRIO', 'Solicita RESET Senha - ID:' . $id, 'Nome: ' . $nome, $resultado, $sql);

            return;
        }

        public function carregar_reset($hash) {

            $sql = " SELECT id FROM usuario WHERE reset='$hash' LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            }

            return false;
        }

        public function usuario_corretor($id) {

            $sql = " SELECT id FROM corretor WHERE usuario=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            }

            return false;
        }
        
        public function usuario_corretor_restrito($id) {

            $sql = " SELECT restrito FROM corretor WHERE usuario=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            }

            return false;
        }
        
        public function usuario_corretor_nome($id) {

            $sql = " SELECT nome FROM corretor WHERE usuario=$id LIMIT 1";
            $ret = $this->db->prepare($sql);
            $ret->execute();

            $row = $ret->fetch();

            if ($row) {
                return $row[0];
            }

            return false;
        }

    }

}