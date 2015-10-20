
function grava_compromisso(usuario, data, hora, compromisso) {
    document.getElementById('carregando').style.display = 'block';
    xmlhttpAg = new XMLHttpRequest();
    xmlhttpAg.onreadystatechange = function()
    {
        if (xmlhttpAg.readyState == 4) {
            if (xmlhttpAg.status == 200)
            {
                document.getElementById('carregando').style.display = 'none';
                window.open('agenda.php?dia=' + data.substr(6, 2) + '&mes=' + data.substr(4, 2) + '&ano=' + data.substr(0, 4), 'frmPrincipal');
            }
        }
    }
    xmlhttpAg.open("GET", "../controller/ajax_agenda_compromisso.php?usuario=" + usuario + "&data=" + data + "&hora=" + hora + "&compromisso=" + compromisso, true);
    xmlhttpAg.send();
}
