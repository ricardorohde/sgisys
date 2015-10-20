<?php
session_start();
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
        <link href="css/config.css" rel="stylesheet" />
        <script type="text/javascript" src="../controller/js/jscolor/jscolor.js"></script>
        <script type="text/javascript" src="../controller/js/pagina_home.js"></script>
    </head>
    <body>
        <?php
        $m = 'Config';
        include 'menu.php';
        include '../controller/site_config.php';
        $site_config = json_decode(site_config_carregar());
        ?>
        <div id="conteudo">
            <form action="../controller/pagina_home_grava.php" method="POST" name="form1" id="form1">
                <h3>Parâmetros da Home do Seu Site</h3>
                <div class="botoes-form">
                    <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                    <?php
                    if (!empty($site_config->servidor_ftp) && !empty($site_config->usuario_ftp) && !empty($site_config->senha_ftp)) {
                        echo '<input type="button" value="Atualizar FTP" class="botao" onclick="atualiza_ftp();">';
                    }
                    echo '</div>';
                    ?>
                    <div class="form-detalhe" id="form-detalhe"> 
                        <fieldset>
                            <legend>Transferência FTP</legend>
                            <div class="form-varchar">Servidor
                                <br><input type="text" name="servidor_ftp" id="servidor_ftp" size="30"  value="<?php echo $site_config->servidor_ftp; ?>">
                            </div>
                            <div class="form-varchar">Pasta Remota
                                <br><input type="text" name="pasta_ftp" id="pasta_ftp" size="30"  value="<?php echo $site_config->pasta_ftp; ?>">
                            </div>
                            <div class="form-varchar">Usuario
                                <br><input type="text" name="usuario_ftp" id="usuario_ftp" size="20" value="<?php echo $site_config->usuario_ftp; ?>">
                            </div>
                            <div class="form-varchar">Senha
                                <br><input type="password" name="senha_ftp" id="senha_ftp" size="20"  value="<?php echo $site_config->senha_ftp; ?>">
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Dados Principais (head)</legend>
                            <div class="form-varchar">Nome da Empresa
                                <br><input type="text" name="nome" id="nome" size="100" maxlength="100" value="<?php echo $site_config->nome; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Título da Página
                                <br><input type="text" name="titulo_pagina" id="titulo_pagina" size="100" maxlength="100" value="<?php echo $site_config->titulo_pagina; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Endereço URL do Site (Usar http://www.meusite.com.br)
                                <br><input type="text" name="url" id="url" size="100" maxlength="100" value="<?php echo $site_config->url; ?>" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">E-mail padrão envio
                                <br><input type="text" name="email_envio" id="email_envio" size="100" maxlength="100" value="<?php echo $site_config->email_envio; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Meta Tag "description"
                                <br><input type="text" name="tag_description" id="tag_description" size="100" maxlength="100" value="<?php echo $site_config->tag_description; ?>" title="Uma ou duas frases que o buscador apresentará como um resumo do conteúdo do seu site. Procure manter um limite de aproximadamente 90 caracteres.">
                            </div>
                            <div class="form-varchar">Meta Tag "keywords"
                                <br><input type="text" name="tag_keywords" id="tag_keywords" size="100" maxlength="100" value="<?php echo $site_config->tag_keywords; ?>" title="Nesta tag você deve incluir uma quantidade de palavras que se referem ao conteúdo da página. Mantenha o limite de aproximadamente 100 caracteres. Se não utilizar as mesmas palavras, tente utilizar sinônimos. Nunca quebre uma linha de palavras-chave, porque seu trecho de código será considerado um erro e será ignorado. Sempre separe as palavras com vírgula e declare todas elas em letras minúsculas - alguns buscadores têm problemas com letras maiúsculas e podem ignorar seu site.">
                            </div>
                            <div class="form-varchar">Imagem Logo/Banner Topo
                                <br><select name="imagem_logo" id="imagem_logo">
                                    <option></option>
                                    <?php
                                    include '../controller/site_imagem.php';
                                    $imgs = json_decode(site_imagem_listar());
                                    foreach ($imgs as $img) {
                                        $sel = '';
                                        $imagem = json_decode(site_imagem_carregar($img));
                                        if ($site_config->imagem_logo == $imagem->img) {
                                            $logo = $imagem->img;
                                            $sel = 'selected';
                                        }
                                        if (!empty($imagem->img)) {
                                            echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <?php
                                echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                ?>
                            </div>
                            <div class="form-varchar">Largura Logo (px)
                                <br><input type="text" name="imagem_largura" id="imagem_largura" size="3" maxlength="3" value="<?php echo $site_config->imagem_largura; ?>">
                            </div>
                            <div class="form-varchar">Altura Logo (px)
                                <br><input type="text" name="imagem_altura" id="imagem_altura" size="3" maxlength="3" value="<?php echo $site_config->imagem_altura; ?>">
                            </div>
                            <div class="form-varchar">Margem Esquerda Logo (px)
                                <br><input type="text" name="imagem_mleft" id="imagem_mleft" size="3" maxlength="3" value="<?php echo $site_config->imagem_mleft; ?>">
                            </div>
                            <div class="form-varchar">Margem Topo Logo (px)
                                <br><input type="text" name="imagem_mtop" id="imagem_mtop" size="3" maxlength="3" value="<?php echo $site_config->imagem_mtop; ?>">
                            </div>
                            <div class="form-varchar">Background Página
                                <br><select name="background" id="imagem_logo">
                                    <option></option>
                                    <?php
                                    include '../controller/site_imagem.php';
                                    $imgs = json_decode(site_imagem_listar());
                                    foreach ($imgs as $img) {
                                        $sel = '';
                                        $imagem = json_decode(site_imagem_carregar($img));
                                        if ($site_config->background == $imagem->img) {
                                            $logo = $imagem->img;
                                            $sel = 'selected';
                                        }
                                        if (!empty($imagem->img)) {
                                            echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <?php
                                echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                ?>
                            </div>
                            <div class="form-varchar">Background Altura (px)
                                <br><input type="text" name="background_altura" id="background_altura" size="3" maxlength="3" value="<?php echo $site_config->background_altura; ?>" >
                            </div>
                            <div class="form-textarea">Scripts
                                <br><textarea name="script" id="script" rows="10" cols="100"><?php echo $site_config->script; ?></textarea>
                            </div>
                            <div class="site-config-logo"><img src="../site/fotos/<?php echo $_SESSION['cliente_id'] . '/' . $logo; ?>"></div>
                        </fieldset>
                        <fieldset>
                            <legend>Configurações da Página (body)</legend>
                            <div class="form-varchar">Cor Fundo
                                <br><input  class="color" type="text" name="pagina_background" id="pagina_background" size="6" maxlength="6" value="<?php echo $site_config->pagina_background; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Fonte Padrão
                                <br><select name="pagina_fonte_padrao" id="pagina_fonte_padrao">
                                    <?php
                                    $sel0 = $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = $sel8 = $sel9 = $sel10 = $sel11 = $sel12 = $sel13 = $sel14 = $sel15 = '';
                                    $sel = $site_config->pagina_fonte_padrao;
                                    if ($sel == '\'Arial,Verdana,Tahoma\', sans-serif;') {
                                        $sel0 = 'selected';
                                    }
                                    if ($sel == '\'Alegreya Sans SC\', sans-serif;') {
                                        $sel1 = 'selected';
                                    }
                                    if ($sel == '\'Gilda Display\', serif;') {
                                        $sel2 = 'selected';
                                    }
                                    if ($sel == '\'Oranienbaum\', serif;') {
                                        $sel3 = 'selected';
                                    }
                                    if ($sel == '\'Cantora One\', sans-serif;') {
                                        $sel4 = 'selected';
                                    }
                                    if ($sel == '\'Iceland\', cursive;') {
                                        $sel5 = 'selected';
                                    }
                                    if ($sel == '\'Montserrat\', sans-serif;') {
                                        $sel6 = 'selected';
                                    }
                                    if ($sel == '\'Allerta\', sans-serif;') {
                                        $sel7 = 'selected';
                                    }
                                    if ($sel == '\'Karla\', sans-serif;') {
                                        $sel8 = 'selected';
                                    }
                                    if ($sel == '\'Play\', sans-serif;') {
                                        $sel9 = 'selected';
                                    }
                                    if ($sel == '\'Vollkorn\', serif;') {
                                        $sel10 = 'selected';
                                    }
                                    if ($sel == '\'Coda\', cursive;') {
                                        $sel11 = 'selected';
                                    }
                                    if ($sel == '\'Nova Square\', cursive;') {
                                        $sel12 = 'selected';
                                    }
                                    if ($sel == '\'Roboto Condensed\', sans-serif;') {
                                        $sel13 = 'selected';
                                    }
                                    if ($sel == '\'Lato\', sans-serif;') {
                                        $sel14 = 'selected';
                                    }
                                    if ($sel == '\'Adamina\', serif;') {
                                        $sel15 = 'selected';
                                    }
                                    echo '<option value="\'Arial\', sans-serif;" ' . $sel0 . '>Arial,Verdana,Tahoma</option>';
                                    echo '<option value="\'Alegreya Sans SC\', sans-serif;" ' . $sel1 . '>Alegreya Sans SC</option>';
                                    echo '<option value="\'Gilda Display\', serif" ' . $sel2 . '>Gilda Display</option>';
                                    echo '<option value="\'Oranienbaum\', serif;" ' . $sel3 . '>Oranienbaum</option>';
                                    echo '<option value="\'Cantora One\', sans-serif;" ' . $sel4 . '>Cantora One</option>';
                                    echo '<option value="\'Iceland\', cursive;" ' . $sel5 . '>Iceland</option>';
                                    echo '<option value="\'Montserrat\', sans-serif;" ' . $sel6 . '>Montserrat</option>';
                                    echo '<option value="\'Allerta\', sans-serif;" ' . $sel7 . '>Allerta</option>';
                                    echo '<option value="\'Karla\', sans-serif;" ' . $sel8 . '>Karla</option>';
                                    echo '<option value="\'Play\', sans-serif;" ' . $sel9 . '>Play</option>';
                                    echo '<option value="\'Vollkorn\', serif;" ' . $sel10 . '>Vollkorn</option>';
                                    echo '<option value="\'Coda\', cursive;" ' . $sel11 . '>Coda</option>';
                                    echo '<option value="\'Nova Square\', cursive;" ' . $sel12 . '>Nova Square</option>';
                                    echo '<option value="\'Roboto Condensed\', sans-serif;" ' . $sel13 . '>Roboto Condensed</option>';
                                    echo '<option value="\'Lato\', sans-serif;" ' . $sel14 . '>Lato</option>';
                                    echo '<option value="\'Adamina\', serif;" ' . $sel15 . '>Adamina</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="form-varchar">Cor Fonte Padrão
                                <br><input  class="color" type="text" name="pagina_cor_padrao" id="pagina_cor_padrao" size="6" maxlength="6" value="<?php echo $site_config->pagina_cor_padrao; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Fonte Tamanho (pixels)
                                <br><select name="pagina_fonte_tamanho" id="pagina_fonte_tamanho">
                                    <?php
                                    $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = $sel8 = '';
                                    $sel = $site_config->pagina_fonte_tamanho;
                                    if ($sel == 10) {
                                        $sel1 = 'selected';
                                    }
                                    if ($sel == 11) {
                                        $sel2 = 'selected';
                                    }
                                    if ($sel == 12) {
                                        $sel3 = 'selected';
                                    }
                                    if ($sel == 13) {
                                        $sel4 = 'selected';
                                    }
                                    if ($sel == 14) {
                                        $sel5 = 'selected';
                                    }
                                    if ($sel == 15) {
                                        $sel6 = 'selected';
                                    }
                                    if ($sel == 16) {
                                        $sel7 = 'selected';
                                    }
                                    if ($sel == 18) {
                                        $sel8 = 'selected';
                                    }
                                    echo '<option value="10" ' . $sel1 . '>10px (pequena)</option>';
                                    echo '<option value="11" ' . $sel2 . '>11px (pequena)</option>';
                                    echo '<option value="12" ' . $sel3 . '>12px (pequena)</option>';
                                    echo '<option value="13" ' . $sel4 . '>13px (média)</option>';
                                    echo '<option value="14" ' . $sel5 . '>14px (média)</option>';
                                    echo '<option value="15" ' . $sel6 . '>15px (média)</option>';
                                    echo '<option value="16" ' . $sel7 . '>16px (grande)</option>';
                                    echo '<option value="18" ' . $sel8 . '>18px (grande)</option>';
                                    ?>
                                </select>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Menu Superior</legend>
                            <div class="form-textarea">Botões
                                <br><textarea name="menu_superior_li" id="menu_superior_li" rows="10" cols="100"><?php echo $site_config->menu_superior_li; ?></textarea>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Conteúdo (content)</legend>
                            <div class="form-varchar">Seções Cor Fundo
                                <br><input  class="color" type="text" name="secoes_background" id="secoes_background" size="6" maxlength="6" value="<?php echo $site_config->secoes_background; ?>" required="required" style="border: 1px solid red;">
                            </div>
                            <div class="form-varchar">Tipo de Destaques (Imóveis)
                                <br><select name="tipo_destaques" id="tipo_destaques">
                                    <?php
                                    $tmp = $site_config->tipo_destaques;
                                    $sel1 = $sel2 = $sel3 = '';
                                    if ($tmp == 'a') {
                                        $sel1 = 'selected';
                                    }
                                    if ($tmp == 'v') {
                                        $sel2 = 'selected';
                                    }
                                    if ($tmp == 'l') {
                                        $sel3 = 'selected';
                                    }
                                    echo '<option value="a" ' . $sel1 . '>Vendas e Locação</option>';
                                    echo '<option value="v" ' . $sel2 . '>Apenas Vendas</option>';
                                    echo '<option value="l" ' . $sel3 . '>Apenas Locação</option>';
                                    ?>
                                </select>
                            </div>
                            <div style="clear: both; width: 100%;height: 10px;"></div>
                            <fieldset>
                                <legend>Seção 1</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao1_tipo" id="secao1_tipo">
                                        <?php
                                        $tmp = $site_config->secao1_tipo;
                                        $sel1 = $sel2 = $sel3 = '';
                                        if ($tmp == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($tmp == 'menu') {
                                            $sel2 = 'selected';
                                        }
                                        if ($tmp == '') {
                                            $sel3 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="menu" ' . $sel2 . '>Menu Superior</option>';
                                        echo '<option value="" ' . $sel3 . '>Não Mostrar</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao1_background" id="secao1_background" size="6" maxlength="6" value="<?php echo $site_config->secao1_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fundo
                                    <br><select name="secao1_img" id="secao1_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao1_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 2</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao2_tipo" id="secao2_tipo">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = '';
                                        if ($site_config->secao2_tipo == 'div_logo_menu') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao2_tipo == 'div_banner_menu') {
                                            $sel2 = 'selected';
                                        }
                                        if ($site_config->secao2_tipo == '') {
                                            $sel3 = 'selected';
                                        }
                                        if ($site_config->secao2_tipo == 'lateral') {
                                            $sel4 = 'selected';
                                        }
                                        echo '<option value="div_logo_menu" ' . $sel1 . '>Menu com Logo</option>';
                                        echo '<option value="lateral" ' . $sel4 . '>Menu Lateral</option>';
                                        echo '<option value="div_banner_menu" ' . $sel2 . '>Imagem Banner com Menu</option>';
                                        echo '<option value="" ' . $sel3 . '>Não Mostrar</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao2_background" id="secao2_background" size="6" maxlength="6" value="<?php echo $site_config->secao2_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Mensagem Atendimento
                                    <br><input type="text" name="secao2_atendimento" id="secao2_atendimento" size="102" maxlength="100" value="<?php echo $site_config->secao2_atendimento; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Atendimento Fonte Padrão
                                    <br><select name="secao2_atendimento_fonte" id="secao2_atendimento_fonte">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = $sel8 = $sel9 = $sel10 = $sel11 = $sel12 = $sel13 = $sel14 = $sel15 = '';
                                        $sel = $site_config->secao2_atendimento_fonte;
                                        if ($sel == '\'Alegreya Sans SC\', sans-serif;') {
                                            $sel1 = 'selected';
                                        }
                                        if ($sel == '\'Gilda Display\', serif;') {
                                            $sel2 = 'selected';
                                        }
                                        if ($sel == '\'Oranienbaum\', serif;') {
                                            $sel3 = 'selected';
                                        }
                                        if ($sel == '\'Cantora One\', sans-serif;') {
                                            $sel4 = 'selected';
                                        }
                                        if ($sel == '\'Iceland\', cursive;') {
                                            $sel5 = 'selected';
                                        }
                                        if ($sel == '\'Montserrat\', sans-serif;') {
                                            $sel6 = 'selected';
                                        }
                                        if ($sel == '\'Allerta\', sans-serif;') {
                                            $sel7 = 'selected';
                                        }
                                        if ($sel == '\'Karla\', sans-serif;') {
                                            $sel8 = 'selected';
                                        }
                                        if ($sel == '\'Play\', sans-serif;') {
                                            $sel9 = 'selected';
                                        }
                                        if ($sel == '\'Vollkorn\', serif;') {
                                            $sel10 = 'selected';
                                        }
                                        if ($sel == '\'Coda\', cursive;') {
                                            $sel11 = 'selected';
                                        }
                                        if ($sel == '\'Nova Square\', cursive;') {
                                            $sel12 = 'selected';
                                        }
                                        if ($sel == '\'Roboto Condensed\', sans-serif;') {
                                            $sel13 = 'selected';
                                        }
                                        if ($sel == '\'Lato\', sans-serif;') {
                                            $sel14 = 'selected';
                                        }
                                        if ($sel == '\'Adamina\', serif;') {
                                            $sel15 = 'selected';
                                        }
                                        echo '<option value="\'Alegreya Sans SC\', sans-serif;" ' . $sel1 . '>Alegreya Sans SC</option>';
                                        echo '<option value="\'Gilda Display\', serif" ' . $sel2 . '>Gilda Display</option>';
                                        echo '<option value="\'Oranienbaum\', serif;" ' . $sel3 . '>Oranienbaum</option>';
                                        echo '<option value="\'Cantora One\', sans-serif;" ' . $sel4 . '>Cantora One</option>';
                                        echo '<option value="\'Iceland\', cursive;" ' . $sel5 . '>Iceland</option>';
                                        echo '<option value="\'Montserrat\', sans-serif;" ' . $sel6 . '>Montserrat</option>';
                                        echo '<option value="\'Allerta\', sans-serif;" ' . $sel7 . '>Allerta</option>';
                                        echo '<option value="\'Karla\', sans-serif;" ' . $sel8 . '>Karla</option>';
                                        echo '<option value="\'Play\', sans-serif;" ' . $sel9 . '>Play</option>';
                                        echo '<option value="\'Vollkorn\', serif;" ' . $sel10 . '>Vollkorn</option>';
                                        echo '<option value="\'Coda\', cursive;" ' . $sel11 . '>Coda</option>';
                                        echo '<option value="\'Nova Square\', cursive;" ' . $sel12 . '>Nova Square</option>';
                                        echo '<option value="\'Roboto Condensed\', sans-serif;" ' . $sel13 . '>Roboto Condensed</option>';
                                        echo '<option value="\'Lato\', sans-serif;" ' . $sel14 . '>Lato</option>';
                                        echo '<option value="\'Adamina\', serif;" ' . $sel15 . '>Adamina</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Atendimento Cor Fonte
                                    <br><input  class="color" type="text" name="secao2_atendimento_cor" id="secao2_atendimento_cor" size="6" maxlength="6" value="<?php echo $site_config->secao2_atendimento_cor; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Atendimento Fonte Tamanho (pixels)
                                    <br><select name="secao2_atendimento_tamanho" id="secao2_atendimento_tamanho">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = $sel6 = $sel7 = $sel8 = '';
                                        $sel = $site_config->secao2_atendimento_tamanho;
                                        if ($sel == 10) {
                                            $sel1 = 'selected';
                                        }
                                        if ($sel == 11) {
                                            $sel2 = 'selected';
                                        }
                                        if ($sel == 12) {
                                            $sel3 = 'selected';
                                        }
                                        if ($sel == 13) {
                                            $sel4 = 'selected';
                                        }
                                        if ($sel == 14) {
                                            $sel5 = 'selected';
                                        }
                                        if ($sel == 15) {
                                            $sel6 = 'selected';
                                        }
                                        if ($sel == 16) {
                                            $sel7 = 'selected';
                                        }
                                        if ($sel == 18) {
                                            $sel8 = 'selected';
                                        }
                                        echo '<option value="10" ' . $sel1 . '>10px (pequena)</option>';
                                        echo '<option value="11" ' . $sel2 . '>11px (pequena)</option>';
                                        echo '<option value="12" ' . $sel3 . '>12px (pequena)</option>';
                                        echo '<option value="13" ' . $sel4 . '>13px (média)</option>';
                                        echo '<option value="14" ' . $sel5 . '>14px (média)</option>';
                                        echo '<option value="15" ' . $sel6 . '>15px (média)</option>';
                                        echo '<option value="16" ' . $sel7 . '>16px (grande)</option>';
                                        echo '<option value="18" ' . $sel8 . '>18px (grande)</option>';
                                        ?>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 3</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao3_tipo" id="secao3_tipo">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = '';
                                        if ($site_config->secao3_tipo == 'horizontal') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao3_tipo == 'vertical') {
                                            $sel2 = 'selected';
                                        }

                                        echo '<option value="horizontal" ' . $sel1 . '>Horizontal</option>';
                                        echo '<option value="vertical" ' . $sel2 . '>Vertical</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao3_background" id="secao3_background" size="6" maxlength="6" value="<?php echo $site_config->secao3_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Cor Borda
                                    <br><input  class="color" type="text" name="secao3_border_color" id="secao3_border_color" size="6" maxlength="6" value="<?php echo $site_config->secao3_border_color; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Cor Botão Buscar
                                    <br><input  class="color" type="text" name="secao3_cor_botao" id="secao3_cor_botao" size="6" maxlength="6" value="<?php echo $site_config->secao3_cor_botao; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Cor Fonte Botão Buscar
                                    <br><input  class="color" type="text" name="secao3_cor_fonte_botao" id="secao3_cor_fonte_botao" size="6" maxlength="6" value="<?php echo $site_config->secao3_cor_fonte_botao; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Tipo Cidade
                                    <br><select name="secao3_tipocidade" id="secao3_tipocidade">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = '';
                                        if ($site_config->secao3_tipocidade == 'cidade') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao3_tipocidade == 'localizacao') {
                                            $sel2 = 'selected';
                                        }

                                        echo '<option value="cidade" ' . $sel1 . '>Cidade</option>';
                                        echo '<option value="localizacao" ' . $sel2 . '>Localização</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Tipo Bairro
                                    <br><select name="secao3_tipobairro" id="secao3_tipobairro">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = '';
                                        if ($site_config->secao3_tipobairro == 'bairro') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao3_tipobairro == 'condominio') {
                                            $sel2 = 'selected';
                                        }

                                        echo '<option value="bairro" ' . $sel1 . '>Bairro</option>';
                                        echo '<option value="condominio" ' . $sel2 . '>Condominio</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Mostrar Subtipo
                                    <br><select name="secao3_subtipo" id="secao3_subtipo">
                                        <?php
                                        $sel1 = $sel2 = '';
                                        if ($site_config->secao3_subtipo == 'Sim') {
                                            $sel1 = 'selected';
                                        } else {
                                            $sel2 = 'selected';
                                        }

                                        echo '<option value="Sim" ' . $sel1 . '>Sim</option>';
                                        echo '<option value="Nao" ' . $sel2 . '>Não</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Finalidade exibir Lançamentos
                                    <br><select name="finalidade_lancamento" id="finalidade_lancamento">
                                        <?php
                                        $sel1 = $sel2 = '';
                                        if ($site_config->finalidade_lancamento == 'Sim') {
                                            $sel1 = 'selected';
                                        } else {
                                            $sel2 = 'selected';
                                        }

                                        echo '<option value="Sim" ' . $sel1 . '>Sim</option>';
                                        echo '<option value="Nao" ' . $sel2 . '>Não</option>';
                                        ?>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 4</legend>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao4_background" id="secao4_background" size="6" maxlength="6" value="<?php echo $site_config->secao4_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 5</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao5_tipo" id="secao5_tipo">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = '';
                                        if ($site_config->secao5_tipo == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao5_tipo == 'banner') {
                                            $sel2 = 'selected';
                                        }
                                        if ($site_config->secao5_tipo == 'ofertas') {
                                            $sel3 = 'selected';
                                        }
                                        if ($site_config->secao5_tipo == '') {
                                            $sel4 = 'selected';
                                        }
                                        if ($site_config->secao5_tipo == 'ofertas2') {
                                            $sel5 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="banner" ' . $sel2 . '>Banner de Imagens</option>';
                                        echo '<option value="ofertas" ' . $sel3 . '>Imóveis Destaque</option>';
                                        echo '<option value="ofertas2" ' . $sel5 . '>Imóveis Destaque 2</option>';
                                        echo '<option value="" ' . $sel4 . '>Não Mostrar</option>';
                                        ?>
                                    </select>
                                </div>

                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao5_background" id="secao5_background" size="6" maxlength="6" value="<?php echo $site_config->secao5_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fixa (1000 x 349px)
                                    <br><select name="secao5_img" id="secao5_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Imagem Banner #1
                                    <br><select name="secao5_banner1" id="secao5_banner1">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_banner1 == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Imagem Banner #2
                                    <br><select name="secao5_banner2" id="secao5_banner2">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_banner2 == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Imagem Banner #3
                                    <br><select name="secao5_banner3" id="secao5_banner3">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_banner3 == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Imagem Banner #4
                                    <br><select name="secao5_banner4" id="secao5_banner4">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_banner4 == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Imagem Banner #5
                                    <br><select name="secao5_banner5" id="secao5_banner5">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao5_banner5 == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Efeito do Banner
                                    <br><select name="secao5_efeito" id="secao5_efeito">
                                        <?php
                                        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = '';
                                        $tmp = $site_config->secao5_efeito;
                                        if ($tmp == '1') {
                                            $sel1 = 'selected';
                                        }
                                        if ($tmp == '2') {
                                            $sel2 = 'selected';
                                        }
                                        if ($tmp == '3') {
                                            $sel3 = 'selected';
                                        }
                                        if ($tmp == '4') {
                                            $sel4 = 'selected';
                                        }
                                        if ($tmp == '5') {
                                            $sel5 = 'selected';
                                        }
                                        echo '<option value="1" ' . $sel1 . '>FadeIn FadeOut</option>';
                                        echo '<option value="2" ' . $sel2 . '>Horizontal Scroll</option>';
                                        echo '<option value="3" ' . $sel3 . '>Vertical Scroll</option>';
                                        echo '<option value="4" ' . $sel4 . '>Infinity Horizontal Scroll</option>';
                                        echo '<option value="5" ' . $sel5 . '>Infinity Vertical Scroll</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Velocidade Efeito
                                    <br><input type="text" name="secao5_velocidade_efeito" id="secao5_velocidade_efeito" size="3" maxlength="3" value="<?php echo $site_config->secao5_velocidade_efeito; ?>" required="required" style="border: 1px solid red;"> Seg
                                </div>
                                <div class="form-varchar">Duração Efeito
                                    <br><input type="text" name="secao5_duracao_efeito" id="secao5_duracao_efeito" size="3" maxlength="3" value="<?php echo $site_config->secao5_duracao_efeito; ?>" required="required" style="border: 1px solid red;"> Seg
                                </div>
                                <div class="form-varchar">Intervalo Efeito
                                    <br><input type="text" name="secao5_intervalo_efeito" id="secao5_intervalo_efeito" size="3" maxlength="3" value="<?php echo $site_config->secao5_intervalo_efeito; ?>" required="required" style="border: 1px solid red;"> Seg
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 6</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao6_tipo" id="secao6_tipo">
                                        <?php
                                        $tmp = $site_config->secao6_tipo;
                                        $sel1 = $sel2 = $sel3 = '';
                                        if ($tmp == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($tmp == 'lista') {
                                            $sel2 = 'selected';
                                        }
                                        if ($tmp == 'lista2') {
                                            $sel3 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="lista" ' . $sel2 . '>Listagem de Imóveis</option>';
                                        echo '<option value="lista2" ' . $sel3 . '>Listagem de Imóveis Grande</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao6_background" id="secao6_background" size="6" maxlength="6" value="<?php echo $site_config->secao6_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fundo
                                    <br><select name="secao6_img" id="secao6_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao6_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Cor Fonte
                                    <br><input  class="color" type="text" name="secao6_cor_fonte" id="secao6_cor_fonte" size="6" maxlength="6" value="<?php echo $site_config->secao6_cor_fonte; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Ordem Padrão
                                    <br><select name="ordem_padrao" id="ordem_padrao">
                                        <?php
                                        $tmp = $site_config->ordem_padrao;
                                        $sel1 = $sel2 = $sel3 = $sel4 = $sel5 = '';
                                        if ($tmp == 'referencia') {
                                            $sel1 = 'selected';
                                        }
                                        if ($tmp == 'data_atualizacao') {
                                            $sel2 = 'selected';
                                        }
                                        if ($tmp == 'menor_valor') {
                                            $sel3 = 'selected';
                                        }
                                        if ($tmp == 'maior_valor') {
                                            $sel4 = 'selected';
                                        }
                                        if ($tmp == 'data_captacao') {
                                            $sel5 = 'selected';
                                        }
                                        echo '<option value="referencia" ' . $sel1 . '>Referência</option>';
                                        echo '<option value="data_atualizacao" ' . $sel2 . '>Data Atualização</option>';
                                        echo '<option value="menor_valor" ' . $sel3 . '>Menor Valor</option>';
                                        echo '<option value="maior_valor" ' . $sel4 . '>Maior Valor</option>';
                                        echo '<option value="data_captacao" ' . $sel5 . '>Data Captação</option>';
                                        ?>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 7</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao7_tipo" id="secao7_tipo">
                                        <?php
                                        $sel1 = $sel2 = '';
                                        if ($site_config->secao7_tipo == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao7_tipo == 'N') {
                                            $sel2 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="N" ' . $sel2 . '>Não Mostrar</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao7_background" id="secao7_background" size="6" maxlength="6" value="<?php echo $site_config->secao7_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fundo (Deverá ser 215 x 356 px)
                                    <br><select name="secao7_img" id="secao7_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao7_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-textarea">Conteúdo
                                    <br><textarea name="secao7_conteudo" id="secao7_conteudo" rows="10" cols="100"><?php echo $site_config->secao7_conteudo; ?></textarea>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 8</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao8_tipo" id="secao8_tipo">
                                        <?php
                                        $tmp = $site_config->secao8_tipo;
                                        $sel1 = $sel2 = '';
                                        if ($tmp == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($tmp == 'lista') {
                                            $sel2 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="lista" ' . $sel2 . '>Listagem de Imóveis</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao8_background" id="secao8_background" size="6" maxlength="6" value="<?php echo $site_config->secao8_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fundo (Deverá ser 215 x 356 px)
                                    <br><select name="secao8_img" id="secao8_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao8_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                                <div class="form-varchar">Cor Fonte
                                    <br><input  class="color" type="text" name="secao8_cor_fonte" id="secao8_cor_fonte" size="6" maxlength="6" value="<?php echo $site_config->secao8_cor_fonte; ?>" required="required" style="border: 1px solid red;">
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Seção 9</legend>
                                <div class="form-varchar">Tipo
                                    <br><select name="secao9_tipo" id="secao9_tipo">
                                        <?php
                                        $sel1 = $sel2 = '';
                                        if ($site_config->secao9_tipo == 'img') {
                                            $sel1 = 'selected';
                                        }
                                        if ($site_config->secao9_tipo == 'N') {
                                            $sel2 = 'selected';
                                        }
                                        echo '<option value="img" ' . $sel1 . '>Imagem Fixa</option>';
                                        echo '<option value="N" ' . $sel2 . '>Não Mostrar</option>';
                                        ?>
                                    </select>
                                </div>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="secao9_background" id="secao9_background" size="6" maxlength="6" value="<?php echo $site_config->secao9_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-varchar">Imagem Fundo
                                    <br><select name="secao9_img" id="secao9_img">
                                        <option></option>
                                        <?php
                                        include '../controller/site_imagem.php';
                                        $imgs = json_decode(site_imagem_listar());
                                        foreach ($imgs as $img) {
                                            $sel = '';
                                            $imagem = json_decode(site_imagem_carregar($img));
                                            if ($site_config->secao9_img == $imagem->img) {
                                                $sel = 'selected';
                                            }
                                            if (!empty($imagem->img)) {
                                                echo '<option value="' . $imagem->img . '" ' . $sel . '>' . $imagem->nome . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    echo '&nbsp;<img src="img/xmag.png" width="20" style="cursor: pointer;" onclick="window.open(\'pagina_home_upload.php\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"> ';
                                    ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Rodapé</legend>
                                <div class="form-varchar">Cor Fundo
                                    <br><input  class="color" type="text" name="rodape_background" id="rodape_background" size="6" maxlength="6" value="<?php echo $site_config->rodape_background; ?>" required="required" style="border: 1px solid red;">
                                </div>
                                <div class="form-textarea">Conteúdo
                                    <br><textarea name="rodape_conteudo" id="rodape_conteudo" rows="30" cols="100"><?php echo $site_config->rodape_conteudo; ?></textarea>
                                </div>
                                <div class="form-textarea">CSS
                                    <br><textarea name="rodape_css" id="rodape_css" rows="10" cols="100"><?php echo $site_config->rodape_css; ?></textarea>
                                </div>
                            </fieldset>
                        </fieldset>
                        <input type="submit" value="Gravar" class="botao" onclick="form1.submit();">
                    </div>
            </form>
        </div>
    </body>
</html>