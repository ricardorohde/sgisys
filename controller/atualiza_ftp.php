<?php

session_start();

include 'site_config.php';

$sc = json_decode(site_config_carregar());

if (empty($sc->servidor_ftp) || empty($sc->usuario_ftp) || empty($sc->senha_ftp)) {
    die('Configuração servidor FTP');
}

$ftp = $sc->servidor_ftp;

$conn_id = ftp_connect($ftp);
@$login_result = ftp_login($conn_id, $sc->usuario_ftp, $sc->senha_ftp);
if ((!$conn_id) || (!$login_result)) {
    die("Erro FTP!");
}
ftp_pasv($conn_id, false);

$pasta = $sc->pasta_ftp;

if (strrpos($pasta, '/') != (strlen($pasta) - 1)) {
    $pasta.= '/';
}
include 'site_imagem.php';
$ret = json_decode(site_imagem_listar());
$x = 0;
$seq = 1;
foreach ($ret as $id) {
    $site_imagem = json_decode(site_imagem_carregar($id));
    $foto_env = '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $site_imagem->img;

    if (!file($foto_env)) {
        die(" ...arquivo " . $site_imagem->img . " não encontrado!");
    } else {
        if (ftp_put($conn_id, $pasta . $site_imagem->img, $foto_env, FTP_BINARY)) {
            echo "→";
            $x++;
            $seq++;
        } else {
            die(" ...Erro ao enviar para FTP! " . $site_imagem->img);
        }
    }
}
echo " Total : $x arquivo(s) enviado(s).";
