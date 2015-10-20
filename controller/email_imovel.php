<?php

session_start();

include 'usuario.php';
$usu = json_decode(usuario_carregar($_SESSION['usuario_id']));

include 'site_config.php';
$cfg = json_decode(site_config_carregar());

$corr_id = $_GET['de'];
$email_envio = 'envios@sgifacil.com.br';
if (!empty($cfg->email_envio)) {
    $email_envio = $cfg->email_envio;
    $email_corr = $cfg->email_envio;
}

$url = 'http://sgiplus.com.br/site/fotos/' . $_SESSION['cliente_id'];
//$url = 'http://sgiplus.com.br/site/fotos/0002';

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

include 'cadastro.php';
$corrs = json_decode(cadastro_listar('corretor', " and id='$corr_id'  ", '', ''));
if ($corrs) {
    $corr = json_decode(cadastro_carregar('corretor', $corrs[0]));
    if (!empty($corr->email)) {
        $email_corr = $corr->email;
    } else {
        die('O Corretor não possui email.');
    }
    if (!empty($corr->nome)) {
        $de = $corr->nome . '<br>Corretor Responsável';
    }
    if (!empty($imob)) {
        $de .='<br><br><strong>' . $imob . '</strong>';
    }
    if (!empty($corr->creci)) {
        $de .= '<br> CRECI ' . $corr->creci;
    }
    if (!empty($corr->fone1)) {
        $de = $de.= '<br>Fone : ' . $corr->fone1;
    }
    if (!empty($corr->fone2)) {
        $de = $de.= ' / ' . $corr->fone2;
    }
    if (!empty($corr->cel)) {
        $de = $de.= ' / ' . $corr->cel;
    }
    if (!empty($corr->foto)) {
        $img = "$url/{$corr->foto}";
    }
}

if (isset($_GET['ref'])) {
    $ref = $_GET['ref'];
} else {
    die('Sem Referencia');
}

if (isset($_GET['mensagem'])) {
    $mensagem = nl2br($_GET['mensagem']);
} else {
    $mensagem = '';
}

if (!empty($ref)) {

    $saida = '';


    include 'oferta.php';
    $imov = json_decode(oferta_carregar_imovel($ref));

    $saida .= '<table width="100%" style="border: none;margin:10px;">';
    $saida .= '<tr>';
    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 0px;vertical-align: top;" width="180"><center>';
    if (!empty($cfg->url) && $imov->internet == 'Sim') {
        $saida .= '<a href="' . $cfg->url . '/index.php?q=Ref&id=' . $imov->id . '" target="_blank">';
    }
    $saida .= '  <img src="' . $url . '/' . $imov->foto . '" width="180" height="150">';
    if (!empty($cfg->url)) {
        $saida .= '</a>';
    }
    $saida .= '  </center></td>';
    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;">';
    $saida .= '  Referência : ' . $imov->id . '  ';
    $saida .= '  | <strong>' . $imov->tipo_nome . ' em ' . $imov->bairro . '/' . $imov->cidade . '</strong>';
    //$saida .= '  <br>' . $imov->localizacao . ' ' . $imov->condominio . ' ' . $imov->edificio . '';
    $saida .= '  <br><br>' . $imov->descricao . '  ';
    $saida .= '  </td>';
    $saida .= '  <td bgcolor="#e6e6e6" style="padding: 5px;" width="180">';
    //
    //
    $saida .= '  <table align="center">';
    if ($imov->dormitorio > 0) {
        $saida .= '      <tr><td>Dorms</td><td align="right">' . $imov->dormitorio . ' &nbsp;</td></tr> ';
    }
    if ($imov->banheiro > 0) {
        $saida .= '      <tr><td>Banheiros</td><td align="right">' . $imov->banheiro . ' &nbsp;</td></tr> ';
    }
    if ($imov->suite > 0) {
        $saida .= '      <tr><td>Suite</td><td align="right">' . $imov->suite . ' &nbsp;</td></tr> ';
    }
    if ($imov->garagem > 0) {
        $saida .= '      <tr><td>Vagas</td><td align="right">' . $imov->garagem . ' &nbsp;</td></tr> ';
    }
    if ($imov->area_util > 0) {
        $saida .= '      <tr><td>Area Util</td><td align="right">' . $imov->area_util . 'm² &nbsp;</td></tr> ';
    }
    if ($imov->area_contruida > 0) {
        $saida .= '      <tr><td>Area Construida</td><td align="right">' . $imov->area_construida . 'm² &nbsp;</td></tr> ';
    }
    if ($imov->area_terreno > 0) {
        $saida .= '      <tr><td>Area Terreno</td><td align="right">' . $imov->area_terreno . 'm² &nbsp;</td></tr> ';
    }
    if ($imov->m2_frente > 0) {
        $saida .= '      <tr><td>Mt Frente</td><td align="right">' . $imov->m2_frente . 'm² &nbsp;</td></tr> ';
    }
    if ($imov->fundos > 0) {
        $saida .= '      <tr><td>Mt Fundos</td><td align="right">' . $imov->fundos . 'm² &nbsp;</td></tr> ';
    }
    if ($imov->profundidade > 0) {
        $saida .= '      <tr><td>Mt Profundidade</td><td align="right">' . $imov->profundidade . ' &nbsp;</td></tr> ';
    }
    if (!empty($imov->zoneamento)) {
        $saida .= '      <tr><td>Zoneamento</td><td align="right">' . $imov->zoneamento . ' &nbsp;</td></tr> ';
    }
    if (!empty($imov->topografia)) {
        $saida .= '      <tr><td>Topografia</td><td align="right">' . $imov->topografia . ' &nbsp;</td></tr> ';
    }
    if ($imov->valor_venda > 0) {
        $saida .= '      <tr><td>Venda </td><td align="right">R$ ' . us_br($imov->valor_venda) . ' &nbsp;</td></tr> ';
    }
    if ($imov->valor_locacao > 0) {
        $saida .= '      <tr><td>Locação </td><td align="right">R$ ' . us_br($imov->valor_locacao) . ' &nbsp;</td></tr> ';
    }
    $saida .= '  </table>';
    //
    //
    $saida .= '  </td>';
    $saida .= '</tr>';
    $saida .= '<tr>';
    $saida .= '  <td colspan="3"><center>';
    $fots = json_decode(cadastro_listar('imovel_foto', " and ref='$ref' ", $order, "limit 1,4 "));
    foreach ($fots as $fot) {
        $foto = json_decode(cadastro_carregar('imovel_foto', $fot));
        $saida .= '  <img src="' . $url . '/' . $foto->foto . '" width="180" height="150" style="margin:5px;">';
    }

    $saida .= '  </center></td>';
    $saida .= '</tr>';
    $saida .= '</table>';
    $saida .= '<br>' . $mensagem;
    $saida .= '<br>Atenciosamente,';
    $saida .= '<br><br><img src="' . $img . '" width="100" alt="' . $imob . '">';
    $saida .= '<br>' . $de . '<br>' . $email_corr;
}

if (!empty($email_envio)) {

    $ref = str_pad($_GET['ref'], 6, '0', 0);
    $email_para = $_GET['para'];
    $nome = $_GET['nome'];
    $email_assunto = "Envio de oferta de imóvel Ref.$ref $imob";
    $email_mensagem = "<br>Prezado(a) $nome, segue abaixo uma oferta enviada.<br><br>$saida";

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
        } else {
            echo 'Não foi possível enviar o e-mail.';
        }
    }
} else {
    echo 'Sem email para envio';
}