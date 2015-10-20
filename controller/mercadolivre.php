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
            include 'portal.php';
            $portal_id = 'MERCADOLIVRE';
            $por = json_decode(portal_carregar($portal_id));
            $ret = json_decode(publicar_listar($portal_id));
            $tot = count($ret);
            include 'cadastro.php';
            include 'site_config.php';
            $cfg = json_decode(site_config_carregar());

            //
            //
            //


            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<ListingDataFeed xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
            $xml .= '    <imoveis>';
            $xml .= '    <email>' . $por->codigo_cliente . '</email>';
            $ger = 0;
            foreach ($ret as $id) {
                $aux = json_decode(publicar_carregar($id));
                $imo = json_decode(cadastro_carregar('imovel', $aux->ref));

                $tipo_anuncio = $por->tipo_anuncio_simples;

                if (!empty($aux->tipo_anuncio)) {
                    $tipo_anuncio = $aux->tipo_anuncio;
                }

                include 'portal_tipo.php';
                $ptp = json_decode(portal_tipo_procurar($portal_id, $imo->tipo));
                if ($ptp) {
                    $tipo = $ptp->tipo_portal;
                } else {
                    $tipo = $imo->tipo_nome;
                }


                if ($tipo == 'Galpões') {
                    $tipo = 'Comerciais - Industriais';
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

                $xml .= '       <imovelId>' . $aux->ref . '</imovelId>';
                $xml .= '       <title>' . $imo->tipo_nome . ' em ' . $imo->cidade . '</title>';
                if ($imo->valor_venda > 0) {
                    $xml .= '       <category>' . $tipo . ' > Venda</category>';
                    $xml .= '           <price>' . intval($imo->valor_venda) . '.00</price>';
                } elseif ($imo->valor_locacao > 0) {
                    $xml .= '       <category>' . $tipo . ' > Aluguel</category>';
                    $xml .= '           <price>' . intval($imo->valor_locacao) . '.00</price>';
                }
                $xml .= '       <listingType>' . strtolower($tipo_anuncio) . '</listingType>';
                //
                $xml .= '       <video />';
                include 'imovel_foto.php';
                $fot = json_decode(imovel_foto_listar($imo->id));
                $fot = array_slice($fot, 0, 30); // IW limita em 30
                if ($fot) {
                    $xml .= '       <pictures>';
                    $x = 0;
                    $x2 = '1';
                    foreach ($fot as $foto_id) {
                        if ($x <= 9) {
                            $fx = json_decode(imovel_foto_carregar($foto_id));
                            $xml .= '           <imageURL>' . $por->url . $fx->foto . '</imageURL>';
                            //$xml .= '               <NomeArquivo>' . $fx->foto . '</NomeArquivo>';
                            //$xml .= '               <imageURL>' . $por->url . $fx->foto . '</imageURL>';
                            //$xml .= '               <Principal>' . $x2 . '</Principal>';
                            //$xml .= '               <Alterada>0</Alterada>';
                            //$xml .= '           </imageURL>';
                        }
                        $x++;
                        $x2 = '0';
                    }
                    $xml .= '       </pictures>';
                }
                $xml .= '       <sellerContact>';
                $xml .= '           <contact>' . $cfg->titulo_pagina . '</contact>';
                $xml .= '           <otherInfo></otherInfo>';
                $xml .= '           <areaCode>11</areaCode>';
                $xml .= '           <phone>' . $por->usuario . '</phone>';
                $xml .= '           <email>' . $cfg->email_envio . '</email>';
                $xml .= '           <webpage>' . $cfg->url . '</webpage>';
                $xml .= '       </sellerContact>';

                $xml .= '       <location>';

                if ($por->enviar_endereco == 'S') {
                    $endereco = $imo->tipo_logradouro . ' ' . $imo->logradouro;
                } else {
                    $endereco = 'Sob Consulta';
                }

                $xml .= '           <addressLine>' . $endereco . '</addressLine>';
                $xml .= '           <zipCode>' . $imo->cep . '</zipCode>';
                $xml .= '           <neighborhood>' . $imo->bairro . '</neighborhood>';
                $xml .= '           <city>' . $imo->cidade . '</city>';
                $xml .= '           <state>' . $imo->uf . '</state>';
                $xml .= '           <country>Brasil</country>';
                $xml .= '           <latitude/>';
                $xml .= '           <longitude/>';
                $xml .= '       </location>';
                $xml .= '       <attributes>';
                if ($imo->tipo_nome == 'Apartamentos') {
                    $xml .= '           <attribute><name>Banheiros</name><value>' . $imo->banheiro . '</value></attribute>';
                    $xml .= '           <attribute><name>Dormitórios</name><value>' . $imo->dormitorio . '</value></attribute>';
                    $xml .= '           <attribute><name>Suítes</name><value>' . $imo->suite . '</value></attribute>';
                    $xml .= '           <attribute><name>Área útil (m²)</name><value>' . $imo->area_util . '</value></attribute>';
                    $xml .= '           <attribute><name>Área total (m²)</name><value>' . $imo->area_total . '</value></attribute>';
                    $xml .= '           <attribute><name>Tipo de edifício</name><value>Apartamento padrão</value></attribute>';
                } elseif ($imo->tipo_nome == 'Casas') {
                    $xml .= '           <attribute><name>Banheiros</name><value>' . $imo->banheiro . '</value></attribute>';
                    $xml .= '           <attribute><name>Dormitórios</name><value>' . $imo->dormitorio . '</value></attribute>';
                    $xml .= '           <attribute><name>Área útil (m²)</name><value>' . $imo->area_util . '</value></attribute>';
                    $xml .= '           <attribute><name>Área total (m²)</name><value>' . $imo->area_total . '</value></attribute>';
                    $xml .= '           <attribute><name>Tipo de propriedade</name><value>Térrea</value></attribute>';
                } elseif ($imo->tipo_nome == 'Rural') {
                    $xml .= '           <attribute><name>Acceso</name><value>Outro acesso</value></attribute>';
                    $xml .= '           <attribute><name>Distância do asfalto (Km)</name><value>1</value></attribute>';
                    $xml .= '           <attribute><name>Área total (Hectáres)</name><value>0</value></attribute>';
                    $xml .= '           <attribute><name>Sede</name><value>Não</value></attribute>';
                    $xml .= '           <attribute><name>Andares</name><value>0</value></attribute>';
                    $xml .= '           <attribute><name>Tipo de propriedade</name><value>' . $imo->subtipo_nome . '</value></attribute>';
                } elseif ($imo->tipo_nome == 'Terrenos') {
                    $xml .= '           <attribute><name>Área total (m²)</name><value>' . $imo->area_total . '</value></attribute>';
                } elseif ($imo->tipo_nome == 'Galpões') {
                    $xml .= '           <attribute><name>Banheiros</name><value>' . $imo->banheiro . '</value></attribute>';
                }

                $xml .= '           <attribute><name>Idade do imóvel</name><value>Não informado</value></attribute>';
                $xml .= '       </attributes>';
                $xml .= '           <description><![CDATA[' . $imo->descricao . ']]></description>';
                //
                /*
                  $xml .= '       <SubTipoimovel>' . $subtipo . '</SubTipoimovel>';
                  $xml .= '       <Categoriaimovel>Padrão</Categoriaimovel>';
                  $xml .= '       <Cidade>' . $imo->cidade . '</Cidade>';
                  $xml .= '       <Bairro>' . $imo->bairro . '</Bairro>';
                  $xml .= '       <Numero/>';
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
                  include 'imovel_caracteristica.php';
                  $caracs = json_decode(imovel_caracteristica_listar($imo->id));
                  foreach ($caracs as $id) {
                  $car = json_decode(imovel_caracteristica_carregar($id));
                  include 'portal_caracteristica.php';
                  $carac = json_decode(portal_caracteristica_procurar($portal_id, $car->caracteristica));
                  if ($carac) {
                  $xml .= '<' . $carac->caracteristica_portal . '>1</' . $carac->caracteristica_portal . '>';
                  }
                  }

                  $xml .= '<TipoOferta>1</TipoOferta>';

                 */


                //
                $xml .= '       </imovel>';
                $ger++;

                publicar_data_envio($id, $tipo_anuncio);
                if ($ger > 0) {
                    $carga_envio .= ', ';
                }
                $carga_envio .= $id;
            }
            $xml .= '</imoveis>';
            $xml .= '</ListingDataFeed>';

            echo 'OK ' . $ger . ' Imóveis.';

            $arq = md5(uniqid("")) . '.xml';
            $carg = 'carga/' . $arq;

            $fop = fopen($carg, 'w');
            $ret = fwrite($fop, $xml);

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
            if (ftp_put($conn_id, $por->pasta_ftp . '/MERCADOLIVRE.XML', $carg, FTP_ASCII)) {
                echo '<br>Arquivo enviado.';
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