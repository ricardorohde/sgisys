function cadastro_mostrar(id) {

    document.getElementById('carregando').style.display = 'block';

    $("#pesq_rapida").fadeOut(500);
    $("#mostrar").fadeOut(500);
    $("#Pesquisar").fadeOut(500);
    $("#tipos-imovel").fadeOut(500);
    $("#pesquisa").fadeOut(500);

    $("#listagem").animate({height: 300}, 200);

    xmlhttp1 = new XMLHttpRequest();
    xmlhttp1.onreadystatechange = function ()
    {
        if (xmlhttp1.readyState == 4) {
            if (xmlhttp1.status == 200)
            {
                document.getElementById('mostrar').innerHTML = xmlhttp1.responseText;
                $("#mostrar").fadeIn(500);
                $('#carregando').fadeOut(1000);
            }
        }
    }
    xmlhttp1.open("GET", "../controller/ajax_cadastro.php?id=" + id, true);
    xmlhttp1.send();
}



function cadastro_pesquisar() {

    document.getElementById('pesquisa_rapida').value = '';
    $("#pesq_rapida").fadeOut(500);
    $("#mostrar").fadeOut(500);
    $("#Pesquisar").fadeOut(500);
    $("#tipos-imovel").fadeOut(500);
    $("#pesquisa").fadeIn(500);
    $("#listagem").animate({height: 600}, 200);

}

function pesq_rapida() {

    $("#Pesquisar").fadeIn(500);
    $("#tipos-imovel").fadeIn(500);
    $("#pesq_rapida").fadeIn(500);
    $('#pesquisa').fadeOut(500);
}

function mostra_fotos() {
    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);
    $("#div-fotos").fadeIn(1000);

}

function oculta_fotos(id) {

    $("#div-fotos").fadeOut(500);
    $("#fundo-negro").fadeOut(1000);

    window.open('cadastro.php?id=' + id, '_self');


}

function imovel_subtipo() {

    var tipo = document.getElementById('tipo').value;
    var subtipo = document.getElementById('subtipo');
    subtipo.options.length = 0;
    subtipo.options[0] = new Option('▸', '');

    xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function ()
    {
        if (xmlhttp2.readyState == 4) {
            if (xmlhttp2.status == 200)
            {
                subtipo.options.length = 0;
                subtipos = JSON.parse(xmlhttp2.responseText);
                for (i = 0; i < subtipos.length; i++) {
                    subtipo.options[i] = new Option(subtipos[i].subtipo, subtipos[i].id);
                }
            }
        }
    }
    xmlhttp2.open("GET", "../controller/ajax_imovel_subtipo.php?tipo=" + tipo, true);
    xmlhttp2.send();

}

function quadro_edita(tipo_cadastro, campo, id) {
    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);

    window.open('cadastro_edita.php?tipo_edita=' + tipo_cadastro + '&campo_edita=' + campo + '&id=' + id, 'frmEdita');

    $("#quadro-edita").fadeIn(500);

}

function edita_voltar(tipo, campo, id, vinculo) {

    document.getElementById('carregando').style.display = 'block';
    $('#fundo-negro').fadeOut(1000);

    var campo_edita = window.top.frmPrincipal.document.getElementById(campo);

    campo_edita.value = id;
    window.top.frmPrincipal.$('#quadro-edita').fadeOut(500);
    window.top.frmPrincipal.$('#fundo-negro').fadeOut(1000);
    window.open('about:blank', 'frmEdita');

}

//function edita_voltar(tipo, campo, id, vinculo) {
//
//    document.getElementById('carregando').style.display = 'block';
//    $('#fundo-negro').fadeOut(1000);
//
//    var campo_edita = window.top.frmPrincipal.document.getElementById(campo);
//    campo_edita.options.length = 0;
//    campo_edita.options[0] = new Option( '▸','');
//
//    xmlhttp3 = new XMLHttpRequest();
//    xmlhttp3.onreadystatechange = function()
//    {
//        if (xmlhttp3.readyState == 4) {
//            if (xmlhttp3.status == 200)
//            {
//                campo_edita.options.length = 0;
//                subtipos = JSON.parse(xmlhttp3.responseText);
//                for (i = 0; i < subtipos.length; i++) {
//                    if (subtipos[i][1] != null) {
//                        campo_edita.options[i] = new Option(subtipos[i][1], subtipos[i][0]);
//                    }
//                }
//                campo_edita.value = id;
//                window.top.frmPrincipal.$('#quadro-edita').fadeOut(500);
//                window.top.frmPrincipal.$('#fundo-negro').fadeOut(1000);
//            }
//        }
//    }
//    xmlhttp3.open("GET", "../controller/ajax_carrega_campos.php?tipo=" + tipo + "&campo=" + vinculo, true);
//    xmlhttp3.send();
//
//}


