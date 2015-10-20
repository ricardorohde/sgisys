<?php
session_start();

date_default_timezone_set('America/Sao_Paulo');
ini_set('register_globals', 'off');
ini_set('display_errors', 'on');

$cliente_id = $_GET['cliente_id'];
$_SESSION['cliente_id'] = $cliente_id;

//require_once('../controller/ses.php');
include 'func.php';

include '../model/conexao.php';
include '../controller/cadastro.php';
include '../controller/oferta.php';
include '../controller/site_config.php';

$conexao = new conexao();
$ret = $conexao->db->query(" SELECT * FROM site_config ");

if (!$ret) {
    die('Tabela de Config do Site não encontrada.');
}

$site_config = $ret->fetch();

$nome = $site_config['nome'];

//$ses = new SimpleEmailService('AKIAIZZEDKTEVHUBVL2Q', 'xpES7BqktVjHyWoaO+jE/Wfmkr6P0K227btoslqO');

$ofertas = 0;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
    </head>
    <body>
        <br>
        <br>
        <?php
        $envio = '';

        $cmp = $conexao->db->query(" SELECT * FROM comprador WHERE nome != '' and email != '' and avisar_cliente='Sim' and radar='Sim' ");

        if (!$cmp) {
            die('Tabela de Compradores não encontrada.');
        }

        $tot = $cmp->rowCount();

        $envio .= '<p><br>' . $nome . ' : ' . $tot . ' compradores</br></p>';
        $ok = 0;
        while ($row = $cmp->fetch()) {
            $envio .= '<br>&nbsp;' . $row['nome'] . ' : ' . $row['email'];

            $site_config = json_decode(site_config_carregar());

            $saida = '';
            if (!empty($site_config->imagem_logo)) {
                $saida .= '<img src="http://sgiplus.com.br/site/fotos/' . $_SESSION['cliente_id'] . '/' . $site_config->imagem_logo . '">';
            }

            $ref = $row['id'];

            $comprador = json_decode(cadastro_carregar('comprador', $ref));
            $corretor = json_decode(cadastro_carregar('corretor', $comprador->corretor));

            $saida .= 'Prezado(a) Sr.(a) ' . $comprador->nome . ', ';
            $saida .= '<br>Conforme suas preferências, segue algumas ofertas que enquadram nos seus requisitos: ';
            $saida .= oferta_qualidade_imovel($ref);
            //
            $aux = json_decode(oferta_listar_imovel($ref));

            if ($comprador->avisar_corretor == 'Sim' && $comprador->corretor != '' && $corretor->usuario > 0) {
                $aviso_corretor = 'Os seguintes imóveis atendem as condições do Cliente : ' . $comprador->nome . '<br><br><br>';
            }

            if ($aux) {
                $tot = count($aux);

                $ofertas += $tot;

                $saida .= '<table width="100%" style="border: none;margin:10px;">';
                foreach ($aux as $id) {
                    $imov = json_decode(oferta_carregar_imovel($id));
                    $saida .= '<tr>';
                    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 0px;" width="180"><center>';
                    if (!empty($site_config->url)) {
                        $saida .= '<a href="' . $site_config->url . '/index.php?q=Ref&id=' . $imov->id . '" target="_blank">';
                    }
                    $saida .= '  <img src="http://sgiplus.com.br/site/fotos/' . $_SESSION['cliente_id'] . '/' . $imov->foto . '" width="180" height="150">';
                    if (!empty($site_config->url)) {
                        $saida .= '</a>';
                    }
                    $saida .= '  </center></td>';
                    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;">';
                    $saida .= '  Referência : ' . $imov->id . '  ';
                    $saida .= '  <br><center><strong>' . $imov->tipo_nome . ' em ' . $imov->bairro . '/' . $imov->cidade . '</strong></center>';
                    $saida .= '  <br>' . $imov->descricao . '  ';
                    $saida .= '  </td>';
                    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;" width="150">';
                    if ($imov->dormitorio > 0) {
                        $saida .= '  <br>Dorms ' . $imov->dormitorio . '  ';
                    }
                    if ($imov->banheiro > 0) {
                        $saida .= '  <br>Banheiros ' . $imov->banheiro . '  ';
                    }
                    if ($imov->garagem > 0) {
                        $saida .= '  <br>Vagas ' . $imov->garagem . '  ';
                    }
                    if ($imov->area_util > 0) {
                        $saida .= '  <br>Area Util ' . $imov->area_util . 'm²  ';
                    }
                    if ($imov->valor_venda > 0) {
                        $saida .= '  <p align="right">Venda R$' . us_br($imov->valor_venda) . '&nbsp;&nbsp;</p>';
                    }
                    if ($imov->valor_locacao > 0) {
                        $saida .= '  <p align="right">Locação R$' . us_br($imov->valor_locacao) . '&nbsp;&nbsp;</p>';
                    }
                    $saida .= '  </td>';
                    $saida .= '</tr>';

                    if (!empty($aviso_corretor)) {
                        $aviso_corretor .= '<br>Ref.: ' . str_pad($imov->id, 6, '0', 0) . ' - ' . $imov->tipo_nome . ' em ' . $imov->bairro . '/' . $imov->cidade . ' Endereço: ' . $imov->tipo_logradouro . ' ' . $imov->logradour . ' ' . $imov->numero;
                    }
                }
                $saida .= '</table>';

                $envio .= ' - ' . $tot . ' oferta(s) encontrada(s).';

                $saida .= '<br>Atenciosamente,  ' . $corretor->nome;

                $from = $site_config->email_envio;


                $assunto = 'Envio de Ofertas';

                if (strpos($row['email'], '@hotmail')) {

                    require('ses.php');

                    $ses = new SimpleEmailService('AKIAJW7PZOSMEKG7WNGA', 'AqVmS/oLnSJUX5KEoL6AE6FdCKGvpnLILnlKXCBDVdAx');
                    $m = new SimpleEmailServiceMessage();

                    $m->addTo($row['email']);
                    $m->setFrom('envios@sgifacil.com.br');
                    $m->setSubjectCharset('UTF-8');
                    $m->setMessageCharset('UTF-8');
                    $m->setSubject('=?UTF-8?B?' . base64_encode($assunto) . '?= ');
                    $m->setMessageFromString(NULL, $mensagem);

                    if (!$ses->sendEmail($m)) {
                        echo 'Não foi possível enviar o e-mail.';
                    } else {
                        $ok++;
                    }
                } else {

                    $headers = "MIME-Version: 1.1\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: $from\r\n";
                    $headers .= "Return-Path: $from\r\n";
                    $envio = mail($row['email'], "$assunto", "$saida", $headers);

                    if ($envio) {
                        $ok++;
                    } else {
                        echo ' <br> Erro ao enviar E-mail : ' . $row['email'];
                    }
                }

//                $m = new SimpleEmailServiceMessage();
//
//                $m->addTo($row['email']);
//                $m->setFrom($from);
//                $m->setSubjectCharset('UTF-8');
//                $m->setMessageCharset('UTF-8');
//                $m->setSubject('=?UTF-8?B?' . base64_encode('Envio de Ofertas') . '?= ');
//                $m->setMessageFromString(NULL, $saida);
//
//                if (!$ses->sendEmail($m)) {
//                    echo ' <br> Erro ao enviar E-mail : ' . $row['email'];
//                } else {
//                    $ok++;
//                }

                if (!empty($aviso_corretor)) {

                    $aviso_corretor.= '<br>Favor entrar em contato com o mesmo o quanto antes.<br><br>Contato : ' . $comprador->fone1 . ' ' . $comprador->fone2 . ' ' . $comprador->cel . ' ' . $comprador->email;

                    include '../model/mensagem.php';
                    $mensagem = new mensagem();

                    $mensagem->enviar($corretor->usuario, $corretor->usuario, 'Radar de Ofertas para ' . $comprador->nome, $aviso_corretor, 'N');
                }

                $saida = '';
            } else {
                echo 'NO AUX';
            }
        }

        if ($ok > 0) {
            echo $envio;
        }


        oferta_sem_novidades();
        ?>
    </body>
</html>
