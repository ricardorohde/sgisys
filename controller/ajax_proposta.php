<?php
session_start();
include 'proposta.php';
echo proposta_carregar($_GET['id']);
