<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');

if (isset($_SESSION['email_envio'])) {
    $email_envio = $_SESSION['email_envio'];
}
if (isset($_REQUEST['email_envio'])) {
    $email_envio = $_REQUEST['email_envio'];
}
if (empty($email_envio)) {
    die('Remetente Inválido');
}

if (isset($_SESSION['email_para'])) {
    $email_para = $_SESSION['email_para'];
    echo "Sess: ";
}
if (isset($_REQUEST['email_para'])) {
    $email_para = $_REQUEST['email_para'];
    echo "Req: ";
}
if (empty($email_para)) {
    $email_para = $email_envio;
    echo "Env: ";
}

if (isset($_SESSION['email_assunto'])) {
    $email_assunto = $_SESSION['email_assunto'];
}
if (isset($_REQUEST['email_assunto'])) {
    $email_assunto = $_REQUEST['email_assunto'];
}
if (empty($email_assunto)) {
    $email_assunto = 'ENVIO SISTEMA SGIPLUS';
}

if (isset($_SESSION['email_mensagem'])) {
    $email_mensagem = $_SESSION['email_mensagem'];
}
if (isset($_REQUEST['email_mensagem'])) {
    $email_mensagem = $_REQUEST['email_mensagem'];
}
if (empty($email_mensagem)) {
    die('Corpo Mensagem Inválida');
}

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
        echo "Não foi possível enviar de $email_envio para o e-mail $email_para.(ses)";
    } else {
        echo "E-mail de $email_envio enviado com sucesso o para $email_para.(ses)";
    }
} else {

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: $email_envio\r\n";
    $headers .= "Return-Path: $email_envio\r\n";
    $envio = mail("$email_para", "$email_assunto", "$email_mensagem", $headers);

    if ($envio) {
        echo "E-mail de $email_envio enviado com sucesso o para $email_para.(mail)";
    } else {
        echo "Não foi possível enviar de $email_envio para o e-mail $email_para.(mail)";
    }
    
    echo "<pre>$envio</pre>";
}
    