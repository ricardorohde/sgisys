<?php

session_start();
include 'cadastro.php';
echo cadastro_carregar('imovel', $_GET['id']);
