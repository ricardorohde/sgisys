<?php

$clientes = array();

$clientes[] = '0000'; // sgi

for ($i = 1; $i <= 100; $i++) {
    $clientes[] = str_pad($i, 4, '0', 0);
}

$clientes[] = '9999'; // dev