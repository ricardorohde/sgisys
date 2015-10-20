<?php
session_start();
include '../model/cep.php';
$cep = new cep();
echo $cep->buscar($_GET['cep']);
