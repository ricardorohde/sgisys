function grava_td(id, valor) {

    xmlhttpTd = new XMLHttpRequest();
    xmlhttpTd.onreadystatechange = function()
    {
        if (xmlhttpTd.readyState == 4) {
            if (xmlhttpTd.status == 200) {

            }
        }
    }
    xmlhttpTd.open("GET", "../controller/ajax_td.php?id=" + id + "&valor=" + valor, true);
    xmlhttpTd.send();

}