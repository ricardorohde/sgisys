<?php

session_start();

include '../model/pagina.php';

$pagina = new pagina();

$pagina->excluir($_GET['id']);
echo '<script>window.open(\'../view/paginas.php\',\'_self\');</script>';
