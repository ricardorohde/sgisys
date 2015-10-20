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
            $portal_id = 'TIQUE';
            $por = json_decode(portal_carregar($portal_id));
            $ret = json_decode(publicar_listar($portal_id));
            $tot = count($ret);
            include 'cadastro.php';
            $xml = '<?xml version="1.0" encoding="UTF-8"?><Carga xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
            $xml .= '<Imoveis>';
            $ger = 0;
            foreach ($ret as $id) {
                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));

                $tipo_anuncio = '';

                if (!empty($aux->tipo_anuncio)) {
                    if ($aux->tipo_anuncio == 'Simples') {
                        $tipo_anuncio = '';
                    } else {
                        $tipo_anuncio = '<EmDestaque>1</EmDestaque>';
                    }
                }

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
                $xml .= '    <Imovel>';
                $xml .= '       <CodigoCliente>' . $aux->ref . '</CodigoCliente>';
                $xml .= '       <CodigoImovel>' . $aux->ref . '</CodigoImovel>';
                $xml .= '       <TipoImovel>' . $tipo . '</TipoImovel>';
                $xml .= '       <SubTipoImovel>' . $subtipo . '</SubTipoImovel>';
                $xml .= '       <CategoriaImovel>Padrão</CategoriaImovel>';
                $xml .= '       <Cidade>' . $imo->cidade . '</Cidade>';
                $xml .= '       <Bairro>' . $imo->bairro . '</Bairro>';
                $xml .= '       <Numero/>';

                $xml .= $tipo_anuncio;

                $xml .= '       <Complemento/>';
                $xml .= '       <CEP/>';
                if ($imo->valor_venda > 0) {
                    $xml .= '           <PrecoVenda>' . intval($imo->valor_venda) . '</PrecoVenda>';
                }
                if ($imo->valor_locacao > 0) {
                    $xml .= '           <PrecoLocacao>' . intval($imo->valor_locacao) . '</PrecoLocacao>';
                }
                if ($imo->valor_condominio > 0) {
                    $xml .= '           <PrecoCondominio>' . intval($imo->valor_condominio) . '</PrecoCondominio>';
                }
                $xml .= '           <AreaUtil>' . intval($imo->area_util) . '</AreaUtil>';
                $xml .= '           <QtdDormitorios>' . $imo->dormitorio . '</QtdDormitorios>';
                if ($imo->suite > 0) {
                    $xml .= '           <QtdSuites>' . $imo->suite . '</QtdSuites>';
                }
                if ($imo->banheiro > 0) {
                    $xml .= '           <QtdBanheiros>' . $imo->banheiro . '</QtdBanheiros>';
                }
                if ($imo->garagem > 0) {
                    $xml .= '           <QtdVagas>' . $imo->garagem . '</QtdVagas>';
                }
                if ($imo->ano_construcao > 0) {
                    $xml .= '           <AnoConstrucao>' . $imo->ano_construcao . '</AnoConstrucao>';
                }

                $xml .= '           <Observacao>' . $imo->descricao . '</Observacao>';
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

                $tipo_anuncio = $por->tipo_anuncio_simples;

                if (!empty($aux->tipo_anuncio)) {
                    $tipo_anuncio = $aux->tipo_anuncio;
                }

                publicar_data_envio($id, $tipo_anuncio);
                if ($ger > 0) {
                    $carga_envio .= ', ';
                }
                $carga_envio .= $id;
            }
            $xml .= '</Imoveis>';
            $xml .= '</Carga>';

            echo 'OK ' . $ger . ' Imóveis.';

            print "<p>Enviando carga : ";
            echo '<br><pre>';

            try {
                $wsdl_url = 'http://ws.tiqueimoveis.com.br/wsTique.asmx?WSDL';
                $client = new SOAPClient($wsdl_url);
                $params = array(
                    'p_Cod_Cliente' => "$por->codigo_cliente",
                    'p_Senha_WS' => "$por->senha",
                    'p_Conteudo_Arquivo_XML' => "$xml",
                );
                $return = $client->EnviaXMLemFormatoTexto($params);
                echo $return->EnviaXMLemFormatoTextoResponse;
                print_r($return);
            } catch (Exception $e) {
                echo "Exception occured: " . $e;
            }

            include '../model/conexao.php';
            $conexao = new conexao();
            $conexao->ocorrencia('PORTAL', 'Enviou Carga para ' . $portal_id . ' : ' . $ger . ' Imóveis', $carga_envio . ' arquivo : ' . $carg, '', '');
            ?>
        </div>
    </body>
</html>