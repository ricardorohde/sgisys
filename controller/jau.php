<?php
session_start();
ini_set('display_errors', 'on');
set_time_limit(300);
$carga_envio = '';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
        <link href="css/forms.css" rel="stylesheet" />
    </head>
    <body>
        <div id="conteudo">
            <?php
            echo ' <br>Gerando Ofertas...';
            include 'portal.php';
            $portal_id = 'JAU';
            $por = json_decode(portal_carregar($portal_id));

            //
            //
            $arq = md5(uniqid("")) . '.xml';
            $carg = 'carga/' . $arq;

            $fop = fopen($carg, 'w');

            $xml = '<?xml version="1.0" encoding="utf-8"?>';
            $xml .= '<imoveis>';
            $ger = 0;
            //
            //
            //
            
            // VENDA


            $ret = json_decode(publicar_listar($portal_id));
            $tot = count($ret);
            include 'cadastro.php';



            foreach ($ret as $id) {

                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));


                if ($imo->valor_venda > 0) {

                    include 'portal_tipo.php';
                    $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
                    if ($ptp) {
                        $tipo = $ptp->tipo_portal;
                    } else {
                        $tipo = $imo->tipo_nome;
                    }
                    include 'portal_subtipo.php';
                    $pstp = json_decode(portal_subtipo_procurar($portal_id, $imo->subtipo));
                    if ($pstp) {
                        $subtipo = $pstp->subtipo_portal;
                    } else {
                        $subtipo = $imo->subtipo_nome;
                    }

                    //
                    // Carga
                    //
                $xml .= '    <imovel>';
                    $xml .= '       <referencia>' . $aux->ref . '</referencia>';
                    $xml .= '       <tipo>' . $tipo . ' - ' . $subtipo . '</tipo>';
                    $xml .= '       <finalidade>Venda</finalidade>';
                    $xml .= '       <uso></uso>';
                    $xml .= '       <uf>' . $imo->uf . '</uf>';
                    $xml .= '       <cidade>' . $imo->cidade . '</cidade>';
                    $xml .= '       <bairro>' . $imo->bairro . '</bairro>';
                    if ($por->enviar_endereco == 'S') {
                        $endereco = $imo->tipo_logradouro . ' ' . $imo->logradouro;
                        $xml .= '       <Endereco>' . $endereco . '</Endereco>';
                    }
                    $xml .= '       <valor>' . $imo->valor_venda . '</valor>';
                    $xml .= '       <condominio>' . $imo->valor_condominio . '</condominio>';
                    $xml .= '       <iptu>' . $imo->valor_iptu . '</iptu>';
                    $xml .= '       <quartos>' . $imo->dormitorio . '</quartos>';
                    $xml .= '       <suites>' . $imo->suite . '</suites>';
                    $xml .= '       <banheiros>' . $imo->banheiro . '</banheiros>';
                    $xml .= '       <vagas>' . $imo->garagem . '</vagas> ';
                    $xml .= '       <areaConstruida>' . $imo->area_construida . '</areaConstruida>';
                    $xml .= '       <areaTotal>' . $imo->area_total . '</areaTotal>';
                    $xml .= '       <descricao><![CDATA[' . $imo->descricao . ']]></descricao>';

                    //
                    // caracteristicas
                    //
                /*
                      include 'imovel_caracteristica.php';
                      $caracs = json_decode(imovel_caracteristica_listar($imo->id));
                      foreach ($caracs as $id) {
                      $car = json_decode(imovel_caracteristica_carregar($id));
                      include 'portal_caracteristica.php';
                      $carac = json_decode(portal_caracteristica_procurar($portal_id, $car->caracteristica));
                      if ($carac) {
                      $xml .= '       <' . $carac->caracteristica_portal . '>1</' . $carac->caracteristica_portal . '>';
                      }
                      }
                      //
                     * 
                     */


                    include 'imovel_foto.php';
                    $fot = json_decode(imovel_foto_listar2($imo->id));
                    $fot = array_slice($fot, 0, 30);

                    $url_foto = $por->url;

                    if (empty($url_foto)) {
                        $url_foto = '/fotos/';
                    }

                    if ($fot) {
                        $xml .= '       <fotos>';
                        $x = 0;
                        $x2 = '1';
                        foreach ($fot as $foto_id) {
                            $fx = json_decode(imovel_foto_carregar($foto_id));
                            $xml .= '<foto>' . $url_foto . $fx->foto . '</foto>';
                            $x++;
                            $x2 = '0';
                        }
                        $xml .= '       </fotos>';
                    }
                    //
                    /*
                      if (!empty($imo->video_youtube) && strpos($imo->video_youtube, 'youtube.com') > 0) {
                      $xml .= '       <Videos>';
                      $xml .= '           <Video>';
                      $xml .= '               <Descricao>Vídeo do Imóvel</Descricao>';
                      $xml .= '               <URLArquivo>' . $imo->video_youtube . '</URLArquivo>';
                      $xml .= '               <Principal>1</Principal>';
                      $xml .= '           </Video>';
                      $xml .= '       </Videos>';
                      }
                      //
                     * 
                     */
                    $xml .= '       </imovel>';
                    $ger++;

                    publicar_data_envio($id, $tipo_anuncio);
                    if ($ger > 0) {
                        $carga_envio .= ', ';
                    }
                    $carga_envio .= $id;

                    $ret = fwrite($fop, $xml);
                    $xml = '';
                    echo '.';
                } elseif ($imo->valor_locacao > 0) {

                    include 'portal_tipo.php';
                    $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
                    if ($ptp) {
                        $tipo = $ptp->tipo_portal;
                    } else {
                        $tipo = $imo->tipo_nome;
                    }
                    include 'portal_subtipo.php';
                    $pstp = json_decode(portal_subtipo_procurar($portal_id, $imo->subtipo));
                    if ($pstp) {
                        $subtipo = $pstp->subtipo_portal;
                    } else {
                        $subtipo = $imo->subtipo_nome;
                    }

                    //
                    // Carga
                    //
                $xml .= '    <imovel>';
                    $xml .= '       <referencia>' . $aux->ref . '</referencia>';
                    $xml .= '       <tipo>' . $tipo . ' - ' . $subtipo . '</tipo>';
                    $xml .= '       <finalidade>Locação</finalidade>';
                    $xml .= '       <uso></uso>';
                    $xml .= '       <uf>' . $imo->uf . '</uf>';
                    $xml .= '       <cidade>' . $imo->cidade . '</cidade>';
                    $xml .= '       <bairro>' . $imo->bairro . '</bairro>';
                    if ($por->enviar_endereco == 'S') {
                        $endereco = $imo->tipo_logradouro . ' ' . $imo->logradouro;
                        $xml .= '       <Endereco>' . $endereco . '</Endereco>';
                    }
                    $xml .= '       <valor>' . $imo->valor_locacao . '</valor>';
                    $xml .= '       <condominio>' . $imo->valor_condominio . '</condominio>';
                    $xml .= '       <iptu>' . $imo->valor_iptu . '</iptu>';
                    $xml .= '       <quartos>' . $imo->dormitorio . '</quartos>';
                    $xml .= '       <suites>' . $imo->suite . '</suites>';
                    $xml .= '       <banheiros>' . $imo->banheiro . '</banheiros>';
                    $xml .= '       <vagas>' . $imo->garagem . '</vagas> ';
                    $xml .= '       <areaConstruida>' . $imo->area_construida . '</areaConstruida>';
                    $xml .= '       <areaTotal>' . $imo->area_total . '</areaTotal>';
                    $xml .= '       <descricao><![CDATA[' . $imo->descricao . ']]></descricao>';

                    //
                    // caracteristicas
                    //
                /*
                      include 'imovel_caracteristica.php';
                      $caracs = json_decode(imovel_caracteristica_listar($imo->id));
                      foreach ($caracs as $id) {
                      $car = json_decode(imovel_caracteristica_carregar($id));
                      include 'portal_caracteristica.php';
                      $carac = json_decode(portal_caracteristica_procurar($portal_id, $car->caracteristica));
                      if ($carac) {
                      $xml .= '       <' . $carac->caracteristica_portal . '>1</' . $carac->caracteristica_portal . '>';
                      }
                      }
                      //
                     * 
                     */


                    include 'imovel_foto.php';
                    $fot = json_decode(imovel_foto_listar2($imo->id));
                    $fot = array_slice($fot, 0, 30);

                    $url_foto = $por->url;

                    if (empty($url_foto)) {
                        $url_foto = '/fotos/';
                    }

                    if ($fot) {
                        $xml .= '       <fotos>';
                        $x = 0;
                        $x2 = '1';
                        foreach ($fot as $foto_id) {
                            $fx = json_decode(imovel_foto_carregar($foto_id));
                            $xml .= '<foto>' . $url_foto . $fx->foto . '</foto>';
                            $x++;
                            $x2 = '0';
                        }
                        $xml .= '       </fotos>';
                    }
                    //
                    /*
                      if (!empty($imo->video_youtube) && strpos($imo->video_youtube, 'youtube.com') > 0) {
                      $xml .= '       <Videos>';
                      $xml .= '           <Video>';
                      $xml .= '               <Descricao>Vídeo do Imóvel</Descricao>';
                      $xml .= '               <URLArquivo>' . $imo->video_youtube . '</URLArquivo>';
                      $xml .= '               <Principal>1</Principal>';
                      $xml .= '           </Video>';
                      $xml .= '       </Videos>';
                      }
                      //
                     * 
                     */
                    $xml .= '       </imovel>';
                    $ger++;

                    publicar_data_envio($id, $tipo_anuncio);
                    if ($ger > 0) {
                        $carga_envio .= ', ';
                    }
                    $carga_envio .= $id;

                    $ret = fwrite($fop, $xml);
                    $xml = '';
                    echo '.';
                }
            }
            $xml .= '</imoveis>';

            echo 'OK ' . $ger . ' Imóveis.';

            $ret = fwrite($fop, $xml);
            fclose($fop);

            $ftp = $por->endereco_ftp;
            echo '<br>Conectando ' . $ftp . ' ...';
            $conn_id = ftp_connect($ftp);
            @$login_result = ftp_login($conn_id, $por->usuario_ftp, $por->senha_ftp);
            if ((!$conn_id) || (!$login_result)) {
                echo "Erro FTP!";
                echo "<br><br><font color='red'>Não Foi Possível Transmitir Ofertas.</font>";
                die;
            } else {
                echo "Connectado.";
            }
            echo '<br>Servidor : ' . ftp_systype($conn_id);
            echo '<br>Diretório autal : ' . ftp_pwd($conn_id);

            ftp_pasv($conn_id, false);

            echo '<br>Enviando Carga :';
            if (ftp_put($conn_id, $por->pasta_ftp . '/JAU.XML', $carg, FTP_ASCII)) {
                echo '<br>OK Arquivo enviado.';
                if (file_exists($carg)) {
//                    if (unlink($carg)) {
//                        echo 'OK';
//                    }
                    include '../model/conexao.php';
                    $conexao = new conexao();
                    $conexao->ocorrencia('PORTAL', 'Enviou Carga para ' . $portal_id . ' : ' . $ger . ' Imóveis', $carga_envio . ' arquivo : ' . $carg, '', '');
                }
            } else {
                echo '<br>Erro! Não foi possível enviar.';
            }

            // fim 02-02-2015
            ?>
        </div>
    </body>
</html>