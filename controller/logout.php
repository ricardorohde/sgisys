<?php

session_start();

$_SESSION['usuario_id'] = '';
unset($_SESSION['usuario_id']);

$_SESSION['cliente_id'] = '';
unset($_SESSION['cliente_id']);

echo '<meta charset="UTF-8">';
echo '<script>window.open("../view/login.php","_self");</script>';
echo 'Saindo...';

session_destroy();

exit(); 



