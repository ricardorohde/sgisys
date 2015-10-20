function grava_modelo(modelo) {

    xmlhttp1 = new XMLHttpRequest();
    xmlhttp1.onreadystatechange = function()
    {
        if (xmlhttp1.readyState == 4) {
            if (xmlhttp1.status == 200)
            {
                document.getElementById(modelo).checked='checked';
            }
        }
    }
    xmlhttp1.open("GET", "../controller/ajax_modelo.php?modelo=" + modelo, true);
    xmlhttp1.send();
}

