<?php
session_start();
$ref = $_GET['id'];
include 'func.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SGI Fácil : : Painél Administrativo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
        <link href="css/forms.css" rel="stylesheet" />
        <link href="css/cadastro.css" rel="stylesheet" />
        <script src="../controller/js/ficha.js"></script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <div style="width: 100%;height: 50px;float: left;"></div>
        <form action="../controller/imovel_atualizacoes_grava.php" method="POST" name="form1" id="form1">
            <input type="hidden" name="ref" id="ref" value="<?php echo $ref; ?>">            
            <input type="button"  value="Voltar" class="botao" onclick="window.open('imovel_atualizacoes.php?id=<?php echo $ref; ?>', '_self');">
            <input type="submit" value="Gravar" class="botao">
            <br>
            <br>
            <p>Solicitação de Atualizações da Ficha do Imóvel</p>
            <?php
            include '../controller/ficha.php';
            $aux = json_decode(ficha_listar($ref));
            $fich = json_decode(ficha_carregar($aux->id));
            if ($fich) {
                $data = data_decode($fich->data);
                $solicitante = $fich->solicitante;
                $descricao = $fich->descricao;
                $situacao = $fich->situacao;
            } else {
                $data = date('d/m/Y');
                $solicitante = $_SESSION['usuario_nome'];
                $descricao = '';
                $situacao = 'Pendente';
            }
            echo '<div class="div-esq">Data';
            echo '<br><input type="text" name="data" size="10" value="' . $data . '" readonly>';
            echo '</div>';
            echo '<div class="div-esq">Solicitante';
            echo '<br><input type="text" name="solicitante" size="20" value="' . $solicitante . '" readonly>';
            echo '</div>';
            echo '<div class="div-esq">Descrição';
            echo '<br><textarea name="descricao" rows="5" cols="40">' . $descricao . '</textarea>';
            echo '</div>';
            echo '<input type="hidden" name="situacao" id="situacao" value="' . $situacao . '">';
            ?>
            <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        </form>
    </body>
</html>
