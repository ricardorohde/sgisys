<?php

if (!class_exists('database')) {

    class database {

        public $cfg_con;

        public function __construct() {

            $this->cfg_con = array();

            //
            // Adm 
            //

            if ($_SESSION['cliente_id'] == '0000') { // sgi facil
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = 'localhost';
                $this->cfg_con['database'] = 'sgipl134_0000';
                $this->cfg_con['user'] = 'sgipl134_0000';
                $this->cfg_con['password'] = 'des!fer@';
            } elseif ($_SESSION['cliente_id'] == '0002') { // sgidemo
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = 'sgipl134_0002';
                $this->cfg_con['user'] = 'sgipl134_0002';
                $this->cfg_con['password'] = 'imoveis0102';
            } elseif ($_SESSION['cliente_id'] == '0003') { // teste
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = 'sgipl134_0003';
                $this->cfg_con['user'] = 'sgipl134_0003';
                $this->cfg_con['password'] = 'imoveis0102';
            } elseif ($_SESSION['cliente_id'] == '0001') { // vitoria garden
                $cliente_id = $_SESSION['cliente_id'];
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = "sgipl134_0001";
                $this->cfg_con['user'] = "sgipl134_0001";
                $this->cfg_con['password'] = 'imoveis0102';
            } elseif ($_SESSION['cliente_id'] == '0004') { // almeidalima
                $cliente_id = $_SESSION['cliente_id'];
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = "sgipl134_0004";
                $this->cfg_con['user'] = "sgipl134_0004";
                $this->cfg_con['password'] = 'imoveis0102';
            } elseif ($_SESSION['cliente_id'] == '0005') { // paywa
                $cliente_id = $_SESSION['cliente_id'];
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = "sgipl134_0005";
                $this->cfg_con['user'] = "sgipl134_0005";
                $this->cfg_con['password'] = 'imoveis0102';
            } elseif ($_SESSION['cliente_id'] == '0006') { // tuka
                $cliente_id = $_SESSION['cliente_id'];
                $this->cfg_con['type'] = 'mysql';
                $this->cfg_con['server'] = '162.144.109.206';
                $this->cfg_con['database'] = "sgipl134_0006"