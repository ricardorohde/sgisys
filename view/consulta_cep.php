<?php

$_SESSION['cliente_id'] = 000;

include '../model/conexao.php';
include '../model/cep.php';

$cep = new cep();
echo $cep->buscar($_GET['cep']);

