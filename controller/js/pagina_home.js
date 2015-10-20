function atualiza_ftp() {
    xmlhttp1 = new XMLHttpRequest();
    xmlhttp1.onreadystatechange = function()
    {
        if (xmlhttp1.readyState == 4) {
            if (xmlhttp1.status == 200)
            {
                alert(xmlhttp1.responseText);
            }
        }
    }
    xmlhttp1.open("GET", "../controller/atualiza_ftp.php", true);
    xmlhttp1.send();
}