function imovel_condominio() {

    var condominio = document.getElementById('data_condominio');
    condominio.options.length = 0;
    condominio.options[0] = new Option('▸', '');

    var tmp = '';
    xmlhttp4 = new XMLHttpRequest();
    xmlhttp4.onreadystatechange = function ()
    {
        if (xmlhttp4.readyState == 4) {
            if (xmlhttp4.status == 200)
            {
                condominio.innerHTML = '';
                condominios = JSON.parse(xmlhttp4.responseText);
                for (i = 0; i < condominios.length; i++) {
                    tmp = tmp + '<option value="' + condominios[i] + '">';
                }
                condominio.innerHTML = tmp;
            }
        }
    }
    xmlhttp4.open("GET", "../controller/ajax_imovel_condominio.php", true);
    xmlhttp4.send();

}

function caracteristicas() {

    var id = document.getElementById('id').value;

    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);

    window.open('imovel_caracteristica.php?id=' + id, 'frmEdita');

    $("#quadro-edita").fadeIn(500);


}

function portais() {

    var id = document.getElementById('id').value;

    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);

    window.open('imovel_portais.php?id=' + id, 'frmEdita');

    $("#quadro-edita").fadeIn(500);


}

function  atualizacoes() {

    var id = document.getElementById('id').value;

    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);

    window.open('imovel_atualizacoes.php?id=' + id, 'frmEdita');

    $("#quadro-edita").fadeIn(500);


}

function  ofertas() {

    var id = document.getElementById('id').value;

    var alturaTela = $(document).height();
    var larguraTela = $(window).width();

    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});
    $('#fundo-negro').fadeIn(1000);

    window.open('comprador_ofertas.php?id=' + id, 'frmEdita');

    $("#quadro-edita").fadeIn(500);


}

function seta_option(campo, valor) {
    document.getElementById(campo).value = valor;
}


function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58))
        return true;
    else {
        if (tecla == 8 || tecla == 0)
            return true;
        else
            return false;
    }
}

function pesquisa_tiponome(nivel) {

    document.getElementById('carregando2').style.display = 'block';

    var tipo_nome = document.getElementById('tipo_nome');
    var subtipo_nome = document.getElementById('subtipo_nome');
    var cidade = document.getElementById('cidade');
    var bairro = document.getElementById('bairro');
    var localizacao = document.getElementById('localizacao');
    var condominio = document.getElementById('condominio');
    var edificio = document.getElementById('edificio');
    var endereco = document.getElementById('endereco');

    if (nivel == 1) {
        // subtipo
        subtipo_nome.options.length = 0;
        subtipo_nome.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    subtipo_nome.options.length = 0;
                    subtipo_nome.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        subtipo_nome.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(2);
                    carrega_valores(1);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=subtipo_nome&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 2) {
        // subtipo
        cidade.options.length = 0;
        cidade.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    cidade.options.length = 0;
                    cidade.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        cidade.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(3);
                    carrega_valores(2);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=cidade&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 3) {
        // subtipo
        bairro.options.length = 0;
        bairro.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    bairro.options.length = 0;
                    bairro.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        bairro.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(4);
                    carrega_valores(3);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=bairro&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 4) {
        // subtipo
        localizacao.options.length = 0;
        localizacao.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    localizacao.options.length = 0;
                    localizacao.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        localizacao.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(5);
                    carrega_valores(4);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=localizacao&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 5) {
        // subtipo
        condominio.options.length = 0;
        condominio.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    condominio.options.length = 0;
                    condominio.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        condominio.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(6);
                    carrega_valores(5);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=condominio&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 6) {
        // subtipo
        edificio.options.length = 0;
        edificio.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    edificio.options.length = 0;
                    edificio.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        edificio.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    pesquisa_tiponome(7);
                    carrega_valores(6);
                }
            }
        }
        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=edificio&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }
    if (nivel == 7) {
        // subtipo
        endereco.options.length = 0;
        endereco.options[0] = new Option('▸', '');

        xmlhttpSn = new XMLHttpRequest();
        xmlhttpSn.onreadystatechange = function ()
        {
            if (xmlhttpSn.readyState == 4) {
                if (xmlhttpSn.status == 200)
                {
                    endereco.options.length = 0;
                    endereco.options[0] = new Option('', '');
                    subtipos = JSON.parse(xmlhttpSn.responseText);
                    for (i = 0; i < subtipos.length; i++) {
                        endereco.options[i + 1] = new Option(subtipos[i], subtipos[i]);
                    }
                    carrega_valores(7);
                    document.getElementById('carregando2').style.display = 'none';
                }
            }
        }

        xmlhttpSn.open("GET", "../controller/ajax_subtipo_nome.php?q=logradouro&tipo_nome=" + tipo_nome.value + "&subtipo_nome=" + subtipo_nome.value + "&cidade=" + cidade.value + "&bairro=" + bairro.value + "&localizacao=" + localizacao.value + "&condominio=" + condominio.value + "&edificio=" + edificio.value, true);
        xmlhttpSn.send();
        //
    }

}

