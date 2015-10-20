<?php
session_start();
include '../model/site_config.php';
$site_config = new site_config();
echo $site_config->modelo_grava($_GET['modelo']);
