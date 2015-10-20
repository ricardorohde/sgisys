<?php
session_start();
ini_set('display_errors', 'on');
set_time_limit(300);
$carga_envio = '';
;
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
            $portal_id = 'CRECI';
            $por = json_decode(portal_carregar($portal_id));
            $ret = json_decode(publicar_listar($portal_id));
            $tot = count($ret);
            include 'cadastro.php';



            // 18-01-2015

            $arq = md5(uniqid("")) . '.xml';
            $carg = 'carga/' . $arq;

            $fop = fopen($carg, 'w');


            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<publish xmlns="http://tempuri.org/XMLSchema1.xsd">';
            $xml .= '<publisher>f9b0383b6b97ab9f0d072bf7a6eae886</publisher>';
            $xml .= '<client_info>';
            $xml .= '   <creci>' . $por->codigo_cliente . '</creci>';
            $xml .= '   <password>' . $por->senha . '</password>';
            $xml .= '</client_info>';
            $xml .= '<properties>';
            $ger = 0;
            foreach ($ret as $id) {
                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));

                if ($imo->tipo_nome == 'Casas') {
                    $tipo = 'M-2';
                } elseif ($imo->tipo_nome == 'Apartamentos') {
                    $tipo = 'M-1';
                } elseif ($imo->tipo_nome == 'Comercial') {
                    $tipo = 'M-3';
                } elseif ($imo->tipo_nome == 'Terrenos') {
                    $tipo = 'M-4';
                } elseif ($imo->tipo_nome == 'Rural') {
                    $tipo = 'M-5';
                }


                /*
                  include 'portal_tipo.php';
                  $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
                  if ($ptp) {
                  $tipo = $ptp->tipo_portal;
                  } else {
                  $tipo = $imo->tipo_nome;
                  }
                 * 
                 */


                if ($imo->tipo_nome == 'Casas') {
                    $subtipo = 'M-2-T-1';
                } elseif ($imo->tipo_nome == 'Apartamentos') {
                    $subtipo = 'M-1-T-1';
                } elseif ($imo->tipo_nome == 'Comercial') {
                    $subtipo = 'M-3-T-2';
                } elseif ($imo->tipo_nome == 'Terrenos') {
                    $subtipo = 'M-4-T-1';
                } elseif ($imo->tipo_nome == 'Rural') {
                    $subtipo = 'M-5-T-1';
                }



                //
                //
                /*
                  include 'portal_subtipo.php';
                  $pstp = json_decode(portal_subtipo_procurar($portal_id, $imo->subtipo));
                  if ($pstp) {
                  $subtipo = $pstp->subtipo_portal;
                  } else {
                  $subtipo = $imo->subtipo_nome;
                  }
                 * 
                 */
                //
                // Carga
                //
                $xml .= '<property>';
                $xml .= '    <common_data>';
                
                //
                // 14-04-2015
                //
                
                if ($imo->tipo_nome == 'Casas' || $imo->tipo_nome == 'Terrenos') {
                    $xml .= '<useful_area>' . $imo->area_construida . '</useful_area>';
                    $xml .= '<total_area>' . $imo->area_terreno . '</total_area>';
                } else {
                    $xml .= '<useful_area>' . $imo->area_util . '</useful_area>';
                    $xml .= '<total_area>' . $imo->area_total . '</total_area>';
                }
                
