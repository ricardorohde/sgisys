function baixa_ficha(ref, id) {

    xmlhttpFch = new XMLHttpRequest();
    xmlhttpFch.onreadystatechange = function()
    {
        if (xmlhttpFch.readyState == 4) {
            if (xmlhttpFch.status == 200)
            {
                var tmp = xmlhttpFch.responseText;

                if (tmp == 'OK') {
                    window.open('imovel_atualizacoes.php?id=' + ref, '_self');
                } else {
                    alert(tmp);
                }

            }
        }
    }
    xmlhttpFch.open("GET", "../controller/ajax_baixa_ficha.php?id=" + id, true);
    xmlhttpFch.send();
}

function grava_ficha() {

    var logo = document.getElementById('logo').value;
    var texto1 = document.getElementById('texto1').value;
    var texto2 = document.getElementById('texto2').value;

    xmlhttpFch = new XMLHttpRequest();
    xmlhttpFch.onreadystatechange = function()
    {
        if (xmlhttpFch.readyState == 4) {
            if (xmlhttpFch.status == 200)
            {
                var tmp = xmlhttpFch.responseText;

                if (tmp == 'OK') {
                    window.open('ficha_config.php', '_self');
                } else {
                    alert(tmp);
                }

            }
        }
    }
    xmlhttpFch.open("GET", "../controller/ajax_grava_ficha.php?logo=" + logo + "&texto1=" + texto1 + "&texto2=" + texto2, true);
    xmlhttpFch.send();
}