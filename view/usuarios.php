<?php
session_start();

include '../controller/usuario_acesso.php';
$usu = json_decode(usuario_acesso_carregar($_SESSION['usuario_id']));
$tmp = 'usuario_consultar';
if ($usu->$tmp != 'Sim') {
    echo '<script>window.open(\'../view/home.php\',\'_self\');</script>';
}

$adm = mysqli_connect('localhost', 'sgipl134_0000', 'des!fer@','sgipl134_0000');
if (!$adm) {
    die('Nao foi possivel conectar ADM 0000 : ' . mysqli_connect_error());
}

$rlic = mysqli_fetch_row(mysqli_query($adm, " SELECT licencas FROM cliente WHERE sgiplus={$_SESSION['cliente_id']} "));
$lic = 0;
if ($rlic) {
	$lic = $rlic[0];
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
        <link href="css/usuario.css" rel="stylesheet" />
    </head>
    <body>
        <?php
        $m = 'Tabelas';
        include 'menu.php';
        ?>
        <div id="conteudo">
            <h3>Usuários</h3>
            <?php
			
			include '../controller/usuario.php';
			
			$disp = 100;
			if ($lic > 0) {
				
				$disp = $lic;
				
				$usuario_where = " and bloqueado != 'S' and nome != 'ADMIN' ";
                $ret = json_decode(usuario_listar($usuario_where, $usuario_order, $usuario_rows));
                $tot = count($ret);
				
				$disp -= $tot;
				
				echo "<h4>&nbsp;&nbsp;$lic Licenças Contratadas - $disp Licenças Disponíveis.</h4>";
			}
            if ($usu->usuario_alterar == 'Sim' && $disp > 0) {
                echo '<input type="button"  value="Novo" class="botao" onclick="window.open(\'usuario.php?id=add\', \'_self\');">';
            }
            ?>
            <table width="100%">
                <tr class="listagem-titulo">
                    <td>Nome</td>
                    <td>Acesso</td>
                    <td>Bloqueado</td>
                    <td>Logins</td>
                </tr>
                <?php
                
                $usuario_where = " and acesso<=" . $usu->id . " ";
                $ret = json_decode(usuario_listar($usuario_where, $usuario_order, $usuario_rows));
                $tot = count($ret);
                foreach ($ret as $id) {
                    $usuario = json_decode(usuario_carregar($id));
                    echo '<tr class="listagem-item" onclick="window.open(\'usuario.php?id=' . $usuario->id . '\',\'_self\');">';
                    echo '<td>' . $usuario->nome . '</td>';
                    include '../controller/usuario_acesso.php';
                    $acesso = json_decode(acesso_carregar($usuario->acesso));
                    echo '<td>' . $acesso->acesso . '</td>';
                    if ($usuario->bloqueado == 'S') {
                        $bloqueado = 'Sim';
                    } else {
                        $bloqueado = 'Não';
                    }
                    echo '<td>' . $bloqueado . '</td>';
                    echo '<td>' . $usuario->login_ok;
                    if (!empty($usuario->ultimo_login)) {
                        echo ', último em ' . $usuario->ultimo_login;
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '<tr class="listagem-titulo"><td colspan="6">' . $tot . ' registro(s)</td></tr>';
                ?>
            </table>
        </div>
    </body>
</html>