//                $xml .= '           <total_area>' . intval($imo->area_total) . '</total_area>';
//                $xml .= '           <useful_area>' . intval($imo->area_util) . '</useful_area>';
                
                // fim 14-04-2015
                
                
                $xml .= '           <financing>0</financing>';
                $xml .= '           <obs>' . $imo->descricao . '</obs>';
                $xml .= '           <reference_code>' . $aux->ref . '</reference_code>';
                $xml .= '           <master_type>' . $tipo . '</master_type>';
                $xml .= '           <type>' . $subtipo . '</type>';
                $xml .= '           <action>save</action>';


                $xml .= '    <purpose>';
                $xml .= '           <season_price></season_price>';
                $xml .= '           <season_available>0</season_available>';
                $xml .= '           <rent_price>' . intval($imo->valor_locacao) . '</rent_price>';
                if ($imo->valor_locacao > 0) {
                    $xml .= '           <rent_available>1</rent_available>';
                } else {
                    $xml .= '           <rent_available>0</rent_available>';
                }
                $xml .= '           <sell_price>' . intval($imo->valor_venda) . '</sell_price>';
                if ($imo->valor_venda > 0) {
                    $xml .= '           <sell_available>1</sell_available>';
                } else {
                    $xml .= '           <sell_available>0</sell_available>';
                }
                $xml .= '    </purpose>';

                $xml .= '    <address>';
                $xml .= '           <longitude></longitude>';
                $xml .= '           <latitude></latitude>';
                $xml .= '           <neighborhood>' . $imo->bairro . '</neighborhood>';
                $xml .= '           <region>' . $imo->localizacao . '</region>';
                $xml .= '           <zone></zone>';
                $xml .= '           <city>' . $imo->cidade . '</city>';
                $xml .= '           <zipcode>' . $imo->cep . '</zipcode>';
                $xml .= '    </address>';

                $xml .= '    </common_data>';

                $xml .= '    <composition>';
                $xml .= '           <vagancy>' . $imo->garagem . '</vagancy>';
                $xml .= '           <bathroom>' . $imo->banheiro . '</bathroom>';
                $xml .= '           <room>' . $imo->sala . '</room>';
                $xml .= '           <suite>' . $imo->suite . '</suite>';
                $xml .= '           <bedroom>' . $imo->dormitorio . '</bedroom>';
                $xml .= '           <kitchen>0</kitchen>';
                $xml .= '    </composition>';



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
                $fot = array_slice($fot, 0, 30); // IW limita em 30
                if ($fot) {
                    $xml .= '       <photos>';
                    $x = 0;
                    $x2 = '1';
                    foreach ($fot as $foto_id) {
                        $fx = json_decode(imovel_foto_carregar($foto_id));
                        $xml .= '           <photo>';
                        //$xml .= '               <NomeArquivo>' . $fx->foto . '</NomeArquivo>';
                        $xml .= '               <big_file_url><!--[CDATA[' . $por->url . $fx->foto . ']]--></big_file_url>';
                        $xml .= '               <small_file_url><!--[CDATA[' . $por->url . $fx->foto . ']]--></small_file_url>';
                        $xml .= '               <label>Foto ' . $x . '</label>';
                        $xml .= '               <order>' . $x . '</order>';
                        $xml .= '           </photo>';
                        $x++;
                        $x2 = '0';
                    }
                    $xml .= '       </photos>';
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
                $xml .= '</property>';
                $ger++;

                publicar_data_envio($id, $tipo_anuncio);
                if ($ger > 0) {
                    $carga_envio .= ', ';
                }
                $carga_envio .= $id;
            }
            $xml .= '</properties>';
            $xml .= '</publish>';


            $ret = fwrite($fop, $xml);
            fclose($fop);


            echo 'OK ' . $ger . ' Imóveis.';

            print "<p>Enviando carga : ";
            echo '<br>';

            $dados = array('xml' => $xml);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://www.portalcreci.org.br/publicador');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);
            $retorno = curl_exec($curl);
            curl_close($curl);

            echo '<br><pre>';

            $xml = simplexml_load_string($retorno);
            if ($xml->properties->property) {
                foreach ($xml->properties->property as $resu) {
                    if ($resu->result == 1) {
                        echo '<br> Ref. ' . $resu->reference . ' : OK';
                    } else {
                        echo '<br> Ref. ' . $resu->reference . ' : ERRO';
                        foreach ($resu->message as $value) {
                            echo '<br>';
                            print_r($value);
                        }
                    }
                }
            } else {
                echo '<br>Nenhuma carga enviada.';
            }
            include '../model/conexao.php';
            $conexao = new conexao();
            $conexao->ocorrencia('PORTAL', 'Enviou Carga para ' . $portal_id . ' : ' . $ger . ' Imóveis', $carga_envio . ' arquivo : ' . $carg, '', '');
            ?>
        </div>
    </body>
</html>