<?php

session_start();

include 'usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
if ($usu->fotos != 'Sim') {
    echo '<script>alert("Você não tem permissões para essa operação.")</script>';
    echo '<script>window.close("#");</script>';
}

include 'site_config.php';

$sc = json_decode(site_config_carregar());
$pasta = '';

if (!empty($sc->servidor_ftp) && !empty($sc->usuario_ftp) && !empty($sc->senha_ftp)) {
    $ftp = $sc->servidor_ftp;

    $conn_id = ftp_connect($ftp);
    $login_result = ftp_login($conn_id, $sc->usuario_ftp, $sc->senha_ftp);

    if ((!$conn_id) || (!$login_result)) {
        echo 'Erro FTP';
    } else {

        ftp_pasv($conn_id, false);

        $pasta = $sc->pasta_ftp;

        if (strrpos($pasta, '/') != (strlen($pasta) - 1)) {
            $pasta.= '/';
        }
    }
}

include 'imovel_foto.php';
$id = $_POST['id'];
$uploaddir = '../site/fotos/' . $_SESSION['cliente_id'] . '/';
echo '<a name="topo" id="topo"><br>Upload :';

$ret = json_decode(imovel_foto_listar($id));

$disp = (30 - count($ret));

$max = count($_FILES['arquivo']['name']);
if ($max > $disp) {
    $max = $disp;
}
$erro = 0;
echo '<table>';
echo '  <tr>';
echo '      <td bgcolor="#d1d1a3">Nome Arquivo</td>';
echo '      <td bgcolor="#d1d1a3">Tamanho</td>';
echo '      <td bgcolor="#d1d1a3">Resultado</td>';
echo '  </tr>';
for ($i = 0; $i < $max; $i++) {
    $arq = md5(uniqid("")) . '.jpg';
    $uploadfile = $uploaddir . $arq;
    $tmp = $_FILES['arquivo']['tmp_name'][$i];

    echo '  <tr>';

    echo '      <td bgcolor="#dde0e3"><b>' . $_FILES['arquivo']['name'][$i] . '</b></td>';

    echo '      <td align="right" bgcolor="#dde0e3">' . number_format(($_FILES['arquivo']['size'] [$i] / 1024), 0, '', ',') . ' Kbytes</td>';
    
    echo '      <td bgcolor="#dde0e3">';

    if ($_FILES['arquivo']['size'] [$i] > 2000000) {
        echo '<font color="red">ERRO - Tamanho <i>maior</i> que 2,000 Kbytes (2Mb) </font>';
        $erro++;
    } elseif (move_uploaded_file($tmp, $uploadfile)) {
        if (!empty($conn_id)) {
            if (ftp_put($conn_id, $pasta . $arq, '../site/fotos/' . $_SESSION['cliente_id'] . '/' . $arq, FTP_BINARY)) {
                echo '  ✈   ';
            } else {
                echo '  ✘   ';
            }
        } else {
            echo ' ◎  ';
        }

        $imginfo_array = getimagesize($uploadfile);
        if ($imginfo_array !== false) {
            $mime_type = $imginfo_array['mime'];
        }
        if ($mime_type == 'image/jpeg') {
            imovel_foto_gravar($id, $arq);
            echo '...OK';
        } else {
            echo "<font color='red'>ERRO - tipo $mime_type inválido. Utilizar JPG</font></br>";
            $erro++;
        }
    } else {
        echo "<font color='red'>ERRO - não enviado</font>";
        $erro++;
    }
    echo '      </td>';
    echo '  </tr>';
}
echo '</table>';
if ($erro == 0) {
    sleep(2);
    echo '<script>window.open("../view/cadastro_foto_upload.php?id=' . $id . '","cadastro_foto");</script>';
} else {
    echo '<br><br><b>' . $erro . ' Foto(s) não enviada(s) - Verifique em <font color="red">vermelho</font>.';
    echo '<br><br><a href="#" onclick="window.open(\'../view/cadastro_foto_upload.php?id=' . $id . '\',\'cadastro_foto\');">Fechar</a>';
}
?>