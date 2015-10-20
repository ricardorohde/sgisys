<?php
session_start();
include 'imovel_subtipo.php';
echo imovel_subtipo_carregar_tipo($_GET['tipo']);