function limpa_pesquisa() {

    document.getElementById('tipo_nome').value = '';
    document.getElementById('subtipo_nome').value = '';
    document.getElementById('cidade').value = '';
    document.getElementById('bairro').value = '';
    document.getElementById('localizacao').value = '';
    document.getElementById('condominio').value = '';
    document.getElementById('edificio').value = '';
    document.getElementById('endereco').value = '';
    document.getElementById('area_terreno_de').value = '0';
    document.getElementById('area_terreno_ate').value = '0';
    //document.getElementById('quadra_de').value = '0';
    //document.getElementById('quadra_ate').value = '0';
    document.getElementById('quadra').value = '';
    document.getElementById('valor_venda_de').value = '0';
    document.getElementById('valor_venda_ate').value = '0';
    document.getElementById('area_construida_de').value = '0';
    document.getElementById('area_construida_ate').value = '0';
    //document.getElementById('lote_de').value = '0';
    //document.getElementById('lote_ate').value = '0';
    document.getElementById('lote').value = '';
    document.getElementById('valor_locacao_de').value = '0';
    document.getElementById('valor_locacao_ate').value = '0';
    document.getElementById('valor_condominio_de').value = '0';
    document.getElementById('valor_condominio_ate').value = '0';
    document.getElementById('metragem_de').value = '0';
    document.getElementById('metragem_ate').value = '0';
    document.getElementById('dormitorio_de').value = '0';
    document.getElementById('dormitorio_ate').value = '0';
    document.getElementById('obra').value = '';
    document.getElementById('zoneamento').value = '';
    document.getElementById('suite_de').value = '0';
    document.getElementById('suite_ate').value = '0';
    document.getElementById('permuta').value = '';
    document.getElementById('topografia').value = '';
    document.getElementById('banheiro_de').value = '0';
    document.getElementById('banheiro_ate').value = '0';
    document.getElementById('situacao').value = 'Ativo';
    document.getElementById('garagem_de').value = '0';
    document.getElementById('garagem_ate').value = '0';
    document.getElementById('proprietario').value = '0';
    
    document.getElementById('aceita_financiamento').value = 'Não';
    document.getElementById('na_internet').value = 'Não';
    document.getElementById('nos_portais').value = 'Não';
    document.getElementById('atualizado_recente').value = 'Não';
    document.getElementById('captado_recente').value = 'Não';
    document.getElementById('todos_venda').value = 'Não';
    document.getElementById('todos_locacao').value = 'Não';

    form1.submit();
}


function enviar_email(ref) {

    document.getElementById('ajax-loader').style.display = 'block';

    var de = document.getElementById('email_de').value;
    var para = document.getElementById('email_para').value;
    var nome = document.getElementById('email_nome').value;
    var mensagem = document.getElementById('email_mensagem').value;

    if (para == '' || nome == '') {
        alert('Preencha Nome e E-mail para enviar.');
        document.getElementById('ajax-loader').style.display = 'none';
    } else {
        email_ses(de, para, nome, ref, mensagem);
    }
}

function email_ses(de, para, nome, ref, mensagem) {

    var alturaTela = $(document).height();
    var larguraTela = $(window).width();
    $('#fundo-negro').fadeIn(1000);
    $('#fundo-negro').css({'width': larguraTela, 'height': alturaTela});

    xmlhttpE = new XMLHttpRequest();
    xmlhttpE.onreadystatechange = function ()
    {
        if (xmlhttpE.readyState == 4) {
            if (xmlhttpE.status == 200)
            {
                alert(xmlhttpE.responseText);
                $('#fundo-negro').fadeOut(1000);

                document.getElementById('ajax-loader').style.display = 'none';
                document.getElementById('envia-email').style.display = 'none';
            }
        }
    }
    xmlhttpE.open("GET", "../controller/email_imovel.php?de=" + de + "&para=" + para + "&nome=" + nome + "&ref=" + ref + "&mensagem=" + nl2br(mensagem, ''), true);
    xmlhttpE.send();
}

function calcula(operacao, valor1, valor2, resultado) {

    var v1 = document.getElementById(valor1).value;
    var v2 = document.getElementById(valor2).value;
    var res = document.getElementById(resultado);

    v1 = v1.replace('.', '');
    v1 = v1.replace('.', '');
    v1 = v1.replace('.', '');
    v1 = v1.replace('.', '');

    v1 = v1.replace(',', '.');

    v2 = v2.replace('.', '');
    v2 = v2.replace('.', '');
    v2 = v2.replace('.', '');
    v2 = v2.replace('.', '');

    v2 = v2.replace(',', '.');


    if (operacao == 'div') {
        op2 = '÷';
        if (v1 > 0) {
            res.value = formatNumber(v1 / v2);
        } else {
            res.value = formatNumber(0);
        }
        return true;

    }

    if (operacao == 'mult') {
        op2 = 'X';
        res.value = formatNumber(v1 * v2);
        return true;
    }

    if (operacao == 'som') {
        op2 = '+';
        res.value = formatNumber(v1 + v2);
        return true;
    }

    if (operacao == 'sub') {
        op2 = '-';
        res.value = formatNumber(v1 - v2);
        return true;
    }

    if (operacao == 'elev') {
        op2 = '√';
        res.value = formatNumber(v1 ^ v2);
        return true;
    }

    return false;
}


function formatNumber(number)
{
    number = number.toFixed(2) + '';
    x = number.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}