function imovel_mostrar(id) {

    document.getElementById('pesquisa').style.display = 'none';
    document.getElementById('mostrar').style.display = 'block';


    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200)
            {
                imovel = JSON.parse(xmlhttp.responseText);
                document.getElementById('cep').value = imovel.cep;
                document.getElementById('endereco').value = imovel.tipo_logradouro + ' ' + imovel.logradouro + ' ' + imovel.numero;
                document.getElementById('bairro').value = imovel.bairro;
                document.getElementById('cidade').value = imovel.cidade;
                document.getElementById('uf').value = imovel.uf;
            }
        }
    }
    xmlhttp.open("GET", "../controller/ajax_imovel.php?id=" + id, true);
    xmlhttp.send();
}

function imovel_pesquisar() {

    document.getElementById('pesquisa').style.display = 'block';
    document.getElementById('mostrar').style.display = 'none';

}

function imovel_buscar() {

    document.getElementById('carregando').style.display = 'block';

    var localizacao = document.getElementById('localizacao').value;
    var cidade = document.getElementById('cidade').value;
    var bairro = document.getElementById('bairro').value;
    var logradouro = document.getElementById('logradouro').value;
    var id = document.getElementById('id').value;
    var tipo = document.getElementById('tipo').value;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200)
            {
                document.getElementById('carregando').style.display = 'none';
                window.open('imoveis.php', '_self');
            }
        }
    }
    xmlhttp.open("GET", "../controller/ajax_imovel_pesquisa.php?localizacao=" + localizacao + "&cidade=" + cidade + "&bairro=" + bairro + "&logradouro=" + logradouro + "&id=" + id + "&tipo=" + tipo, true);
    xmlhttp.send();
}
