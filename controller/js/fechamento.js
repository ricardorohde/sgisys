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

function busca_proposta() {

    var id = document.getElementById('proposta').value;

    if (id == '') {
        alert('Digite o número da proposta');
        return false;
    }

    xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function()
    {
        if (xmlhttp2.readyState == 4) {
            if (xmlhttp2.status == 200)
            {
                ret = xmlhttp2.responseText;
                if (ret != 'false') {
                    tmp = JSON.parse(ret);
                    if (tmp['situacao'] != 'Pendente') {
                        alert('Proposta encontrada, mas não disponível.');
                    } else {

                        document.getElementById('proposta').readOnly = true;
                        document.getElementById('proposta').disabled = true;
                        document.getElementById('data_proposta').readOnly = true;
                        document.getElementById('validade_proposta').readOnly = true;
                        document.getElementById('valor_proposta').readOnly = true;
                        document.getElementById('data_proposta').disabled = true;
                        document.getElementById('validade_proposta').disabled = true;
                        document.getElementById('valor_proposta').disabled = true;

                        document.getElementById('btnPesquisar').style.display = 'none';
                        document.getElementById('proposta-dados1').style.display = 'block';

                        document.getElementById('data_proposta').value = tmp['data'];
                        document.getElementById('validade_proposta').value = tmp['validade'];
                        document.getElementById('valor_proposta').value = tmp['valor'];
                    }

                } else {
                    alert('Número da Proposta não localizado.');
                }

            }
        }
    }
    xmlhttp2.open("GET", "../controller/ajax_proposta.php?id=" + id, true);
    xmlhttp2.send();


}

function nova_pesquisa() {
    document.getElementById('proposta').readOnly = false;
    document.getElementById('proposta').disabled = false;
    document.getElementById('data_proposta').readOnly = false;
    document.getElementById('validade_proposta').readOnly = false;
    document.getElementById('valor_proposta').readOnly = false;
    document.getElementById('data_proposta').disabled = false;
    document.getElementById('validade_proposta').disabled = false;
    document.getElementById('valor_proposta').disabled = false;

    document.getElementById('btnPesquisar').style.display = 'block';
    document.getElementById('proposta-dados1').style.display = 'none';

    document.getElementById('btnNovaPesquisa').style.display = 'none';
    document.getElementById('btnSelecionar').style.display = 'none';
    document.getElementById('proposta-dados2').style.display = 'none';
}

function seleciona_proposta() {
    document.getElementById('btnNovaPesquisa').style.display = 'none';
    document.getElementById('btnSelecionar').style.display = 'none';
    document.getElementById('proposta-dados2').style.display = 'block';
}