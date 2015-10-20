function grava_config() {

    var codigo_cliente = document.getElementById('codigo_cliente').value;
    var usuario = document.getElementById('usuario').value;
    var senha = document.getElementById('senha').value;
    var url = document.getElementById('url').value;
    var usuario_ftp = document.getElementById('usuario_ftp').value;
    var senha_ftp = document.getElementById('senha_ftp').value;
    var endereco_ftp = document.getElementById('endereco_ftp').value;
    var enviar_endereco = document.getElementById('enviar_endereco').value;

    xmlhttpCfg = new XMLHttpRequest();
    xmlhttpCfg.onreadystatechange = function()
    {
        if (xmlhttpCfg.readyState == 4) {
            if (xmlhttpCfg.status == 200) {

            }
        }
    }
    xmlhttpCfg.open("POST", "../controller/ajax_creci.php", true);
    xmlhttpCfg.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttpCfg.send("codigo_cliente=" + codigo_cliente + "&usuario=" + usuario + "&senha=" + senha + "&url=" + url + "&usuario_ftp=" + usuario_ftp + "&senha_ftp=" + senha_ftp + "&endereco_ftp=" + endereco_ftp + "&enviar_endereco=" + enviar_endereco);

}


