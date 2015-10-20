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
include '../controller/site_config.php';

$conexao = new conexao();
$ret = $conexao->db->query(" SELECT * FROM site_config ");

if (!$ret) {
    die('Tabela de Config do Site não encontrada.');
}

$site_config = $ret->fetch();

$nome = $site_config['nome'];

//$ses = new SimpleEmailService('AKIAIZZEDKTEVHUBVL2Q', 'xpES7BqktVjHyWoaO+jE/Wfmkr6P0K227btoslqO');
//
//if (!$ses) {
//    die('erro aut.');
//}

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

        $sql = " UPDATE ocorrencia SET avisar_hora2=replace(avisar_hora,':','') ";
        $ocors = $conexao->db->query($sql);

        $sql = " SELECT * FROM ocorrencia WHERE avisar_email!='' and avisar_data='" . date('Ymd') . "' and avisar_hora2 <='" . date('Hi') . "' and avisado='' and status !='Resolvido' ";
        $ocors = $conexao->db->query($sql);

        //echo $sql;

        if (!$ocors) {
            die('Tabela de Ocorrencias não encontrada.');
        }

        $tot = $ocors->rowCount();
        $ok = 0;
        while ($row = $ocors->fetch()) {

            $cli = json_decode(cadastro_carregar('cliente', $row['ref']));

            $saida = "Ocorrencia do agendada cliente " . $cli->nome;
            $saida .= "<br>" . $row['historico'];

            $from = $site_config->email_envio;

//            $m = new SimpleEmailServiceMessage();
//
//            $m->addTo($row['avisar_email']);
//            //$m->addBCC('desenvolvimento.sgifacil@gmail.com');
//            $m->setFrom('envios@sgifacil.com.br');
//            $m->setSubjectCharset('UTF-8');
//            $m->setMessageCharset('UTF-8');
//            $m->setSubject('=?UTF-8?B?' . base64_encode('Alerta de Ocorrencia Agendada em ' . data_decode($row['avisar_data']) . ' ' . $row['avisar_hora']) . '?= ');
//            $m->setMessageFromString(NULL, $saida);
//
//            if (!$ses->sendEmail($m)) {
//                echo ' <br> Erro ao enviar E-mail : ' . $row['avisar_email'];
//            } else {
//                echo 'OK';
//            }
            
            $from = 'envios@sgifacil.com.br';
            $assunto = 'Alerta de Ocorrencia Agendada em ' . data_decode($row['avisar_data']) . ' ' . $row['avisar_hora'];

            $headers = "MIME-Version: 1.1\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: $from\r\n";
            $headers .= "Return-Path: $from\r\n";
            $envio = mail($row['avisar_email'], "$assunto", "$saida", $headers);

            if ($envio) {
                echo 'OK';
            } else {
                echo ' <br> Erro ao enviar E-mail : ' . $row['email'];
            }

            $saida = '';

            $sql = " UPDATE ocorrencia SET avisado='S' ";
            $ocors = $conexao->db->query($sql);
        }
        echo '<br>Total ' . $tot . ' agendamentos';
        ?>
    </body>
</html>
