function pesquisa_mensagem() {
    
    window.document.title = '⇆Sistema SGI Plus Online';
    
    xmlhttpMsg = new XMLHttpRequest();
    xmlhttpMsg.onreadystatechange = function()
    {
        if (xmlhttpMsg.readyState == 4) {
            if (xmlhttpMsg.status == 200)
            {
                tot = 0;
                document.getElementById('mensagens-status').innerHTML = xmlhttpMsg.responseText;
                notifica();
            }
            else
            {
                document.getElementById('mensagens-status').innerHTML = '↛ aguardando servidor...';
                window.document.title = '↛Sistema SGI Plus Online';
            }
        }
    }
    xmlhttpMsg.open("GET", "../controller/ajax_mensagem.php", true);
    xmlhttpMsg.send();
}

function pesquisa_ocorrencia() {

    document.getElementById('ocorrencia-status').innerHTML = '⇆';

    xmlhttpOcorr = new XMLHttpRequest();
    xmlhttpOcorr.onreadystatechange = function()
    {
        if (xmlhttpOcorr.readyState == 4) {
            if (xmlhttpOcorr.status == 200)
            {
                tmp = xmlhttpOcorr.responseText;
                if (tmp.substr(0, 5) == 'ALARM') {
                    alarme = tmp.substr(5, 100);
                    notifyMe(alarme);
                    alert(alarme);
                } else {
                    document.getElementById('ocorrencia-status').innerHTML = tmp;
                }
            }
            else
            {
                document.getElementById('ocorrencia-status').innerHTML = '⇆';
            }
        }
    }
    xmlhttpOcorr.open("GET", "../controller/ajax_ocorrencia.php", true);
    xmlhttpOcorr.send();
}

function notifica() {
    xmlhttpNtf = new XMLHttpRequest();
    xmlhttpNtf.onreadystatechange = function()
    {
        if (xmlhttpNtf.readyState == 4) {
            if (xmlhttpNtf.status == 200)
            {
                if (xmlhttpNtf.responseText != '') {
                    tot = xmlhttpNtf.responseText;
                    notifyMe(tot);
                    window.document.title = tot;
                } else {
                    window.document.title = '≡ Sistema SGI Plus Online';
                }
            }
        }
    }
    xmlhttpNtf.open("GET", "../controller/ajax_mensagem_notifica.php", true);
    xmlhttpNtf.send();
}

function notifyMe(msg) {
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        alert(msg);
    }

    // Let's check if the user is okay to get some notification
    else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification(msg);
    }

    // Otherwise, we need to ask the user for permission
    // Note, Chrome does not implement the permission static property
    // So we have to check for NOT 'denied' instead of 'default'
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function(permission) {

            // Whatever the user answers, we make sure we store the information
            if (!('permission' in Notification)) {
                Notification.permission = permission;
            }

            // If the user is okay, let's create a notification
            if (permission === "granted") {
                var notification = new Notification(msg);
            }
        });
    }

    // At last, if the user already denied any notification, and you 
    // want to be respectful there is no need to bother him any more.
}