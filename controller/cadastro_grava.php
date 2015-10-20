<?php
session_start();

include 'usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
$tmp = $_SESSION['tipo_cadastro'] . '_alterar';
if ($usu->$tmp != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
}

include '../model/cadastro.php';
include '../view/func.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="../view/css/fontes.css" rel="stylesheet" />
        <link href="../view/css/base.css" rel="stylesheet" />
        <link href="../view/css/menu.css" rel="stylesheet" />
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="../view/img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Cadastros';
        include '../view/menu.php';
        ?>
        <div id="conteudo">
            <?php
            echo '<h3>Gravando Dados...</h3>';

            if ($_SESSION['tipo_cadastro'] != 'imovel') {
                $cadastro = new cadastro();
                $camps = $cadastro->campo($_SESSION['tipo_cadastro']);
                $tp_camp = $cadastro->tipo_campo($_SESSION['tipo_cadastro']);
                $dados = array();
                for ($i = 0; $i < count($camps); $i++) {
                    if (isset($_REQUEST[$camps[$i]])) {
                        if ($_SESSION['tipo_cadastro'] == 'tabela') {
                            $dados[$camps[$i]] = $_REQUEST[$camps[$i]];
                        } else {
                            $dados[$camps[$i]] = filtra_campo($_REQUEST[$camps[$i]]);
                            if ($tp_camp[$i] == 'DECIMAL') {
                                $dados[$camps[$i]] = br_us($dados[$camps[$i]]);
                            } elseif ($tp_camp[$i] == 'DATE') {
                                if (!empty($_REQUEST[$camps[$i]])) {
                                    $dados[$camps[$i]] = data_encode($dados[$camps[$i]]);
                                }
                            }
                        }
                    } else {
                        $dados[$camps[$i]] = '';
                    }
                }

                if ($_REQUEST['id'] == 'add') {
                    $id = $cadastro->inserir($_SESSION['tipo_cadastro']);
                    $dados['id'] = $id;
                } else {
                    $id = $_REQUEST['id'];
                }
                $cadastro->gravar($_SESSION['tipo_cadastro'], $camps, $dados, $id);
                if ($_SESSION['tipo_cadastro'] == 'cliente') {
                    echo '<script>window.open(\'../view/cliente.php?id=' . $id . '\',\'_self\');</script>';
                } else {
                    echo '<script>window.open(\'../view/cadastro.php?id=' . $id . '\',\'_self\');</script>';
                }
            } else {
                $cadastro = new cadastro();
                $camps = $cadastro->campo_imovel();
                $dados = array();
                foreach ($camps as $camp => $value) {
                    if ($camp == 'exclusividade_ate' || $camp == 'data_captacao' || $camp == 'data_atualizacao' || $camp == 'cadastro_data' || $camp == 'alterado_data' || $camp == 'data_captacao_venda' || $camp == 'data_placa_venda' || $camp == 'data_captacao_locacao' || $camp == 'data_placa_locacao' || $camp == 'prev_fim_locacao' || $camp == 'data_lancamento') {
                        $dados[$camp] = data_encode($_POST[$camp]);
                    } elseif ($camp == 'valor_venda' || $camp == 'valor_locacao' || $camp == 'valor_metro' || $camp == 'valor_iptu' || $camp == 'valor_condominio') {
                        $dados[$camp] = br_us($_POST[$camp]);
                    } else {
                        $dados[$camp] = filtra_campo($_POST[$camp]);
                    }
                }


                //
                
                
                
                //
                // solicitado em 26-06-2015
                //

//                if (empty($dados['descricao'])) {
//                    echo '<script>alert("A Descrição do Imóvel não pode ficar vazia.");</script>';
//                    echo '<script>history.go(-1);</script>';
//                    exit();
//                }



                //


                if ($_REQUEST['id'] == 'add') {
                    $id = $cadastro->inserir($_SESSION['tipo_cadastro']);
                    $dados['id'] = $id;
                } else {
                    $id = $_REQUEST['id'];
                }


                $cadastro->gravar_imovel($dados, $id);
                echo '<script>window.open(\'../view/cadastro.php?id=' . $id . '\',\'_self\');</script>';
            }
            //echo '<a href="#" onclick="window.open(\'../view/cadastro.php?id=' . $id . '\',\'_self\');">voltar</a>';
            ?>
        </div>
    </body>
</html>
