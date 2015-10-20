$(function() {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'
        ],
        dayNamesMin: [
            'D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'
        ],
        dayNamesShort: [
            'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'
        ],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
            'Outubro', 'Novembro', 'Dezembro'
        ],
        monthNamesShort: [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set',
            'Out', 'Nov', 'Dez'
        ],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });
});


function carrega_nome(tipo) {
    var nome = document.getElementById('nome');
    nome.options.length = 0;
    nome.options[0] = new Option('', 'carregando...');

    xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function()
    {
        if (xmlhttp2.readyState == 4) {
            if (xmlhttp2.status == 200)
            {
                nome.options.length = 0;
                nomes = JSON.parse(xmlhttp2.responseText);
                for (i = 0; i < nomes.length; i++) {
                    nome.options[i] = new Option(nomes[i], nomes[i]);
                }
            }
        }
    }
    xmlhttp2.open("GET", "../controller/ajax_movimento.php?tipo=" + tipo, true);
    xmlhttp2.send();
}