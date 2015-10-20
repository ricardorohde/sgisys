<?php

session_start();

include '../model/movimento.php';

$movimento = new movimento();

$movimento->excluir($_GET['id']);
echo '<script>window.open(\'../view/movimentos.php\',\'_self\');</script>';
