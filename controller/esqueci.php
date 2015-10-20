<?php

session_start();


if (isset($_POST['cliente'])) {
    $cliente = $_POST['cliente'] + 0;
} else {
    $cliente = "";
}

if (empty($cliente)) {
    echo '<script>alert("Codigo de Cliente Invalido.");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}

$cliente = str_pad($cliente, 4, '0', 0);
$_SESSION['cliente_id'] = $cliente;


if (!isset($_POST['usuario'])) {
    echo '<script>alert("Usuario Invalido.");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}

include 'usuario.php';

$nome = $_POST['usuario'];

$usu_id = json_decode(usuario_id($nome));

if (!$usu_id) {
    echo '<script>alert("Usuario Invalido..");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}

$usu = json_decode(usuario_carregar($usu_id));

if (!$usu) {
    echo '<script>alert("Usuario Invalido...");</script>';
    echo '<script>window.close("#");</script>';
    exit();
}

include 'site_config.php';
$cfg = json_decode(site_config_carregar());

$email_envio = 'envios@sgifacil.com.br';
if (!empty($cfg->email_envio)) {
    $email_envio = $cfg->email_envio;
}

$de = 'Sistema SGIPLUS';
$img = "$url/logo_sgi.png";

if (!empty($cfg->titulo_pagina)) {
    $de = $cfg->titulo_pagina;
    $imob = $cfg->titulo_pagina;
}

if (!empty($cfg->imagem_logo)) {
    $img = "$url/{$cfg->imagem_logo}";
}

include 'ficha_config.php';
$fic = json_decode(ficha_config_carregar());

if (!empty($fic->texto1)) {
    $de = $fic->texto1;
}

if (!empty($fic->logo)) {
    $img = "$url/{$fic->logo}";
}


$email_seguro = $email_envio;

if (!empty($email_envio)) {

    $email_para = $email_seguro;

    if (strtoupper($nome) == 'ADMIN' || empty($nome)) {
        die('Usuario invalido');
    }

    $email_assunto = "Redefinição de Senha Sistema SGIPLUS";
    $hash = date('Ymd') . '|'; // 0 data
    $hash .= $cliente . '|'; // 1 cliente
    $hash .= md5(uniqid("")) . '|'; // 2 uniq
    $hash .= date('d/m/Y H:i:s') . '|'; // 3 data2
    $hash .= $_SERVER['REMOTE_ADDR'] . '|'; // 4 ip
    $hash .= date('HisYmd') . '|'; // 5 data3
    $hash .= time(); // 6 time
    $hash = base64_encode($hash);
    $url = "http://sgiplus.com.br/view/reset_user.php?hash=$hash";
    $email_mensagem = "<br>Prezado(a) cliente, através do link <en><b>esqueci minha senha</b></en> da página de login do Sistema SGIPLUS (SGI On line) foi solicitada a redefinição da senha do usuário : <br>&nbsp;[ <b>$nome</b> ]<br><br>>>> Segue o link para redefinição.<br><br><br><a href='$url' target='_blank' title='clique aqui para voltar a senha para a padrão do sistema.'>$url</a><br><sup>Caso o link não esteja disponível, você pode copiar o endereço acima e colar na barra do seu navegador.<br><br><br>Se você não fez essa solicitação, apenas desconsidere esta mensagem.</sup><br><br>Atenciosamente, <br>Equipe SGI Fácil.";


    if (strpos($email_para, '@hotmail')) {


        require('ses.php');


        $ses = new SimpleEmailService('AKIAJW7PZOSMEKG7WNGA', 'AqVmS/oLnSJUX5KEoL6AE6FdCKGvpnLILnlKXCBDVdAx');
        $m = new SimpleEmailServiceMessage();

        $m->addTo($email_para);
        $m->setFrom('envios@sgifacil.com.br');
        $m->setSubjectCharset('UTF-8');
        $m->setMessageCharset('UTF-8');
        $m->setSubject('=?UTF-8?B?' . base64_encode($email_assunto) . '?= ');
        $m->setMessageFromString(NULL, $email_mensagem);

        if (!$ses->sendEmail($m)) {
            echo 'Não foi possível enviar o e-mail.';
        } else {
            echo 'E-mail enviado com sucesso.';
        }
    } else {
        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $email_envio\r\n";
        $headers .= "Return-Path: $email_envio\r\n";
        $envio = mail("$email_para", "$email_assunto", "$email_mensagem", $headers);

        if ($envio) {
            echo 'E-mail enviado com sucesso.';
            usuario_gravar_reset($usu_id, $hash);
        } else {
            echo 'Não foi possível enviar o e-mail.';
        }
    }
} else {
    echo '<script>alert("Sem email para envio");</script>';
}
echo '<script>window.close("#");</script>';
