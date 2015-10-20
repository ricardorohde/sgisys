<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo '<script>window.open("login.php","_self");</script>';
}
include '../controller/site_config.php';
$site_config = json_decode(site_config_carregar('nome'));

if (isset($_GET['senha'])) {
    $senha = $_GET['senha'];
} else {
    $senha = '';
}

include '../controller/usuario.php';
include '../controller/usuario_acesso.php';
$usr = json_decode(usuario_carregar($_SESSION['usuario_id']));
$ace = json_decode(acesso_carregar($usr->acesso));


if ($senha == 'S') {
    $programa = 'usuario.php?id=' . $_SESSION['usuario_id'];
} else {
    $programa = 'home.php';
    $home = $usr->home;
    if (!empty($home)) {
        $programa = $home;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>≡ Sistema SGI Plus Online</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=1200, initial-scale=1" /> 
        <link href="css/fontes.css" rel="stylesheet" />
        <link href="css/base.css" rel="stylesheet" />
        <link href="css/admin.css" rel="stylesheet" />
        <script src="../controller/js/admin.js"></script>
        <script language="javascript" >
            window.onbeforeunload = function() {
                return ' Deseja realmente sair do sistema?. ';
            }
        </script>
        <style>
            .aviso {
                position: absolute;
                top: 100px;
                left: 200px;
                background: #fff;
/*                #border: 2px solid red;*/
                border: 2px solid #06c;
                border-radius: 10px;
                z-index: 1010;
                padding: 20px;
                box-shadow: 0 0 5px #000;
                width: 400px;
            }
        </style>
    </head>
    <body onload="pesquisa_mensagem();">
        <div class="topo">
            <a href="admin.php"><img src="img/logo_sgi.png" width="160"></a>
            <div class="site-nome">
                <?php echo $_SESSION['cliente_id'] . '-' . $site_config->nome . '  &nbsp;('.$site_config->email_envio.') <br><a href="' . $site_config->url . '" target="_blank">' . $site_config->url . '</a>'; ?>
                <br>
                <div id="ocorrencia-status">⇢
                </div>
            </div>
            <div id="mensagens-status">carregando...</div>
            <div class="usuario">
                <div class="usuario-foto"><a href="usuario.php?id=<?php echo $_SESSION['usuario_id']; ?>" target="frmPrincipal"><img src="../site/fotos/<?php echo $_SESSION['cliente_id'] . '/' . $_SESSION['usuario_foto']; ?>" width="30"></a></div>
                <div class="usuario-dados">
                    <span title="ID: <?php echo $_SESSION['usuario_id'] . ' - Acesso : ' . $ace->acesso; ?>">Usuário : <?php echo $_SESSION['usuario_nome']; ?></span>
                    <br>[ <a href="usuario.php?id=<?php echo $_SESSION['usuario_id']; ?>" target="frmPrincipal">Meus Dados</a> ]
                    [ <a href="../controller/logout.php">Sair</a> ]
                </div>
            </div>
        </div>
        <iframe name="frmPrincipal" width="100%" height="1000" frameborder="no" src="<?php echo $programa; ?>" style="position: absolute; top: 80px;"></iframe>
        <!--<div class="aviso" id="aviso"><b><font size="4"><img src="img/Button Close-01.png" width="30" alt="Voltar" title="Voltar" style="float: right;margin-top: -30px;margin-right: 00px;cursor: pointer;" onclick="document.getElementById('aviso').style.display='none';">Comunicado IMPORTANTE:<br> No próximo dia 08 (Sábado) O sistema estará em manutenção das 00:00h as 18:00hs. Nesse período não será possível utilizar. Os sites funcionarão normalmente.</font></b></div> -->
        <!--<div class="aviso" id="aviso"><b><font size="2"><img src="img/Button Close-01.png" width="30" alt="Voltar" title="Voltar" style="float: right;margin-top: -30px;margin-right: 00px;cursor: pointer;" onclick="document.getElementById('aviso').style.display='none';">COMUNICADO IMPORTANTE:<br>
PREZADO CLIENTE,<br>
PARA MELHORIA DE NOSSOS SERVIÇOS, ESTAMOS EM UM PERÍODO DE MANUTENÇÃO E MELHORIAS NO SISTEMA SGI PLUS,<br>

PEDIMOS QUE NÃO EFETUEM NOVOS CADASTROS, ALTERAÇÕES E EXCLUSÕES NO SISTEMA ATÉ SEGUNDA-FEIRA ( 17/11/2014 )<br>

DESCULPE O TRANSTORNO,<br>

TODA A EQUIPE DA SGI FÁCIL AGRADECE A COMPREENSÃO.
</font></b></div>-->
    </body>
    <script>window.setInterval('pesquisa_mensagem()', 90000);</script>
    <script>window.setInterval('pesquisa_ocorrencia()', 120000);</script>
    <?php
    include '../controller/mensagem.php';

    $ret = json_decode(mensagem_listar('Caixa de entrada', $_SESSION['usuario_id']));
    $tot = 0;
    foreach ($ret as $id) {
        $mensagem = json_decode(mensagem_carregar($id));
        if ($mensagem->situacao == 'Pendente') {
            $tot++;
        }
    }
    if ($tot == 1) {
        echo "<script>if (confirm('Você tem $tot mensagem não lida. Gostaria de ver sua Caixa de Entrada?')) { window.open('mensagem.php?pasta=Caixa%20de%20entrada','frmPrincipal'); };</script>";
    } elseif ($tot > 1) {
        echo "<script>if (confirm('Você tem $tot mensagens não lidas. Gostaria de ver sua Caixa de Entrada?')) { window.open('mensagem.php?pasta=Caixa%20de%20entrada','frmPrincipal'); };</script>";
    }

    include '../controller/agenda.php';

    $ret = json_decode(agenda_listar(date('Ymd'), $_SESSION['usuario_id']));
    $tot = 0;
    $compro = '';
    foreach ($ret as $id) {
        $agenda = json_decode(agenda_carregar($id));
        if (!empty($agenda->compromisso) && $agenda->hora >= date('Hi')) {
            $tot++;
            if ($tot == 1) {
                $compro = 'Compromisso as ' . substr($agenda->hora, 0, 2) . ':' . substr($agenda->hora, 2) . '-' . $agenda->compromisso;
            }
        }
    }

    if ($tot == 1) {
        echo '<script>alert("' . $compro . '");</script>';
    }if ($tot > 1) {
        echo "<script>alert('Você tem $tot compromissos hoje');</script>";
    }

    $pasta = '../site/fotos/' . $_SESSION['cliente_id'];
    if (!file_exists($pasta)) {
        if (mkdir($pasta)) {
            echo '<script>alert("Pasta de Fotos criada.");</script>';
            copy('../site/fotos/sem_foto.jpg', $pasta . '/sem_foto.jpg');
            copy('../site/fotos/usuario_sem_foto.jpg', $pasta . '/usuario_sem_foto.jpg');
        } else {
            echo '<script>alert("Pasta de Fotos não pôde ser criada.");</script>';
        }
    }
    ?>
</html>


