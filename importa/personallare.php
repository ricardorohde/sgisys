<?php
set_time_limit(1800);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

$ver = '100'; // 11/02/2014
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Atualizando site SGI Plus...</title>
        <link href="favicon.ico" rel="shortcut icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" id="view" content="width=device-width, minimum-scale=1, maximum-scale=1" />
        <meta name="robots" content="index,follow" />
        <meta name="author" content="Carlos Renato Gaddini, http://sgifacil.com.br" />
    </head>
    <body>
        <?php
        echo '<br>Inicio:' . date('d/m/Y h:i:s');
        echo '<br>';
        echo '<br>';

        $cliente = '0018';


        /// sgipro

        echo '<br>Conectando ftp.sgipro.com.br ...';
        $conn_id = ftp_connect("ftp.sgipro.com.br");
        $login_result = ftp_login($conn_id, "sgipro", "des!fer125@");
        if ((!$conn_id) || (!$login_result)) {
            echo "Erro FTP!";
            die;
        } else {
            echo "Connectado.";
        }

        echo '<br>Servidor : ' . ftp_systype($conn_id);
        echo '<br>Diretório autal : ' . ftp_pwd($conn_id);

        ftp_pasv($conn_id, false);

        // personallare

        $url = '/personallare.com.br/web/public/imagens/imoveis';


        $persona = ftp_connect("ftp.personallare.com.br");
        $login_result = ftp_login($persona, "personalla1", "eca0510");
        if ((!$persona) || (!$login_result)) {
            echo "Erro FTP! Personallare";
            die;
        } else {
            echo "Connectado.";
        }

        echo '<br>Servidor : ' . ftp_systype($persona);
        echo '<br>Diretório autal : ' . ftp_pwd($persona);

        ftp_pasv($persona, false);

        //


        echo "\r\n<br>";
        echo "<br><br>  [  iniciando processo do cliente $cliente " . date('d/m/Y h:i:s') . " ] ";
        $_SESSION['cliente_id'] = $cliente;
        $pasta = $_SESSION['cliente_id'];
        $pfotos = '/httpdocs/' . $pasta . '/*.jpg';

        echo '<br><br>Procurando ' . $pfotos . ' : ';
        $fotos = ftp_nlist($conn_id, $pfotos);


        $max = 200;

        if ($fotos) {
            $fotos = array_slice($fotos, 0, $max);
            echo '<pre>';
            print_r($fotos);
            echo '</pre>';
        }
        $x = 0;
        $total = 0;
        if ($fotos) {
            $tfot = count($fotos);
            echo ' Total de Fotos encontrada em ' . $pasta . ' : ' . $tfot . ' ';
            foreach ($fotos as $remoto) {
                if ($x < $max) { // maximo de fotos por rodada
                    echo '<br>' . ($x + 1) . ' de ' . $tfot . ' : ' . $remoto . ' ';
                    $foto = str_replace('/httpdocs/' . $pasta . '/', '', $remoto);
                    $local = '../site/fotos/' . $pasta . '/' . $foto;
                    echo '-> ' . $local . ' ';
                    $tam = ftp_size($conn_id, $remoto);
                    echo number_format($total, 0, '', '.') . " bytes : ";
                    $local = str_replace('.JPG', '.jpg', $local);
                    if (ftp_get($conn_id, $local, $remoto, FTP_BINARY)) {
                        if (filesize($local) == $tam) {
                            echo "OK";
                            if (ftp_delete($conn_id, $remoto)) {
                                echo ' -> arquivo remoto removido ';

                                if (ftp_put($persona, $url . '/' . $foto, $local, FTP_BINARY)) {
                                    echo ' -> arquivo enviado de ' . $foto . ' para ' . $url . '/' . $foto;
                                } else {
                                    echo ' -> erro ao enviar remoto ';
                                }
                            } else {
                                echo ' !!! erro ao remover arquivo remoto ';
                            }
                            $total += $tam;
                        } else {
                            echo " !!! Tamanho diferente de " . number_format($tam, 0, '', '.') . " para " . number_format(filesize($local, 0, '', '.'));
                        }
                    } else {
                        echo "ERRO $foto\n";
                    }
                }
                $x++;
            }
            echo "<br>Total " . number_format(($total / 1024), 0, '', '.') . " KBytes transf";
        } else {
            echo ' Nenhuma foto encontrada.';
        }

        $mdb = '/httpdocs/' . $pasta . '/sgiweb2k.mdb';

        echo '<br> --- Procurando MDB : ' . $mdb . '... ';

        if (ftp_size($conn_id, $mdb) > 0) {
            echo 'Encontrado ';
            echo '<br>Atualizando site...';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://sgipro.com.br/importa_0018.asp");
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_exec($ch);

            curl_close($ch);

//            $cfg_con = array();
//
//            $cfg_con['type'] = 'mysql';
//            $cfg_con['server'] = 'srv66.prodns.com.br';
//            $cfg_con['database'] = 'sgi' . $pasta . '_base';
//            $cfg_con['user'] = 'sgi' . $pasta . '_site';
//            $cfg_con['password'] = 'imoveis0102';
//
//            $db = new PDO($cfg_con['type'] . ':host=' . $cfg_con['server'] . ';dbname=' . $cfg_con['database'], $cfg_con['user'], $cfg_con['password']);
//
//            if ($cfg_con['type'] == 'mysql') {
//                $db->query("SET NAMES 'utf8'");
//                $db->query('SET character_set_connection=utf8');
//                $db->query('SET character_set_client=utf8');
//                $db->query('SET character_set_results=utf8');
//            }

            echo ' -> Apagando MDB : ';
            if (ftp_delete($conn_id, $mdb)) {
                echo 'OK';
            } else {
                echo 'Não foi possível apagar.';
            }
        } else {
            echo ' Não encontrado.';
        }
        echo "<br><br>  [  ok - processo terminado cliente $cliente " . date('d/m/Y h:i:s') . " ] ";



        ftp_close($conn_id);
        ftp_close($persona);

        echo "\r\n<br><br>Fim:" . date('d/m/Y h:i:s');
        ?>

    </body>
</html>