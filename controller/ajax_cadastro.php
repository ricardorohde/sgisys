<?php

session_start();

include 'cadastro.php';
include 'imovel_caracteristica.php';

include '../view/func.php';

$saida = '';
//
$cad = json_decode(cadastro_carregar($_SESSION['tipo_cadastro'], $_GET['id']));
$saida .= '<div style="cursor: pointer;width: auto;height: auto;" onclick="window.open(\'cadastro.php?id=' . $cad->id . '\',\'_self\');" title="Clique para Ver a Ficha do Im&oacute;vel">';
if ($_SESSION['tipo_cadastro'] == 'imovel') {
    $foto1 = $cad->foto;
    if (empty($foto1)) {
        $foto1 = 'sem_foto.jpg';
    } else {
        if (!file_exists('../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1)) {
            $foto1 = 'sem_foto.jpg';
        }
    }
    //$saida .= '<div class="div-foto-ajax" style="border-radius: 10px;background: white;">';
    //$saida .= '</div>';
    $saida .= '<div style="float:left;width:1250px;height: auto;padding: 30px;border: 3px solid #ccc; margin: 10px;margin-left: 10px;border-radius: 10px;background: white;">';
    $saida .= ' <div style="cursor: pointer;border-radius: 10px;width: 300px;height: 320px;overflow: hidden;float:left; margin-right: 20px;border: 1px solid #ccc;">';
    $saida .= '     <center><img src="../site/fotos/' . $_SESSION['cliente_id'] . '/' . $foto1 . '" height="350" style="margin-left: -20%;z-index: 996;" ></center>';
    $saida .= ' </div>';

    if ($cad->tipo_nome == 'Casas') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="40" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="40" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Complemento&nbsp;<input type="text" value="' . $cad->complemento . '" size="10" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;CEP&nbsp;<input type="text" value="' . $cad->cep . '" size="8" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Bairro</div><div class="div-dir-ajax"><input type="text" value="' . $cad->bairro . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;SubTipo&nbsp;<input type="text" value="' . $cad->subtipo_nome . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Terreno</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_terreno . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Construida&nbsp;<input type="text" value="' . $cad->area_construida . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Frente&nbsp;<input type="text" value="' . $cad->m2_frente . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Fundos&nbsp;<input type="text" value="' . $cad->fundos . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Profundidade&nbsp;<input type="text" value="' . $cad->profundidade . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Obra&nbsp;<input type="text" value="' . $cad->obra . '" size="15" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Dormitórios</div><div class="div-dir-ajax"><input type="text" value="' . $cad->dormitorio . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Banheiros&nbsp;<input type="text" value="' . $cad->banheiro . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Suites&nbsp;<input type="text" value="' . $cad->suite . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Garagens&nbsp;<input type="text" value="' . $cad->garagem . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="60" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } elseif ($cad->tipo_nome == 'Apartamentos') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;SubTipo&nbsp;<input type="text" value="' . $cad->subtipo_nome . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Edificio&nbsp;<input type="text" value="' . $cad->edificio . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;NºApto&nbsp;<input type="text" value="' . $cad->numero_apartamento . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">CEP</div><div class="div-dir-ajax"><input type="text" value="' . $cad->cep . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="40" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Bairro&nbsp;<input type="text" value="' . $cad->bairro . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Total</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_total . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Util&nbsp;<input type="text" value="' . $cad->area_util . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Dormitorios&nbsp;<input type="text" value="' . $cad->dormitorio . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Banheiros&nbsp;<input type="text" value="' . $cad->banheiro . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Suites&nbsp;<input type="text" value="' . $cad->suite . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Garagens&nbsp;<input type="text" value="' . $cad->garagem . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="60" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Obra&nbsp;<input type="text" value="' . $cad->obra . '" size="20" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } elseif ($cad->tipo_nome == 'Galpões') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;NºGalpão&nbsp;<input type="text" value="' . $cad->numero_galpao . '" size="10" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">CEP</div><div class="div-dir-ajax"><input type="text" value="' . $cad->cep . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="40" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Bairro&nbsp;<input type="text" value="' . $cad->bairro . '" size="25" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Terreno</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_terreno . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Construida&nbsp;<input type="text" value="' . $cad->area_construida . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Escritorio&nbsp;<input type="text" value="' . $cad->area_escritorio . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Galpão&nbsp;<input type="text" value="' . $cad->area_galpao . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Pé Direito&nbsp;<input type="text" value="' . $cad->pe_direito . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Fabril</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_fabril . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Vão Livre&nbsp;<input type="text" value="' . $cad->vao_livre . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Patio&nbsp;<input type="text" value="' . $cad->area_patio . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Metragem&nbsp;<input type="text" value="' . $cad->metragem . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cabine Primária&nbsp;<input type="text" value="' . $cad->cabine_primaria . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="60" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Banheiros&nbsp;<input type="text" value="' . $cad->banheiros . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Garagem&nbsp;<input type="text" value="' . $cad->garagem . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Obra&nbsp;<input type="text" value="' . $cad->obra . '" size="15" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } elseif ($cad->tipo_nome == 'Rural') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="40" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Complemento&nbsp;<input type="text" value="' . $cad->complemento . '" size="10" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;CEP&nbsp;<input type="text" value="' . $cad->cep . '" size="8" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Bairro</div><div class="div-dir-ajax"><input type="text" value="' . $cad->bairro . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="20" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Terreno</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_terreno . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Util&nbsp;<input type="text" value="' . $cad->area_util . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Frente&nbsp;<input type="text" value="' . $cad->m2_frente . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Fundos&nbsp;<input type="text" value="' . $cad->fundos . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Profundidade&nbsp;<input type="text" value="' . $cad->profundidade . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Obra&nbsp;<input type="text" value="' . $cad->obra . '" size="15" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Dormitórios</div><div class="div-dir-ajax"><input type="text" value="' . $cad->dormitorio . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Banheiros&nbsp;<input type="text" value="' . $cad->banheiro . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Suites&nbsp;<input type="text" value="' . $cad->suite . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Garagens&nbsp;<input type="text" value="' . $cad->garagem . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="60" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="50" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } elseif ($cad->tipo_nome == 'Comercial') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="25" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Torre&nbsp;<input type="text" value="' . $cad->torre . '" size="30" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">CEP</div><div class="div-dir-ajax"><input type="text" value="' . $cad->cep . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cj/Loja/Sl&nbsp;<input type="text" value="' . $cad->conjunto . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Andar&nbsp;<input type="text" value="' . $cad->andar . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Cidade</div><div class="div-dir-ajax"><input type="text" value="' . $cad->cidade . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="30" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Terreno</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_terreno . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Util&nbsp;<input type="text" value="' . $cad->area_util . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Banheiros&nbsp;<input type="text" value="' . $cad->banheiro . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Garagens&nbsp;<input type="text" value="' . $cad->garagem . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="50" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Obra&nbsp;<input type="text" value="' . $cad->obra . '" size="50" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } elseif ($cad->tipo_nome == 'Terrenos') {
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Ref</div><div class="div-dir-ajax"><input type="text" value="' . $cad->id . '" size="6" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Situação&nbsp;<input type="text" value="' . $cad->situacao . '" size="15" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Localização</div><div class="div-dir-ajax"><input type="text" value="' . $cad->localizacao . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Endereço&nbsp;<input type="text" value="' . $cad->tipo_logradouro . ' ' . $cad->logradouro . ' ' . $cad->numero . '" size="40" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Complemento&nbsp;<input type="text" value="' . $cad->complemento . '" size="10" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;CEP&nbsp;<input type="text" value="' . $cad->cep . '" size="8" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Bairro</div><div class="div-dir-ajax"><input type="text" value="' . $cad->bairro . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Cidade&nbsp;<input type="text" value="' . $cad->cidade . '" size="30" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Tipo&nbsp;<input type="text" value="' . $cad->tipo_nome . '" size="20" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;SubTipo&nbsp;<input type="text" value="' . $cad->subtipo_nome . '" size="20" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Area Terreno</div><div class="div-dir-ajax"><input type="text" value="' . $cad->area_terreno . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Area Construida&nbsp;<input type="text" value="' . $cad->area_construida . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Quadra&nbsp;<input type="text" value="' . $cad->quadra . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Lote&nbsp;<input type="text" value="' . $cad->lote . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Frente&nbsp;<input type="text" value="' . $cad->m2_frente . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Fundos&nbsp;<input type="text" value="' . $cad->fundos . '" size="5" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Profundidade&nbsp;<input type="text" value="' . $cad->profundidade . '" size="5" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        $saida .= ' <div class="div-esq-ajax">Chaves</div><div class="div-dir-ajax"><input type="text" value="' . $cad->chaves . '" size="60" readonly ></div>';
        $saida .= ' &nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;Condominio&nbsp;<input type="text" value="' . $cad->condominio . '" size="50" readonly ></div>';
        $saida .= '</div>';
        $saida .= '<div class="div-linha-ajax">';
        if (imovel_caracteristica_procurar($cad->id, '129')) {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Aceita Financimento</div>';
        }
        if ($cad->internet == 'Sim') {
            $saida .= '&nbsp;<div class="div-dir-ajax">&nbsp;&nbsp;✔ Na Internet</div>';
        }
        $saida .= '</div>';
        $saida .= '<hr>';
    } else {
        $saida .= '<h3>Tipo ' . $cad->tipo_nome . ' Não Reconhecido.</h3>';
        $saida .= '<p>Favor enviar um email para desenvolvimento@sgifacil.com.br informando a Referência deste imóvel.</p>';
    }
    $saida .= '<div class="div-linha-ajax">';
    $saida .= '<table>';
    $saida .= ' <tr>';
    $saida .= '     <td>';
    $saida .= '         <div class="div-linha-ajax">';
    $saida .= '             <div class="div-esq-ajax">Valor Venda</div><div class="div-dir-ajax"><input type="text" value="' . number_format($cad->valor_venda, 2, ',', '.') . '" size="14" readonly  style="text-align: right;"></div>';
    $saida .= '         </div>';
    $saida .= '         <div class="div-linha-ajax">';
    $saida .= '             <div class="div-esq-ajax">Valor Locação</div><div class="div-dir-ajax"><input type="text" value="' . number_format($cad->valor_locacao, 2, ',', '.') . '" size="14" readonly  style="text-align: right;"></div>';
    $saida .= '         </div>';
    $saida .= '         <div class="div-linha-ajax">';
    $saida .= '             <div class="div-esq-ajax">Valor Condomínio</div><div class="div-dir-ajax"><input type="text" value="' . number_format($cad->valor_condominio, 2, ',', '.') . '" size="14" readonly  style="text-align: right;"></div>';
    $saida .= '         </div>';
    $saida .= '         <div class="div-linha-ajax">';
    $saida .= '             <div class="div-esq-ajax">Valor IPTU</div><div class="div-dir-ajax"><input type="text" value="' . number_format($cad->valor_iptu, 2, ',', '.') . '" size="14" readonly style="text-align: right;"></div>';
    $saida .= '         </div>';
    $saida .= '         <div class="div-linha-ajax">';
    $saida .= '             <div class="div-esq-ajax">Data Atualização</div><div class="div-dir-ajax"><input type="text" value="' . data_decode($cad->data_atualizacao) . '" size="12" readonly  style="text-align: center;"></div>';
    $saida .= '         </div>';
    $saida .= '     </td>';
    $saida .= '     <td style="padding: 5px;">';
    $saida .= '         Condições de Pagamento';
    $saida .= '         <br><textarea rows="6" cols="23">' . $cad->condicoes_pagamento . '</textarea>';
    $saida .= '     </td>';
    $saida .= '     <td style="padding: 5px;">';
    $saida .= '         Observação (Internas da Imobiliária)';
    $saida .= '         <br><textarea rows="6" cols="23">' . $cad->observacao . '</textarea>';
    $saida .= '     </td>';
    $saida .= '     <td style="padding: 5px;">';
    $saida .= '         Descrição (Públicas)';
    $saida .= '         <br><textarea rows="6" cols="23">' . $cad->descricao . '</textarea>';
    $saida .= '     </td>';
    $saida .= ' </tr>';
    $saida .= '</table>';
    $saida .= '</div>';
    $saida .= '</div>';
}
//$saida .= '<div style="clear: both;width: 100%;heigth: 10px;"></div>';
//$saida .= '<br><input type="button" value="Fechar" class="botao" onclick="$(\'#mostrar\').fadeOut(500);">';
$saida .= '</div>';
$saida .= '<br><input type="button" value="Fechar" class="botao" onclick="$(\'#mostrar\').fadeOut(500);pesq_rapida();">';
//
echo $saida;
