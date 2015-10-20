function pesquisa_cep() {
    var cep = document.getElementById('cep').value;

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200)
            {
                var tmp = xmlhttp.responseText.split('|');
                if (tmp[1].length > 2) {
                    document.getElementById('tipo_logradouro').value = tmp[0];
                    document.getElementById('logradouro').value = tmp[1];
                    document.getElementById('bairro').value = tmp[2];
                    document.getElementById('cidade').value = tmp[3];
                    document.getElementById('uf').value = tmp[4];
                }
            }
        }
    }
    xmlhttp.open("GET", "../controller/ajax_cep.php?cep=" + cep, true);
    xmlhttp.send();
}