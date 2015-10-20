<?php
session_start();
include 'log.php';
echo log_carregar($_GET['id']);
