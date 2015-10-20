<?php
session_start();
include 'ficha.php';
echo ficha_baixar($_GET['id']);
