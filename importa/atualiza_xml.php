<?php
set_time_limit(600);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

$ver = '100'; // 27/02/2014
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Atualizando site SGI Plus...</title>
        <link href="favicon.ico" rel="shortcut icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" id="view" content="width=device-width, minimum-scale=1, maximum-scale=1" />
        <meta name="robots" content="index,follow" />
        <meta name="author" content="Carlos Renato Gaddini, http://sgifacil.com.br" />
    </head>
    <body>
        <?php
        $saida .= '<br>Inicio:' . date('d/m/Y h:i:s');
        $saida .= '<br>';
        $saida .= '<br>';

        $clientes = array();
		
		include 'backup_clientes.php';

		
        // $clientes[] = 'http://almeidalima.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imobiliariatuka.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://c21marajoara.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://idealimoveisbotucatu.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imoveiscavalcanti.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imobiliaria3deouro.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imoveisbenhur.com.br/atualiza_xml.php';
        // $clientes[] = 'http://sbi.imb.br/atualiza_xml.php';
        // $clientes[] = 'http://campeaoimoveisitatiba.com.br/atualiza_xml.php';
        // $clientes[] = 'http://gennaroimobiliaria.com.br/atualiza_xml.php';
        // $clientes[] = 'http://lmcorretores.com.br/atualiza_xml.php';
        // $clientes[] = 'http://vidanovaimoveisgo.com.br/atualiza_xml.php';
        // $clientes[] = 'http://imobiliariaviabrasil.com.br/atualiza_xml.php';
        // $clientes[] = 'http://gefimoveis.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imobiliariaviabrasil.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://imimobiliaria.com.br/atualiza_xml.php'; 
        // $clientes[] = 'http://abaimoveisluziania.com.br/atualiza_xml.php';
        // $clientes[] = 'http://mabelimoveis.com.br/atualiza_xml.php';
        // $clientes[] = 'http://georgeferes.com.br/html/sgi/atualiza_xml.php';
        // $clientes[] = 'http://montecarlosimoveisassis.com.br/atualiza_xml.php';
        // $clientes[] = 'http://bedeuimoveis.com.br/atualiza_xml.php';
        // $clientes[] = 'http://cbimoveis.com/atualiza_xml.php';

        foreach ($clientes as $cliente) {

            $saida .= "<br>-- Cliente : $cliente <br>";
			
			$url = 'http://162.144.109.206/~sgi'.$cliente.'/atualiza_xml.php';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $saida .= curl_exec($ch);

            curl_close($ch);
        }

        $saida .= "\r\n<br><br>Fim:" . date('d/m/Y h:i:s');
        
        echo $saida;
        ?>

    </body>
</html>