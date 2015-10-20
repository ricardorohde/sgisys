<?php
session_start();
ini_set('display_errors', 'on');
ini_set('max_execution_time', '600');
set_time_limit(600);
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
            include 'portal_caracteristica.php';
            include 'imovel_caracteristica.php';
            $portal_id = 'VIVAREAL';
            $por = json_decode(portal_carregar($portal_id));

            $ret = json_decode(publicar_listar($portal_id));

            $ret = array_slice($ret, 0, 500); // 

            $tot = count($ret);
            include 'cadastro.php';
            include 'imovel_foto.php';
            include 'portal_tipo.php';
            include 'portal_subtipo.php';

            //25-01-2015
            //
            
            $arq = md5(uniqid("")) . '.xml';
            $carg = 'carga/' . $arq;

            $fop = fopen($carg, 'w');

            $xml = '<?xml version="1.0" encoding="UTF-8"?><ListingDataFeed xmlns="http://www.vivareal.com/schemas/1.0/VRSync" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.vivareal.com/schemas/1.0/VRSync  http://xml.vivareal.com/vrsync.xsd">';
            $xml .= '<Header>';
            $xml .= '   <Provider>' . $por->codigo_cliente . '</Provider>';
            $xml .= '   <Email>' . $por->usuario . '</Email>';
            $xml .= '</Header>';
            $xml .= '<Listings>';
            $ger = 0;
            foreach ($ret as $id) {
                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));

                $tipo_anuncio = '';

                if (!empty($aux->tipo_anuncio)) {
                    if ($aux->tipo_anuncio == 'Simples') {
                        $tipo_anuncio = '';
                    } else {
                        $tipo_anuncio = 'True';
                    }
                }


                $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
                if ($ptp) {
                    $tipo = $ptp->tipo_portal;
                } else {
                    $tipo = $imo->tipo_nome;
                }

                if ($imo->tipo_nome == 'Casas') {
                    $tipo = 'Residential / Home';
                } elseif ($imo->tipo_nome == 'Apartamentos') {
                    $tipo = 'Residential / Apartment';
                } elseif ($imo->tipo_nome == 'Comercial') {
                    $tipo = 'Commercial / Office';
                } elseif ($imo->tipo_nome == 'Terrenos') {
                    $tipo = 'Residential / Land Lot';
                } elseif ($imo->tipo_nome == 'Rural') {
                    $tipo = 'Residential / Farm Ranch';
                }
                
                //
                //
                //
                $pstp = json_decode(portal_subtipo_procurar($portal_id, $imo->subtipo));
                if ($pstp) {
                    $subtipo = $pstp->subtipo_portal;
                } else {
                    $subtipo = $imo->subtipo_nome;
                }
                //
                // Carga
                //
                $xml .= '    <Listing>';
                $xml .= '       <ListingID>' . $aux->ref . '</ListingID>';
                if ($imo->valor_venda > 0 && $imo->valor_locacao == 0) {
                    $xml .= '       <TransactionType>For Sale</TransactionType>';
                }
                if ($imo->valor_venda == 0 && $imo->valor_locacao > 0) {
                    $xml .= '       <TransactionType>For Rent</TransactionType>';
                }
                if ($imo->valor_venda > 0 && $imo->valor_locacao > 0) {
                    $xml .= '       <TransactionType>Sale/Rent</TransactionType>';
                }
                $xml .= '       <Featured>' . $tipo_anuncio . '</Featured>';
                $xml .= '       <Title>' . $tipo . ' em ' . $imo->bairro . '</Title>';
                // featured?
                $xml .= '       <Details>';
                $xml .= '           <PropertyType>' . $tipo . '</PropertyType>';
                $xml .= '           <Description><![CDATA[' . trim($imo->descricao) . ']]></Description>';
                if ($imo->valor_venda > 0) {
                    $xml .= '           <ListPrice currency="BRL">' . intval($imo->valor_venda) . '</ListPrice>';
                }
                if ($imo->valor_locacao > 0) {
                    $xml .= '           <RentalPrice  currency="BRL">' . intval($imo->valor_locacao) . '</RentalPrice>';
                }
                if ($imo->area_terreno > 0) {
                    $xml .= '           <LotArea unit="square metres">' . intval($imo->area_terreno) . '</LotArea>';
                }
                if ($imo->area_construida > 0) {
                    $xml .= '           <ConstructedArea unit="square metres">' . intval($imo->area_construida) . '</ConstructedArea>';
                }
                if ($imo->area_util > 0) {
                    $xml .= '           <LivingArea unit="square metres">' . intval($imo->area_util) . '</LivingArea>';
                }
                if ($imo->banheiro > 0) {
                    $xml .= '           <Bathrooms>' . intval($imo->banheiro) . '</Bathrooms>';
                }
                if ($imo->dormitorio > 0) {
                    $xml .= '           <Bedrooms>' . intval($imo->dormitorio) . '</Bedrooms>';
                }
                if ($imo->dormitorio > 0) {
                    $xml .= '           <Suites>' . intval($imo->suite) . '</Suites>';
                }
                if ($imo->garagem > 0) {
                    $xml .= '           <Garage type="Parking Space">' . intval($imo->garagem) . '</Garage>';
                }
                $xml .= '           <Features>';
                //caracteristicas

                
                  $caracs = json_decode(cadastro_listar('imovel_caracteristica', " and ref='$imo->id' ", $order, $rows));
                  $saida .= '<td>';
                  $x = 0;
                  
                  $caracs = array_slice($caracs, 0, 20);
                  
                  foreach ($caracs as $car_id) {
                  $car = json_decode(imovel_caracteristica_carregar($carac_id));
                  $carac = json_decode(portal_caracteristica_procurar($portal_id, $car->caracteristica));
                  if ($carac) {
                  $xml .= '       <Feature>' . $carac->caracteristica_portal . '</Feature>';
                  }
                  }
                 
                //
                $xml .= '           </Features>';
                $xml .= '       </Details>';
                $xml .= '       <Location>';
                $xml .= '           <Country abbreviation="BR">Brasil</Country>';
                $xml .= '           <State abbreviation="' . $imo->uf . '">' . $imo->uf . '</State>';
                $xml .= '           <City>' . $imo->cidade . '</City>';
                $xml .= '           <Neighborhood>' . $imo->bairro . '</Neighborhood>';


                if ($por->enviar_endereco == 'S') {
                    $endereco = $imo->tipo_logradouro . ' ' . $imo->logradouro;
                    $cep = $imo->cep;
                    $visi = 'true';
                } else {
                    $endereco = 'Sob Consulta';
                    $cep = '';
                    $visi = 'false';
                }


                $xml .= '           <Address publiclyVisible = "' . $visi . '">' . $endereco . '</Address>';
                $xml .= '           <PostalCode>' . $cep . '</PostalCode >';
                $xml .= '       </Location>';
                $xml .= '       <ContactInfo>';
                $xml .= '           <Name>' . $por->codigo_cliente . '</Name>';
                $xml .= '           <Email>' . $por->usuario . '</Email>';
                $xml .= '           <Location>';
                $xml .= '               <Country abbreviation="BR">Brasil</Country>';
                $xml .= '           </Location>';
                $xml .= '       </ContactInfo>';


                //

                $fot = json_decode(imovel_foto_listar2($imo->id));
                $fot = array_slice($fot, 0, 20); // IW limita em 20
                if ($fot) {
                    $xml .= '       <Media>';
                    $x = 0;
                    $x2 = ' primary="true"';
                    foreach ($fot as $foto_id) {
                        $fx = json_decode(imovel_foto_carregar($foto_id));
                        $xml .= '<Item medium="image" caption="Foto ' . $x . '" ' . $x2 . ' >' . $por->url . $fx->foto . '</Item>';
                        $x++;
                        $x2 = '';
                    }
                    $xml .= '       </Media>';
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
                $xml .= '       </Listing>';
                $ger++;


                $tipo_anuncio = $aux->tipo_anuncio;

                if (empty($tipo_anuncio)) {
                    $tipo_anuncio = $por->tipo_anuncio_simples;
                }

                publicar_data_envio($id, $tipo_anuncio);
                if ($ger > 0) {
                    $carga_envio .= ', ';
                }
                $carga_envio .= $id;

                $ret = fwrite($fop, $xml);
                $xml = '';
                echo '.';
            }
            $xml .= '</Listings>';
            $xml .= '</ListingDataFeed>';

            echo 'OK ' . $ger . ' Imóveis.';


            $ret = fwrite($fop, $xml);
            fclose($fop);
//            if ($ret) {
//                echo '<br><br>arquivo temporário gravado com sucesso : [ <a href="http://sgiplus.com.br/controller/' . $carg . '">' . $arq . '</a> ]';
//            }

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
            if (ftp_put($conn_id, $por->pasta_ftp . '/VIVAREAL.XML', $carg, FTP_ASCII)) {
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
            ?>
        </div>
    </body>
</html>