<?php
set_time_limit(1800);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

$ver = '106'; // 30/01/2014
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

        $clientes = array();

        //$clientes[] = '0017'; // campeão

        $max = 500;

        foreach ($clientes as $cliente) {

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

            echo "\r\n<br>";
            echo "<br><br>  [  iniciando processo do cliente $cliente " . date('d/m/Y h:i:s') . " ] ";
            $_SESSION['cliente_id'] = $cliente;
            $pasta = $_SESSION['cliente_id'];
            $pfotos = '/httpdocs/' . $pasta . '/*.jpg';

            echo '<br><br>Procurando ' . $pfotos . ' : ';
            $fotos = ftp_nlist($conn_id, $pfotos);

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

                curl_setopt($ch, CURLOPT_URL, "http://sgipro.com.br/importa.asp?pasta=$pasta&erro=N");
                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_exec($ch);

                curl_close($ch);

                $cfg_con = array();

                $cfg_con['type'] = 'mysql';
                $cfg_con['server'] = 'srv66.prodns.com.br';
                $cfg_con['database'] = 'sgipl134_' . $pasta;
                $cfg_con['user'] = 'sgipl134_' . $pasta;
                $cfg_con['password'] = 'imoveis0102';

                $db = new PDO($cfg_con['type'] . ':host=' . $cfg_con['server'] . ';dbname=' . $cfg_con['database'], $cfg_con['user'], $cfg_con['password']);

                if ($cfg_con['type'] == 'mysql') {
                    $db->query("SET NAMES 'utf8'");
                    $db->query('SET character_set_connection=utf8');
                    $db->query('SET character_set_client=utf8');
                    $db->query('SET character_set_results=utf8');
                }

                if (file_exists('update.php')) {

                    include 'update.php';
                }

                $sql = "DELETE FROM `imovel`";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "INSERT INTO `imovel` SELECT * FROM `imovel1`;";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "DELETE FROM `imovel_foto`";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "INSERT INTO `imovel_foto` SELECT * FROM `imovel_foto1`;";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "update imovel SET foto = (SELECT foto FROM imovel_foto WHERE imovel_foto.ref=imovel.id LIMIT 1) WHERE imovel.foto = '';";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "update imovel SET foto = 'sem_foto.jpg' WHERE imovel.foto = '';";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "delete from imovel_tipo ";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "insert into imovel_tipo (tipo) SELECT tipo_nome FROM `imovel` group by tipo_nome order by tipo_nome ";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "update imovel SET tipo = (SELECT id FROM imovel_tipo WHERE imovel_tipo.tipo=imovel.tipo_nome LIMIT 1)";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "delete from imovel_subtipo ";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "insert into imovel_subtipo (subtipo,tipo) SELECT subtipo_nome,tipo FROM `imovel` group by subtipo_nome order by subtipo_nome ";
                $ret = $db->prepare($sql);
                $ret->execute();

                $sql = "update imovel SET subtipo = (SELECT id FROM imovel_subtipo WHERE imovel_subtipo.subtipo=imovel.subtipo_nome LIMIT 1)";
                $ret = $db->prepare($sql);
                $ret->execute();

                //
                if ($pasta == '001') {

                    $sql = "UPDATE imovel SET destaque = 'Sim' WHERE (
imovel.id = '13' OR imovel.id = '57' OR imovel.id = '51' OR imovel.id = '71' OR imovel.id = '72' OR imovel.id = '39' OR imovel.id = '81' OR imovel.id = '96' OR imovel.id = '7' OR imovel.id = '6'
)";
                    $ret = $db->prepare($sql);
                    $ret->execute();
                }

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
        }




        if (file_exists('update.php')) {
            unlink('update.php');
        }

        echo "\r\n<br><br>Fim:" . date('d/m/Y h:i:s');
        ?>

    </body>
</html>