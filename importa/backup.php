<?php

set_time_limit(600);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

$ver = '100'; // 19-05-2014

$saida = '';

echo '<br>Inicio:' . date('d/m/Y h:i:s');
echo '<br>';
echo '<br>';

include 'backup_clientes.php';

foreach ($clientes as $cliente) {

    echo '<br>' . $cliente . ' : ';

    $hora = date('H');

    $cmd = "mysqldump sgipl134_$cliente > backup/sgipl134_$cliente-$hora.sql -h localhost -u sgipl134 -pV&2-yZw+}h_o -v ";

    echo shell_exec($cmd);
}

echo "\r\n<br><br>Fim:" . date('d/m/Y h:i:s');

echo $saida;


