<?php

include 'conexao.php';

class cep extends conexao {

    function buscar($cep) {

        $ok = 'N';

        $sql = " SELECT * FROM cep WHERE cep='$cep' ";

        $ret = $this->db->prepare($sql);

        $ret->execute();

        if ($ret->rowCount() == 0) {

            $db2 = new PDO('mysql:host=localhost;dbname=facilcom_cep', 'facilcom_cep', 'imoveis0102');
            $db2->query("SET NAMES 'utf8'");
            $db2->query('SET character_set_connection=utf8');
            $db2->query('SET character_set_client=utf8');
            $db2->query('SET character_set_results=utf8');

            $sql = " SELECT * FROM cep_log_index WHERE cep5='" . substr($cep, 0, 5) . "' ";

            $ret = $db2->prepare($sql);

            $ret->execute();

            if ($ret->rowCount() == 1) {

                $row = $ret->fetch();

                $sql = " SELECT * FROM " . $row['uf'] . " WHERE cep='" . substr($cep, 0, 5) . '-' . substr($cep, 5) . "' ";

                $ret = $db2->prepare($sql);

                $ret->execute();

                if ($ret->rowCount() == 1) {
                    $row2 = $ret->fetch();

                    $sql = " INSERT INTO cep (cep,tipo_logradouro,logradouro,bairro,cidade,uf) ";
                    $sql .= " VALUES ('$cep','" . $row2['tp_logradouro'] . "','" . $row2['logradouro'] . "','" . $row2['bairro'] . "','" . $row2['cidade'] . "','" . strtoupper($row['uf']) . "')";

                    $ret = $this->db->prepare($sql);

                    $ret->execute();

                    return $row2['tp_logradouro'] . '|' . $row2['logradouro'] . '|' . $row2['bairro'] . '|' . $row2['cidade'] . '|' . strtoupper($row2['uf']) . '|CRGNET';
                }
            }
        } elseif ($ret->rowCount() == 1) {

            $row2 = $ret->fetch();
            return $row2['tipo_logradouro'] . '|' . $row2['logradouro'] . '|' . $row2['bairro'] . '|' . $row2['cidade'] . '|' . strtoupper($row2['uf']) . '|LOCAL';
        }

        $ch = curl_init('http://cep.republicavirtual.com.br/web_cep.php?cep=' . $cep . '&formato=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);

        $row2 = json_decode($json);

        if (!empty($row2->logradouro)) {
            $sql = " INSERT INTO cep (cep,tipo_logradouro,logradouro,bairro,cidade,uf) ";
            $sql .= " VALUES ('$cep','" . $row2->tipo_logradouro . "','" . $row2->logradouro . "','" . $row2->bairro . "','" . $row2->cidade . "','" . strtoupper($row2->uf) . "')";

            $ret = $this->db->prepare($sql);

            $ret->execute();

            $sql = " INSERT INTO cep_log_index (cep5,uf) ";
            $sql .= " VALUES ('" . substr($cep, 0, 5) . '-' . substr($cep, 5) . "','" . strtoupper($row2->uf) . "')";

            $ret = $db2->prepare($sql);

            $ret->execute();

            $sql = " INSERT INTO " . strtoupper($row2->uf) . " (cep,tp_logradouro,logradouro,bairro,cidade,uf) ";
            $sql .= " VALUES ('" . substr($cep, 0, 5) . '-' . substr($cep, 5) . "','" . $row2->tipo_logradouro . "','" . $row2->logradouro . "','" . $row2->bairro . "','" . $row2->cidade . "','" . strtoupper($row2->uf) . "')";

            $ret = $db2->prepare($sql);

            $ret->execute();

            return $row2->tipo_logradouro . '|' . $row2->logradouro . '|' . $row2->bairro . '|' . $row2->cidade . '|' . strtoupper($row2->uf) . '|WS';
        }
    }

}
