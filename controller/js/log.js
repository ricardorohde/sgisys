function log_mostrar(id) {

    document.getElementById('pesquisa').style.display = 'none';
    document.getElementById('mostrar').style.display = 'block';


    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200)
            {
                log = JSON.parse(xmlhttp.responseText);
                document.getElementById('mostrar').innerHTML = '<pre><code> ' + log.data_hora + ' ' + log.usuario + ' ' + log.titulo + ' <br><hr><table width="80%" align="center"><tr><td nowrap>' + log.descricao + '</td></tr></table></code></pre>';
            }
        }
    }
    xmlhttp.open("GET", "../controller/ajax_log.php?id=" + id, true);
    xmlhttp.send();
}

function log_pesquisar() {

    document.getElementById('pesquisa').style.display = 'block';
    document.getElementById('mostrar').style.display = 'none';

}
