<?php

if ($m == 'Home') {
    $mn1 = 'menu-botao menu-botao-sel';
} else {
    $mn1 = 'menu-botao';
}
if ($m == 'Cadastros') {
    $mn2 = 'menu-botao menu-botao-sel';
} else {
    $mn2 = 'menu-botao';
}
if ($m == 'Financeiro') {
    $mn3 = 'menu-botao menu-botao-sel';
} else {
    $mn3 = 'menu-botao';
}
if ($m == 'Config') {
    $mn4 = 'menu-botao menu-botao-sel';
} else {
    $mn4 = 'menu-botao';
}
if ($m == 'Portais') {
    $mn5 = 'menu-botao menu-botao-sel';
} else {
    $mn5 = 'menu-botao';
}
if ($m == 'Tabelas') {
    $mn6 = 'menu-botao menu-botao-sel';
} else {
    $mn6 = 'menu-botao';
}
include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
$programa = 'home.php';

include '../controller/usuario.php';
$usr = json_decode(usuario_carregar($_SESSION['usuario_id']));
$home = $usr->home;
if (!empty($home)) {
    $programa = $home;
}

echo '<div class="menu">';
echo '    <div class="' . $mn1 . '" onclick="window.open(\'' . $programa . '\', \'_self\')">Home</div>';
if ($usu->cadastros == 'Sim') {
    echo '    <div class="' . $mn2 . '" onclick="window.open(\'cadastros.php?id_cadastro=0&tabelas=Não\', \'_self\')">Cadastros</div>';
}
if ($usu->financeiro == 'Sim') {
    echo '    <div class="' . $mn3 . '" onclick="window.open(\'financeiro.php\', \'_self\')">Financeiro</div>';
}
if ($usu->site == 'Sim') {
    echo '    <div class="' . $mn4 . '" onclick="window.open(\'config.php\', \'_self\')">Configurações Site</div>';
}
if ($usu->portal == 'Sim') {
    echo '    <div class="' . $mn5 . '" onclick="window.open(\'portais.php\', \'_self\')">Portais</div>';
}
if ($usu->tabelas == 'Sim') {
    echo '    <div class="' . $mn6 . '" onclick="window.open(\'tabelas.php\', \'_self\')">Tabelas</div>';
}
echo '</div>';
if ($m == 'Home') {
    echo '<div class="submenu">';
    echo '    <div class="submenu-botao" onclick="window.open(\'home.php\', \'_self\')">Minha Página</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'mensagem.php?pasta=Caixa de entrada\', \'_self\')">Mensagens</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'agenda.php\', \'_self\')">Agenda</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'ligacoes.php\', \'_self\')">Ligações</div>';
    echo '</div>';
} elseif ($m == 'Cadastros' && $usu->cadastros == 'Sim') {
    echo '<div class="submenu">';
    include '../controller/tipo_cadastro.php';
    $ret = json_decode(tipo_cadastro_listar());
    $tot = count($ret);
    foreach ($ret as $id) {
        $tcad = json_decode(tipo_cadastro_carregar($id));
        $tmp = $tcad->tabela . '_consultar';
        if ($usu->$tmp == 'Sim' && $tcad->tabelas != 'S') {
            echo '    <div class="submenu-botao" onclick="window.open(\'cadastros.php?id_cadastro=' . $id . '&tabelas=Não\', \'_self\')">' . $tcad->tipo . '</div>';
        }
    }
    echo '</div>';
} elseif ($m == 'Financeiro' && $usu->financeiro == 'Sim') {
    echo '<div class="submenu">';
    echo '    <div class="submenu-botao" onclick="window.open(\'movimentos.php\', \'_self\')">Movimento</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'fechamentos.php\', \'_self\')">Fechamento de Vendas</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'conta-corrente.php\', \'_self\')">Conta-Corrente Corretor</div>';
    echo '</div>';
} elseif ($m == 'Config' && $usu->site == 'Sim') {
    echo '<div class="submenu">';
    if ($usu->site_modelo == 'Sim') {
        echo '    <div class="submenu-botao" onclick="window.open(\'modelo.php\', \'_self\')">Modelo de Site</div>';
    }
    echo '    <div class="submenu-botao" onclick="window.open(\'pagina_home.php\', \'_self\')">Página Home</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'pagina_lista.php\', \'_self\')">Listagem de Imóveis</div>';
    echo '    <div class="submenu-botao" onclick="window.open(\'pagina_detalhe.php\', \'_self\')">Detalhe Imóvel</div>';
    if ($usu->site_conteudo == 'Sim') {
        echo '    <div class="submenu-botao" onclick="window.open(\'paginas.php\', \'_self\')">Páginas de Conteúdo</div>';
    }
    echo '    <div class="submenu-botao" onclick="window.open(\'imagens.php\', \'_self\')">Banco de Imagens</div>';
    echo '</div>';
} elseif ($m == 'Portais' && $usu->portal == 'Sim') {
    echo '<div class="submenu">';
    include '../controller/portal.php';
    $ret = json_decode(portal_listar());
    $tot = count($ret);
    foreach ($ret as $id) {
        $port = json_decode(portal_carregar($id));
        echo '    <div class="submenu-botao" style="width: 100px;" onclick="window.open(\'portal.php?nome=' . $id . '\', \'_self\')">' . $port->nome_completo . '</div>';
    }
    //
    echo '</div>';
} elseif ($m == 'Tabelas' && $usu->tabelas == 'Sim') {
    echo '<div class="submenu">';
    //
    if ($usu->usuario_consultar == 'Sim') {
        echo '    <div class="submenu-botao" onclick="window.open(\'log.php\', \'_self\')">Log Sistema</div>';
    }
    include '../controller/tipo_cadastro.php';
    $ret = json_decode(tipo_cadastro_listar());
    $tot = count($ret);
    foreach ($ret as $id) {
        $tcad = json_decode(tipo_cadastro_carregar($id));
        $tmp = $tcad->tabela . '_consultar';
        if ($usu->$tmp == 'Sim' && $tcad->tabelas == 'S') {
            echo '    <div class="submenu-botao" onclick="window.open(\'cadastros.php?id_cadastro=' . $id . '&tabelas=Sim\', \'_self\')">' . $tcad->tipo . '</div>';
        }
    }
    if ($usu->usuario_consultar == 'Sim') {
        echo '    <div class="submenu-botao" onclick="window.open(\'usuarios.php\', \'_self\')">Usuários</div>';
    }
    if ($usu->usuario_consultar == 'Sim') {
        echo '    <div class="submenu-botao" onclick="window.open(\'ficha_config.php\', \'_self\')">Config Ficha</div>';
    }
    echo '</div>';
}
?>
