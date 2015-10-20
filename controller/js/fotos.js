function mostra_foto(foto) {
    document.getElementById('foto-grande').innerHTML = '<img src="' + foto + '" width="500"><div class="foto-fecha" onclick="fecha_foto();">[ X Fechar]</div>';
    $("#foto-grande").fadeIn(500);

}

function fecha_foto() {
    $("#foto-grande").fadeOut(500);
}

function grava_fachada(id) {

    xmlhttpGF = new XMLHttpRequest();
    xmlhttpGF.onreadystatechange = function()
    {
        if (xmlhttpGF.readyState == 4) {
            if (xmlhttpGF.status == 200)
            {
                alert(xmlhttpGF.responseText);
            }
        }
    }
    xmlhttpGF.open("GET", "../controller/ajax_grava_fachada.php?id=" + id, true);
    xmlhttpGF.send();
}
