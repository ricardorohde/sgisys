<?php

session_start();

include '../model/ocorrencia.php';

$ocorrencia = new ocorrencia();

$ocorrencia->excluir($_GET['id']);
echo '<script>window.open(\'../view/ocorrencias.php\',\'frmOcorrs\');</script>';
