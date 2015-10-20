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
        echo '<br>Inicio:' . date('d/m/Y h:i:s');
        echo '<br>';
        echo '<br>';

        $clientes = array();

        for ($x = 1; $x <= 99; $x++) {

            $clientes[] = 'http://sgiplus.com.br/view/radar_ofertas.php?cliente_id=' . str_pad($x, 4, '0', 0);
            
        }

        foreach ($clientes as $cliente) {

            echo "<br>-- Cliente : $cliente <br>";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $cliente);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_exec($ch);

            curl_close($ch);
        }

        echo "\r\n<br><br>Fim:" . date('d/m/Y h:i:s');
        ?>

    </body>
</html>