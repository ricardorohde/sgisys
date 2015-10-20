<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');

ini_set('register_globals', 'off');

$_SESSION['debug'] = 'S';
include 'db.php';
include 'debug.php';

if (!class_exists('conexao')) {

    class conexao extends database {

        public function __construct() {
            parent::__construct();

            if (isset($_SESSION['cliente_id'])) {
                $this->db = new PDO($this->cfg_con['type'] . ':host=' . $this->cfg_con['server'] . ';dbname=' . $this->cfg_con['database'], $this->cfg_con['user'], $this->cfg_con['password']);
            }

            if ($this->cfg_con['type'] == 'mysql') {
                $this->db->query("SET NAMES 'utf8'");
                $this->db->query('SET character_set_connection=utf8');
                $this->db->query('SET character_set_client=utf8');
                $this->db->query('SET character_set_results=utf8');
            }
        }

        public function ocorrencia($tipo, $titulo, $descricao, $resultado = '', $query = '') {

            $sql = " INSERT INTO log ";
            $sql .= " (usuario, ip, tipo, titulo, descricao, resultado, query) ";
            $sql .= " VALUES ('" . $_SESSION['usuario_nome'] . "', '" . $_SERVER['REMOTE_ADDR'] . "', '$tipo', '$titulo', '" . str_replace('<br>', ' _ ', nl2br($descricao)) . "', '$resultado', '" . "') ";
            $ret = $this->db->prepare($sql);
            @$ret->execute();
        }

    }

}