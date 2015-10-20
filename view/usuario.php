<?php
session_start();

$adm = mysqli_connect('localhost', 'sgipl134_0000', 'des!fer@','sgipl134_0000');
if (!$adm) {
    die('Nao foi possivel conectar ADM 0000 : ' . mysqli_connect_error());
}

$rlic = mysqli_fetch_row(mysqli_query($adm, " SELECT licencas FROM cliente WHERE sgiplus={$_SESSION['cliente_id']} "));
$lic = 0;
if ($rlic) {
	$lic = $rlic[0];
}

$disp = 100;
if ($lic > 0) {
	
	include '../controller/usuario.php';
	
	$disp = $lic;
	
	$usuario_where = " and bloqueado != 'S' and nome != 'ADMIN' ";
	$ret = json_decode(usuario_listar($usuario_where, $usuario_order, $usuario_rows));
	$tot = count($ret);
	
	$disp -= $tot;
	
}

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
        <link href="css/usuario.css" rel="stylesheet" />
        <script>
            function form_campos() {

                return true;
            }

            function grava_home(home) {

                xmlhttpSn = new XMLHttpRequest();
                xmlhttpSn.onreadystatechange = function()
                {
                    if (xmlhttpSn.readyState == 4) {
                        if (xmlhttpSn.status == 200)
                        {

                        }
                    }
                }

                xmlhttpSn.open("GET", "../controller/ajax_usuario_home.php?home=" + home, true);
                xmlhttpSn.send();
            }
        </script>
    </head>
    <body onload="$('#carregando').fadeOut(1000);">
        <div id="carregando"><img src="img/ajax-loader.gif" width="200"></div>
        <?php
        $m = 'Tabelas';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <form action="../controller/usuario_grava.php" method="POST" name="form1" id="form1" onsubmit="return form_campos();">
                <h3>Cadastro de Usu&aacute;rio</h3>
				<?php
				if ($lic > 0) {
					echo "<h4>&nbsp;&nbsp;$lic Licenças Contratadas - $disp Licenças Disponíveis.</h4>";
				}
				?>

                <div class="botoes-form">
                    <input type="button"  value="Voltar" class="botao" onclick="window.open('usuarios.php', '_self');">
                    <?php
                    if ($usu->usuario_alterar == 'Sim') {
                        echo '<input type="submit" value="Gravar" class="botao" onclick="form1.submit();">';
                    }
                    $id = '';
                    $nome = '';
                    $home = 'home.php';
                    $foto = '';
                    $acesso = '';
                    $logins = '';
                    $seg_sex_hi = '00:00';
                    $seg_sex_hf = '24:00';
                    $sab_hi = '00:00';
                    $sab_hf = '24:00';
                    $dom_hi = '00:00';
                    $dom_hf = '24:00';
                    $ip1 = '';
                    $ip2 = '';
                    $home1 = '';
                    $home2 = '';
                    $home3 = '';

                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    }
                    if ($id != 'add') {
                        include '../controller/usuario.php';

                        $usuario = json_decode(usuario_carregar($id));

                        $nome = $usuario->nome;
                        $home = $usuario->home;
                        $foto = $usuario->foto;
                        $acesso = $usuario->acesso;
                        $logins = $usuario->login_ok;
                        if (!empty($usuario->ultimo_login)) {
                            $logins .= ', último em ' . $usuario->ultimo_login;
                        }
                        $seg_sex_hi = $usuario->seg_sex_hi;
                        $seg_sex_hf = $usuario->seg_sex_hf;
                        $sab_hi = $usuario->sab_hi;
                        $sab_hf = $usuario->sab_hf;
                        $dom_hi = $usuario->dom_hi;
                        $dom_hf = $usuario->dom_hf;
                        $ip1 = $usuario->ip1;
                        $ip2 = $usuario->ip2;
                        $home1 = $usuario->home1;
                        $home2 = $usuario->home2;
                        $home3 = $usuario->home3;
                    }

                    if (empty($foto)) {
                        $foto = 'usuario_sem_foto.jpg';
                    }

                    if ($id != 'add') {
                        if ($usu->usuario_alterar == 'Sim') {
                            $readonly = '';
                            if ($disp > 0) {
								echo '<input type="button" value="Novo" class="botao" onclick="window.open(\'usuario.php?id=add\', \'_self\');">';
							}

                            if ($usuario->bloqueado == 'S') {
								if ($disp > 0) {
									echo '<input type="button" value="Desbloquear" class="botao" onclick="if (confirm(\'Deseja Realmente Desbloquear ???\'    )) {';
									echo '                window.open(\'../controller/usuario_exclui.php?id=' . $id . '\', \'_self\');';
									echo '    }">';
								}
                            } else {
                                echo '<input type="button" value="Bloquear" class="botao" onclick="if (confirm(\'Deseja Realmente Bloquear ???\'    )) {';
								echo '                window.open(\'../controller/usuario_exclui.php?id=' . $id . '\', \'_self\');';
								echo '    }">';
                            }
                            
							
                        } else {
                            $readonly = 'readonly class="desativado"';
                        }
                        if ($id == $_SESSION['usuario_id']) {
                            echo '<input type="button" value="Trocar Senha" class="botao" onclick="window.open(\'usuario_senha.php?id=' . $id . '\', \'_blank\',\'width=300,height=300,status=no,address=no,top=200,left=500\');">';
                        } elseif ($usu->usuario_alterar == 'Sim') {
                            echo '<input type="button" value="Resetar Senha" class="botao" onclick="if (confirm(\'Deseja Realmente Resetar para 123456 ???\'    )) {';
                            echo '                window.open(\'../controller/usuario_senha.php?id=' . $id . '\', \'_blank\',\'width=300,height=300,status=no,address=no,top=200,left=500\');';
                            echo '    }">';
                        }
                    }
                    ?>
                </div>
                <div class="form-detalhe" id="form-detalhe">
                    <div class="form-titulo">Ficha de Cadastro</div>
                    <div style="width: 100%;height: 50px;float: left;"></div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="foto" id="foto" value="<?php echo $foto; ?>">
                    <fieldset>
                        <legend>Dados Principais</legend>
                        <div id="foto-detalhe-p" 
                        <?php
                        if ($id != 'add') {
                            echo ' onclick="window.open(\'usuario_foto_upload_p.php?id=' . $id . '\', \'_blank\', \'width=400,height=250,menubar=no,status=no\');"  title="Clique aqui para Fazer Upload de uma foto"';
                        }
                        ?>
                             >
                            <img src="../site/fotos/<?php echo $_SESSION['cliente_id'] . '/' . $foto; ?>" width="100">
                        </div>
                        <div class="form-varchar">Login
                            <br><input type="text" name="nome" id="nome" size="15" maxlength="15" value="<?php echo $nome; ?>" required="required" style="border: 1px solid red;" 
                            <?php
                            if ($id != 'add') {
                                echo 'readonly class="desativado"><i> (não pode ser alterado)</i>';
                            } else {
                                echo '>';
                            }
                            ?>
                        </div>
                        <div class="form-varchar">Acesso
                            <br>
                            <?php
                            include '../controller/usuario_acesso.php';
                            $usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
                            include '../controller/tabela.php';
                            $tabela_vinculo = json_decode(tabela_vinculo('usuario_acesso'), '');
                            if ($usu->usuario_alterar == 'Sim' && $id != $_SESSION['usuario_id']) {
                                echo '<select name="acesso" id="acesso">';
                                foreach ($tabela_vinculo as $vinculo) {
                                    if (!empty($vinculo->acesso)) {
                                        $sel = '';
                                        if ($acesso == $vinculo->id) {
                                            $sel = 'selected';
                                        }
                                        if ($vinculo->id <= $usu->id) {
                                            echo '      <option value="' . $vinculo->id . '" ' . $sel . '>' . $vinculo->acesso . '</option>';
                                        }
                                    }
                                }
                            } else {
                                $xacesso = tabela_carregar_campo('usuario_acesso', 'acesso', $acesso);
                                echo '<input type="text" name="xacesso" id="xacesso" value="' . $xacesso . '" readonly class="desativado">';
                                echo '<input type="hidden" name="acesso" id="acesso" value="' . $acesso . '" readonly class="desativado">';
                            }
                            ?>
                            </select>
                        </div>    
                        <div class="form-varchar">Página Home
                            <br>
                            <?php
                            if ($_SESSION['usuario_id'] == $id) {
                                echo '<select name="home" id="home" onchange="grava_home(this.value);">';
                            } else {
                                echo '<select name="home" id="home">';
                            }
                            $sel0 = $sel1 = $sel2 = $sel3 = $sel4 = '';
                            if ($home == 'home.php') {
                                $sel0 = 'selected';
                            }
                            if ($home == 'agenda.php') {
                                $sel1 = 'selected';
                            }
                            if ($home == 'cadastros.php?id_cadastro=1&tabelas=Não') {
                                $sel2 = 'selected';
                            }
                            if ($home == 'mensagem.php?pasta=Caixa%20de%20entrada') {
                                $sel3 = 'selected';
                            }
                            if ($home == 'ligacoes.php') {
                                $sel4 = 'selected';
                            }
                            echo '<option value="home.php" ' . $sel0 . '>Minha Página</option>';
                            echo '<option value="agenda.php" ' . $sel1 . '>Agenda</option>';
                            echo '<option value="cadastros.php?id_cadastro=1&tabelas=Não" ' . $sel2 . '>Imóveis</option>';
                            echo '<option value="mensagem.php?pasta=Caixa%20de%20entrada" ' . $sel3 . '>Mensagens</option>';
                            echo '<option value="ligacoes.php" ' . $sel4 . '>Ligações</option>';
                            ?>
                            </select>
                        </div> 
                    </fieldset>
                    <br>
                    <fieldset>
                        <legend>Política de Segurança</legend>
                        <fieldset><legend>Horários Permitidos - Utilizar formato 00:00 - Exemplos  07:00, 08:30, 17:30, 09:00...</legend>
                            <div class="form-varchar">Segunda à Sexta
                                <br>de <input type="text" name="seg_sex_hi" id="seg_sex_hi" size="5" maxlength="5" value="<?php echo $seg_sex_hi; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>> até <input type="text" name="seg_sex_hf" id="seg_sex_hf" size="5" maxlength="5" value="<?php echo $seg_sex_hf; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>>
                            </div>
                            <div class="form-varchar">Sábado
                                <br>de <input type="text" name="sab_hi" id="sab_hi" size="5" maxlength="5" value="<?php echo $sab_hi; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>> até <input type="text" name="sab_hf" id="sab_hf" size="5" maxlength="5" value="<?php echo $sab_hf; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>>
                            </div>
                            <div class="form-varchar">Domingo
                                <br>de <input type="text" name="dom_hi" id="dom_hi" size="5" maxlength="5" value="<?php echo $dom_hi; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>> até <input type="text" name="dom_hf" id="dom_hf" size="5" maxlength="5" value="<?php echo $dom_hf; ?>" required="required" style="border: 1px solid red;" <?php echo $readonly; ?>>
                            </div>
                        </fieldset>
                        <fieldset><legend>IP Permitidos</legend>
                            <div class="form-varchar">Endereço #1
                                <br><input type="text" name="ip1" id="ip1" size="15" maxlength="15" value="<?php echo $ip1; ?>" <?php echo $readonly; ?>>
                            </div>
                            <div class="form-varchar">Endereço #2
                                <br><input type="text" name="ip2" id="ip2" size="15" maxlength="15" value="<?php echo $ip2; ?>" <?php echo $readonly; ?>>
                            </div>
                        </fieldset>
                    </fieldset>
                    <div class="form-varchar"># Logins : <?php echo $logins; ?></div>
                    <div style="clear:both; width: 100%; height: 20px; "></div>
                </div>
            </form>
        </div>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    </body>
</html>                                    