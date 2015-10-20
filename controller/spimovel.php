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
            $portal_id = 'SPIMOVEL';
            $por = json_decode(portal_carregar($portal_id));
            $ret = json_decode(publicar_listar($portal_id));
            $tot = count($ret);
            include 'cadastro.php';


            $arq = md5(uniqid("")) . '.xml';
            $carg = 'carga/' . $arq;

            $fop = fopen($carg, 'w');


            $xml = '<?xml version="1.0" encoding="UTF-8"?><Carga xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
            $xml .= '<Imoveis>';
            $ger = 0;
            foreach ($ret as $id) {
                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));
//                include 'portal_tipo.php';
//                $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
//                if ($ptp) {
//                    $tipo = $ptp->tipo_portal;
//                } else {
//                    $tipo = $imo->tipo_nome;
//                }
//                include 'portal_subtipo.php';
//                $pstp = json_decode(portal_subtipo_procurar($portal_id, $imo->subtipo));
//                if ($pstp) {
//                    $subtipo = $pstp->subtipo_portal;
//                } else {
//                    $subtipo = $imo->subtipo_nome;
//                }


                if ($imo->tipo_nome == 'Casas') {
                    if ($imo->subtipo_nome == 'Sobrado Padrão') {
                        $tipo = 'Sobrado';
                    } elseif ($imo->subtipo_nome == 'Sobrado em Condomínio') {
                        $tipo = 'Sobrado';
                    } elseif ($imo->subtipo_nome == 'Assobradado') {
                        $tipo = 'Sobrado';
                    } else {
                        $tipo = 'Casa Térrea';
                    }
                } elseif ($imo->tipo_nome == 'Apartamentos') {
                    $tipo = 'Apartamento';
                } elseif ($imo->tipo_nome == 'Comercial') {
                    $tipo = 'Sala Comercial';
                } elseif ($imo->tipo_nome == 'Galpões') {
                    $tipo = 'Galpão / Salão';
                } elseif ($imo->tipo_nome == 'Terrenos') {
                    $tipo = 'Terreno';
                } elseif ($imo->tipo_nome == 'Rural') {
                    $tipo = 'Sítio';
                }

                /*
                  Apartamento
                  Apartamento Duplex
                  Casa Comercial
                  Casa Térrea
                  Chácara
                  Cobertura
                  Cobertura Duplex
                  Condomínio Fechado
                  Flat
                  Galpão / Salão
                  Imóvel para Renda
                  Kitchenette
                  Loft
                  Ponto Comercial
                  Prédio Comercial
                  Sala Comercial
                  Sobrado
                  Terreno
                  Sítio
                 * 
                 */

                //
                // Carga
                //
                $xml .= '    <Imovel>';
                $xml .= '       <CodigoImovel>' . $aux->ref . '</CodigoImovel>';
                $xml .= '       <TipoImovel>' . $tipo . '</TipoImovel>';
                //$xml .= '       <SubTipoImovel>' . $subtipo . '</SubTipoImovel>';
                //$xml .= '       <CategoriaImovel>' . $imo->categoria . '</CategoriaImovel>';
                $xml .= '       <Estado>' . $imo->uf . '</Estado>';
                $xml .= '       <Cidade>' . $imo->cidade . '</Cidade>';
                $xml .= '       <Bairro>' . $imo->bairro . '</Bairro>';

                if ($por->enviar_endereco == 'S') {
                    $endereco = $imo->tipo_logradouro . ' ' . $imo->logradouro;
                } else {
                    $endereco = 'Sob Consulta';
                }

                $xml .= '       <Endereco>' . $endereco . '</Endereco>';
                $xml .= '       <Numero>' . $imo->numero . '</Numero>';
                $xml .= '       <Complemento>' . $imo->complemento . '</Complemento>';
                $xml .= '       <CEP>' . $imo->cep . '</CEP>';
                $xml .= '       <PrecoVenda>' . $imo->valor_venda . '</PrecoVenda>';
                $xml .= '       <PrecoLocacao>' . $imo->valor_locacao . '</PrecoLocacao>';
                $xml .= '       <PrecoCondominio>' . $imo->valor_condominio . '</PrecoCondominio>';
                $xml .= '       <PrecoIptuImovel>' . $imo->valor_iptu . '</PrecoIptuImovel>';
                $xml .= '       <AreaUtil>' . $imo->area_util . '</AreaUtil>';
                $xml .= '       <QtdDormitorios>' . $imo->dormitorio . '</QtdDormitorios>';
                $xml .= '       <QtdSuites>' . $imo->suite . '</QtdSuites>';
                $xml .= '       <QtdBanheiros>' . $imo->banheiro . '</QtdBanheiros>';
                $xml .= '       <QtdVagas>' . $imo->garagem . '</QtdVagas> ';
                $xml .= '       <Observacao><![CDATA[' . $imo->descricao . ']]></Observacao>';
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
                    $xml .= '       <Fotos>';
                    $x = 0;
                    $x2 = '1';
                    foreach ($fot as $foto_id) {
                        $fx = json_decode(imovel_foto_carregar($foto_id));
                        $xml .= '           <Foto>';
                        $xml .= '               <NomeArquivo>' . $fx->foto . '</NomeArquivo>';
                        $xml .= '               <URLArquivo>' . $por->url . $fx->foto . '</URLArquivo>';
                        $xml .= '               <Principal>' . $x2 . '</Principal>';
                        //$xml .= '               <Ordem>' . $x . '</Ordem>';
                        $xml .= '           </Foto>';
                        $x++;
                        $x2 = '0';
                    }
                    $xml .= '       </Fotos>';
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
                $xml .= '       </Imovel>';
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
            $xml .= '</Imoveis>';
            $xml .= '</Carga>';

            echo 'OK ' . $ger . ' Imóveis.';

            $ret = fwrite($fop, $xml);
            fclose($fop);



            /* 02-02-2015
              print "<p>Enviando carga : ";
              echo '<br><pre>';

              try {
              $wsdl_url = 'http://www.grupo.spimovel.com.br/Services/IntegracaoSPImovel.asmx?WSDL';
              $client = new SOAPClient($wsdl_url);
              $params = array(
              'usuario' => "$por->usuario",
              'senha' => "$por->senha",
              'imovelXml' => "$xml",
              );
              $return = $client->AtualizaArquivoString($params);
              echo $return->AtualizaArquivoStringResponse;
              } catch (Exception $e) {
              echo "Erro: " . $e;
              }

              echo '</pre>';

              include '../model/conexao.php';
              $conexao = new conexao();
              $conexao->ocorrencia('PORTAL', 'Enviou Carga para ' . $portal_id . ' : ' . $ger . ' Imóveis', $carga_envio . ' arquivo : ' . $carg, '', '');
             * 
             */



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
            if (ftp_put($conn_id, $por->pasta_ftp . '/SPIMOVEL.XML', $carg, FTP_ASCII)) {
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